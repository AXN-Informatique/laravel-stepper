<?php

namespace Axn\LaravelStepper;

class Step implements StepInterface
{
    private $name;

    private $url;

    private $position;

    private $title;

    private $current;

    private $passed;

    private $first;

    private $last;

    public function __construct($name, $url, $position = null)
    {
        $this->setName($name);

        $this->setUrl($url);

        $this->setPosition($position);
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl()
    {
        return $this->url;
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

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
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
