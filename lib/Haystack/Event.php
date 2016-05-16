<?php namespace Haystack;

class Event
{
    /** @var string */
    public $type;

    public $severity;

    /** @var \DateTime */
    public $created_at;
}