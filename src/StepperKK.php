<?php

namespace Axn\LaravelStepper;

/*
 * Première version (caca) du stepper qui gère des étape sous forme de tableaux
 * depuis je suis parti sur un stepper qui gère des objets "step" mais
 * je le garde en attendant que ce soit fini car y'a du code
 * à garder là dedans.
 */
class Stepper
{
    protected $aSteps = [];
    protected $iNumSteps = null;
    protected $sCurrentStep = null;
    protected $iCurrentStepPosition = 0;
    protected $defaultStepName = 'start';

    public function __construct(array $aSteps = [], $sCurrentStep = null)
    {
        $this->sCurrentStep = $sCurrentStep !== null ? $sCurrentStep : $this->defaultStepName;

        $aSteps = array_values($aSteps);

        $this->iNumSteps = count($aSteps);

        foreach ($aSteps as $i => $aStep)
        {
            $this->addStep(
                $aStep['step'],
                $aStep['position'],
                $aStep['title']
            );

            $this->aSteps[$i] = [
                'step' => $aStep['step'],
                'title' => $aStep['title'],
                'position' => $aStep['position'],
                'past' => false,
                'current' => false,
                'last' => false
            ];
        }

    }

    public function addSteps(array $steps)
    {
        foreach ($steps as $step)
        {
            if (!isset($step['name']) ||
        }
    }

    public function addStep($name, $position = null, $title = null)
    {
        if (null === $position) {
            $position
        }

        $this->aSteps[] = new Step($name, $position, $title);
    }

    protected function compute()
    {
        $this->sort();

        foreach ($this->aSteps as $i => $step)
        {
            if ($step['step'] === $this->sCurrentStep)
            {
                $this->aSteps[$i]['current'] = true;
                $this->iCurrentStepPosition = $i;
            }
        }

        foreach ($this->aSteps as $i => $aStep)
        {
            if ($i < $this->iCurrentStepPosition) {
                $this->aSteps[$i]['past'] = true;
            }

            if ($i === $this->iNumSteps - 1) {
                $this->aSteps[$i]['last'] = true;
            }
        }
    }

    public function stepExists($sStep)
    {
        foreach ($this->aSteps as $aStep) {
            if ($aStep['step'] == $sStep) {
                return true;
            }
        }

        return false;
    }

    public function display()
    {
        $str = '<div class="ui-widget-content ui-corner-all" id="ariane">' . '	<ul class="step10">';

        foreach ($this->aSteps as $i => $step) {
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

    /**
     * Sort step by position.
     *
     * @return void
     */
    protected function sort()
    {
        uasort($this->aSteps, function ($a, $b) {
            if ($a['position'] == $b['position']) {
                return 0;
            }

            return ($a['position'] < $b['position']) ? -1 : 1;
        });

        $this->aSteps = array_values($this->aSteps);
    }
}
