<?php

namespace App\Http\Controllers\Api;

use App\Database\Application;
use App\Database\Event;

use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function __construct() {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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

        return response()->json($query->get());
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
            return response()->json($event);
        } else {
            abort(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($app_id, $id)
    {
        /** @var Event $event */
        $event = Event::where('id', $id)->where('application_id', $app_id)->firstOrFail();
        $output = $event->toArray();
        $output['stack_trace'] = $event->getStackTrace();
        return response()->json($output);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
