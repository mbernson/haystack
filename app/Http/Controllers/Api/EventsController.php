<?php

namespace App\Http\Controllers\Api;

use App\Database\Application;
use App\Database\Event;

use App\Jobs\StoreEventJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventsController extends Controller
{
    public function __construct() {
    }

    /**
     * Display a listing of the resource.
     *
     * @param $app_id
     * @param Request $request
     * @return Response
     */
    public function index($app_id, Request $request)
    {
        $query = Event::query();
        $query->select(
            'events.id',
            'events.application_id',
            'events.incident_id',
            'events.title',
            'events.type',
            'incidents.status',
            'incidents.occurences',
            'events.created_at'
        )
            ->where('events.application_id', $app_id)
            ->where('status', $request->get('status', 'open'))
            ->join('incidents', 'events.incident_id', '=', 'incidents.id')
            ->orderBy('events.created_at', 'desc')
            ->limit(100);

        return new JsonResponse($query->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $app_id
     * @param  \Illuminate\Http\Request $request
     * @return Response
     */
    public function store($app_id, Request $request)
    {
        /** @var Application $app */
        $app = Application::findOrFail($app_id);
        $event = new Event();

        $event->application_id = $app->getKey();
        $event->fill($request->all());
        $event->created_at = new \DateTime();


        if($event->createIncidentIfNeeded() && $event->save() && $event->saveStackTraceIfNeeded($request)) {
            return new JsonResponse($event, 201);
        } else {
            abort(500, 'Event could not be saved');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $app_id
     * @param  \Illuminate\Http\Request $request
     * @return Response
     */
    public function storeAsync($app_id, Request $request)
    {
        /** @var Application $app */
        $app = Application::findOrFail($app_id);
        $event = new Event();

        $event->application_id = $app->getKey();
        $event->fill($request->all());
        $event->created_at = new \DateTime();

        $job = new StoreEventJob($event);
        $this->dispatch($job);

        return new JsonResponse(['saved' => true], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $app_id
     * @param  int $id
     * @return Response
     */
    public function show($app_id, $id)
    {
        /** @var Event $event */
        $event = Event::where('id', $id)->where('application_id', $app_id)->firstOrFail();
        $output = $event->toArray();
        $output['stack_trace'] = $event->getStackTrace();
        return new JsonResponse($output);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $app_id, $id)
    {
        /** @var Event $event */
        $event = Event::where('id', $id)->where('application_id', $app_id)->firstOrFail();
        $event->fill($request->all());
        if($event->save() && $event->saveStackTraceIfNeeded($request)) {
            return new JsonResponse($event, 202);
        } else {
            abort(500, 'Event could not be saved');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $app_id
     * @param  int $id
     * @return Response
     * @throws \Exception
     */
    public function destroy($app_id, $id)
    {
        /** @var Event $event */
        $event = Event::where('id', $id)->where('application_id', $app_id)->firstOrFail();
        if($event->delete()) {
            return new JsonResponse($event, 202);
        } else {
            return abort(500, 'Event could not be deleted');
        }
    }
}
