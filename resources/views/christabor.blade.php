{{--

Encore un autre...

Sources :
    - http://christabor.github.io/css-progress-wizard/

--}}

<style type="text/css">
<!--
/* Copyright 2013-2015 etc Chris Tabor. Licensed under MIT. */

.flexer,
.progress-indicator {
    display: -webkit-box;
    display: -moz-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
}
.no-flexer,
.progress-indicator.stacked {
    display: block;
}
.no-flexer-element {
    -ms-flex: 0;
    -webkit-flex: 0;
    -moz-flex: 0;
    flex: 0;
}
.flexer-element,
.progress-indicator > li {
    -ms-flex: 1;
    -webkit-flex: 1;
    -moz-flex: 1;
    flex: 1;
}
.progress-indicator {
    margin: 0;
    padding: 0;
    font-size: 80%;
    text-transform: uppercase;
    margin-bottom: 1em;
}
.progress-indicator > li {
    list-style: none;
    text-align: center;
    width: auto;
    padding: 0;
    margin: 0;
    position: relative;
    text-overflow: ellipsis;
    color: #bbbbbb;
    display: block;
}
.progress-indicator > li:hover {
    color: #6e6e6e;
}
.progress-indicator > li .bubble {
    border-radius: 1000px;
    width: 20px;
    height: 20px;
    background-color: #bbbbbb;
    display: block;
    margin: 0 auto 0.5em auto;
    border-bottom: 1px solid #888888;
}
.progress-indicator > li .bubble:before,
.progress-indicator > li .bubble:after {
    display: block;
    position: absolute;
    top: 9px;
    width: 100%;
    height: 3px;
    content: '';
    background-color: #bbbbbb;
}
.progress-indicator > li .bubble:before {
    left: 0;
}
.progress-indicator > li .bubble:after {
    right: 0;
}
.progress-indicator > li.completed {
    color: green;
}
.progress-indicator > li.completed .bubble {
    background-color: #65d074;
    color: #65d074;
    border-color: #247830;
}
.progress-indicator > li.completed .bubble:before,
.progress-indicator > li.completed .bubble:after {
    background-color: #65d074;
    border-color: #247830;
}
.progress-indicator > li a:hover .bubble {
    background-color: #5671d0;
    color: #5671d0;
    border-color: #1f306e;
}
.progress-indicator > li a:hover .bubble:before,
.progress-indicator > li a:hover .bubble:after {
    background-color: #5671d0;
    border-color: #1f306e;
}
.progress-indicator > li.danger .bubble {
    background-color: #d3140f;
    color: #d3140f;
    border-color: #440605;
}
.progress-indicator > li.danger .bubble:before,
.progress-indicator > li.danger .bubble:after {
    background-color: #d3140f;
    border-color: #440605;
}
.progress-indicator > li.warning .bubble {
    background-color: #edb10a;
    color: #edb10a;
    border-color: #5a4304;
}
.progress-indicator > li.warning .bubble:before,
.progress-indicator > li.warning .bubble:after {
    background-color: #edb10a;
    border-color: #5a4304;
}
.progress-indicator > li.info .bubble {
    background-color: #5b32d6;
    color: #5b32d6;
    border-color: #25135d;
}
.progress-indicator > li.info .bubble:before,
.progress-indicator > li.info .bubble:after {
    background-color: #5b32d6;
    border-color: #25135d;
}
.progress-indicator.stacked > li {
    text-indent: -10px;
    text-align: center;
    display: block;
}
.progress-indicator.stacked > li .bubble:before,
.progress-indicator.stacked > li .bubble:after {
    left: 50%;
    margin-left: -1.5px;
    width: 3px;
    height: 100%;
}
.progress-indicator.stacked .stacked-text {
    position: relative;
    z-index: 10;
    top: 0;
    margin-left: 60% !important;
    width: 45% !important;
    display: inline-block;
    text-align: left;
    line-height: 1.2em;
}
.progress-indicator.stacked > li a {
    border: none;
}
@media handheld,
screen and (max-width: 400px) {
    .progress-indicator {
        font-size: 60%;
    }
}
-->
</style>


<ul class="progress-indicator">
@foreach ($stepper as $step)
    <li @if($step->isPassed()) class="completed"@endif>
        <span class="bubble"></span>
        {{ $step->getName() }}
    </li>
@endforeach
</ul>

<ul class="progress-indicator">
@foreach ($stepper as $step)
    @if ($step->isCurrent())
    <li class="warning">
        <span class="bubble"></span>
        {{ $step->getName() }}
    </li>
    @elseif ($step->isPassed())
    <li class="completed">
        <span class="bubble"></span>
        <i class="fa fa-check-circle"></i>
        {{ $step->getName() }}
    </li>
    @else
    <li>
        <span class="bubble"></span>
        {{ $step->getName() }}
    </li>
    @endif
@endforeach
</ul>

