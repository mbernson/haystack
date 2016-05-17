<?php

namespace App\Jobs;

use App\Database\Application;
use App\Database\Event;

class StoreEventJob extends Job
{
    private $event;
    private $stack_trace = null;

    /**
     * Create a new job instance.
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->event->createIncidentIfNeeded();
        $this->event->save();
        if(!is_null($this->stack_trace)) {
            $this->event->saveStackTrace($this->stack_trace);
        }
    }
}
