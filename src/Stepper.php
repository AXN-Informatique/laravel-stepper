<?php

namespace Axn\LaravelStepper;

use ArrayAccess;
use Iterator;

abstract class Stepper implements ArrayAccess, Iterator
{
    /**
     * Steps stack.
     *
     * @var array
     */
    protected $steps = [];

    /**
     * Number of steps.
     *
     * @var integer
     */
    protected $numSteps = 0;

    /**
     * Name of the current step.
     *
     * @var string
     */
    protected $currentStep;

    /**
     * Index of the current step.
     *
     * @var integer
     */
    protected $currentStepPosition = 0;

    /**
     * Indicate if the stepper was computed.
     *
     * @var boolean
     */
    protected $computed = false;

    /**
     * Name of the default step.
     *
     * @var string
     */
    protected $defaultStepName = 'start';

    /**
     * Name of the step class to instanciate for each steps.
     *
     * @var string
     */
    protected $stepClass = 'Axn\LaravelStepper\Step';

    /**
     * Name of the view to use.
     * @var string
     */
    protected $view = 'stepper::default';

    /**
     * Create a new stepper.
     *
     */
    public function __construct()
    {
        $this->register();
    }

    /**
     * Register the steps of the stepper.
     *
     * @return void
     */
    abstract public function register();

    /**
     * Set the name of the current step.
     *
     * @param string $currentStep
     */
    public function setCurrentStep($currentStep)
    {
        $this->currentStep = $currentStep;
    }

    /**
     * Return the name of the current step.
     *
     * @return string
     */
    public function getCurrentStep()
    {
        if (null === $this->currentStep) {
            $this->currentStep = $this->defaultStepName;
        }

        return $this->currentStep;
    }

    /**
     * Add a step to the current stepper.
     *
     * @param string $name
     * @param string $url
     * @return \Axn\LaravelStepper\StepInterface
     */
    public function addStep($name, $url)
    {
        $step = $this->getStepInstance($name, $url);

        $step->setPosition($this->numSteps +1);

        return $this->doAddStep($step);
    }

    /**
     * Add multiples steps from an array.
     *
     * @param array $steps
     * @throws MissingMandatoryParameterException
     * @return \Axn\LaravelStepper\Stepper
     */
    public function addStepsFromArray(array $steps)
    {
        foreach ($steps as $step)
        {
            if (!isset($step['name'])) {
                throw new MissingMandatoryParameterException('The key "name" is missing to add the step.');
            }
            elseif (!isset($step['url'])) {
                throw new MissingMandatoryParameterException('The key "url" is missing to add the step.');
            }

            $this->addStep($step['name'], $step['url']);
        }

        return $this;
    }

    /**
     * Return a given step by its name.
     *
     * @param string $stepName
     * @return \Axn\LaravelStepper\StepInterface
     */
    public function getStep($stepName)
    {
        $return = null;

        foreach ($this->aSteps as $step)
        {
            if ($step->getName() == $stepName)
            {
                $return = $step;

                $this->rewind();

                break;
            }
        }

        return $return;
    }

    /**
     * Indicate if a given step exists in the current stepper.
     *
     * @param string $stepName
     * @return boolean
     */
    public function stepExists($stepName)
    {
        $exists = false;

        foreach ($this->aSteps as $step)
        {
            if ($step->getName() == $stepName)
            {
                $exists = true;

                $this->rewind();

                break;
            }
        }

        return $exists;
    }

    public function render()
    {
        if (!$this->computed) {
            $this->compute();
        }

        return view($this->view, ['stepper' => $this]);
    }

    /**
     * Return previous step.
     *
     * @return \Axn\LaravelStepper\StepInterface|null
     */
    public function getPrevStep()
    {
        return isset($this->steps[($this->currentStepPosition - 1)])
            ? $this->steps[($this->currentStepPosition - 1)]
            : null;
    }

    /**
     * Return current step.
     *
     * @return \Axn\LaravelStepper\StepInterface|null
     */
    public function getCurrentStep()
    {
        return
            isset($this->steps[$this->currentStepPosition])
            ? $this->steps[$this->currentStepPosition]
            : null;
    }

    /**
     * Return next step.
     *
     * @return \Axn\LaravelStepper\StepInterface|null
     */
    public function getNextStep()
    {
        return
            isset($this->steps[($this->currentStepPosition + 1)])
            ? $this->steps[($this->currentStepPosition + 1)]
            : null;
    }

    /**
     *
     * @param \Axn\LaravelStepper\StepInterface $step
     */
    final protected function doAddStep(StepInterface $step)
    {
        $this->computed = false;

        $this->steps[] = $step;

        $this->numSteps++;

        return $step;
    }

    /**
     * @return \Axn\LaravelStepper\StepInterface
     */
    protected function getStepInstance($name, $url)
    {
        return new $this->stepClass($name, $url);
    }

    /**
     * Compute the current stepper.
     *
     * @return boolean
     */
    protected function compute()
    {
        if ($this->computed) {
            return false;
        }

        $this->sortSteps();

        $this->computeCurrentStep();

        $this->computeSteps();

        $this->computed = true;

        return true;
    }

    /**
     * Sort step by position.
     *
     * @return \Axn\LaravelStepper\Stepper
     */
    protected function sortSteps()
    {
        uasort($this->steps, function ($a, $b) {
            if ($a->getPosition() == $b->getPosition()) {
                return 0;
            }

            return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
        });

        $this->steps = array_values($this->steps);

        return $this;
    }

    /**
     * Set current step for the current stepper.
     *
     * @return \Axn\LaravelStepper\Stepper
     */
    protected function computeCurrentStep()
    {
        foreach ($this->steps as $i => $step)
        {
            if ($step->getName() == $this->getCurrentStep())
            {
                $step->setCurrent();

                $this->currentStepPosition = $i;

                $this->rewind();

                break;
            }
        }

        return $this;
    }

    /**
     * Set the status of the various steps for the current stepper.
     *
     * @return \Axn\LaravelStepper\Stepper
     */
    protected function computeSteps()
    {
        foreach ($this->steps as $i => $step)
        {
            if ($i === 0) {
                $step->setFirst();
            }

            if ($i < $this->currentStepPosition) {
                $step->setPassed();
            }

            if ($i === $this->numSteps - 1) {
                $step->setLast();
            }
        }

        return $this;
    }

    /*
     * ArrayAccess implementation
     */

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->steps[] = $value;
        } else {
            $this->steps[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->steps[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->steps[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->steps[$offset]) ? $this->steps[$offset] : null;
    }

    /*
     * Iterator implementation
     */

    public function rewind()
    {
        return reset($this->steps);
    }

    public function current()
    {
        return current($this->steps);
    }

    public function key()
    {
        return key($this->steps);
    }

    public function prev()
    {
        return prev($this->steps);
    }

    public function next()
    {
        return next($this->steps);
    }

    public function valid()
    {
        return key($this->steps) !== null;
    }
}
