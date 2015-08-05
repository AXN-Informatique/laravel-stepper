<?php

namespace Axn\LaravelStepper;

class Step implements StepInterface
{
    private $name;
    private $position;
    private $title;
    private $current;
    private $passed;
    private $first;
    private $last;

    /*
    public function __construct($name, $title = null, $position = null, $current = false, $passed = false, $first = false, $last = false)
    {
        $this->setName($name);
        $this->setTitle($title);

        $this->setPosition($position);

        $this->setCurrent($current);
        $this->setPassed($passed);
        $this->setFirst($first);
        $this->setLast($last);
    }
    */

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setTitle($title = null)
    {
        $this->title = $title;

        if (null === $this->title) {
            $this->title = $this->getName();
        }

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setPosition($position)
    {
        $this->position = (integer)$position;

        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setCurrent($current = true)
    {
        $this->current = $current;

        return $this;
    }

    public function getCurrent()
    {
        return $this->current;
    }

    public function isCurrent()
    {
        return $this->getCurrent();
    }

    public function setPassed($passed = true)
    {
        $this->passed = $passed;

        return $this;
    }

    public function getPassed()
    {
        return $this->passed;
    }

    public function isPassed()
    {
        return $this->getPassed();
    }

    public function setFirst($first = true)
    {
        $this->first = $first;

        return $this;
    }

    public function getFirst()
    {
        return $this->first;
    }

    public function isFirst()
    {
        return $this->getFirst();
    }

    public function setLast($last = true)
    {
        $this->last = $last;

        return $this;
    }

    public function getLast()
    {
        return $this->last;
    }

    public function isLast()
    {
        return $this->getLast();
    }

    public function getNumber()
    {
        return $this->getPosition() + 1;
    }
}
