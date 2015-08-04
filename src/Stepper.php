<?php

namespace Axn\LaravelStepper;

use Axn\LaravelStepper\Exception\MissingMandatoryParameterException;

class Stepper
{
    private $steps;

    private $numSteps;

    private $currentStep;

    private $currentStepPosition;

    public function __construct($currentStep, array $steps = [])
    {
        $this->steps = [];
        $this->numSteps = 0;

        $this->currentStep = $currentStep;
        $this->currentStepPosition = 0;

        if (!empty($steps)) {
            $this->addStepsFromArray($steps);
        }
    }

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

    public function addStep($name, $title = null, $position = null)
    {
        if (null === $position) {
            $position = $this->numSteps +1;
        }

        $this->steps[] = new Step($name, $title, $position);

        $this->numSteps++;

        return $this;
    }

    protected function compute()
    {
        $this->sort();

        # first pass to set current step
        foreach ($this->steps as $i => $step)
        {
            if ($step->getName() == $this->currentStep)
            {
                $step->setCurrent();

                $this->currentStepPosition = $i;
            }
        }

        # second pass to set step metadata
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
    }

    public function stepExists($stepName)
    {
        foreach ($this->aSteps as $step)
        {
            if ($step->getName() == $stepName) {
                return true;
            }
        }

        return false;
    }

    public function display()
    {
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
    }

    /*
    public function getPrevStep()
    {
        return isset($this->aSteps[($this->iCurrentStepPosition - 1)]['step']) ? $this->aSteps[($this->iCurrentStepPosition - 1)]['step'] : null;
    }

    public function getCurrentStep()
    {
        return isset($this->aSteps[$this->iCurrentStepPosition]['step']) ? $this->aSteps[$this->iCurrentStepPosition]['step'] : null;
    }

    public function getNextStep()
    {
        return isset($this->aSteps[($this->iCurrentStepPosition + 1)]['step']) ? $this->aSteps[($this->iCurrentStepPosition + 1)]['step'] : null;
    }
    */

    /**
     * Sort step by position.
     *
     * @return void
     */
    protected function sort()
    {
        uasort($this->steps, function ($a, $b) {
            if ($a->getPosition() == $b->getPosition()) {
                return 0;
            }

            return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
        });

        $this->steps = array_values($this->steps);
    }
}
