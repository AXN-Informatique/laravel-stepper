<?php


Stepper::addStep('étape 1', 1);
Stepper::addStep('étape 2', 2);
Stepper::addStep('étape 3', 3);


Stepper::addStep(new Step('étape 1'), 1)





namespace Axn\LaravelStepper;

use Axn\LaravelStepper\Exception\MissingMandatoryParameterException;

class Stepper
{
    protected $steps;

    protected $numSteps;

    protected $currentStep;

    protected $currentStepPosition;

    protected $computed;

    /**
     * Create a new stepper with a given current step.
     *
     * @param string $currentStep
     * @param array $steps
     */
    public function __construct($currentStep, array $steps = [])
    {
        $this->steps = [];
        $this->numSteps = 0;

        $this->currentStep = $currentStep;
        $this->currentStepPosition = 0;

        $this->computed = false;

        if (!empty($steps)) {
            $this->addStepsFromArray($steps);
        }
    }

    /**
     * Add a step to the current stepper.
     *
     * @param string $name
     * @param string $title
     * @param integer $position
     * @return \Axn\LaravelStepper\Stepper
     */
    public function addStep($name, $title = null, $position = null)
    {
        if (null === $position) {
            $position = $this->numSteps +1;
        }

        $this->steps[] = new Step($name, $title, $position);

        $step = new $app->config('stepper.step.class');

        $this->numSteps++;

        $this->computed = false;

        return $this;
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

            $this->addStep(
                $step['name'],
                isset($step['title']) ? $step['title'] : null,
                isset($step['position']) ? $step['position'] : null
            );
        }

        return $this;
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
            if ($step->getName() == $stepName) {
                $exists = true;
            }
        }

        return $exists;
    }

    public function display()
    {
        if (!$this->computed) {
            $this->compute();
        }

        // @todo: this should be defered to a customizable renderer
        /*
        $str = '<div class="ui-widget-content ui-corner-all" id="ariane">' . '	<ul class="step10">';

        foreach ($this->aSteps as $i => $step)
        {
            $str .= '<li';

            if ($step['current']) {
                $str .= ' class="active"';
            } elseif ($step['past']) {
                $str .= ' class="past"';
            }

            if ($step['last']) {
                $str .= ' id="lastStep"';
            }

            $url = !empty($step['url']) ? $step['url'] : '#';

            $str .= '><span><a href="' . $url . '">' . ($i + 1) . '</a></span><a href="' . $url . '">' . $step['title'] . '</a></li>';
        }

        $str .= '	</ul>' . '	<div class="clearer"></div>' . '</div>';

        return $str;
        */
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
            if ($step->getName() == $this->currentStep)
            {
                $step->setCurrent();

                $this->currentStepPosition = $i;
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
}
