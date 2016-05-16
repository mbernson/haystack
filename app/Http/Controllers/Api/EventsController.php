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
    public function index($app_id)
    {
        $events = Event::where('application_id', $app_id)
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();
        return response()->json($events);
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

        if($request->has('stack_trace')) {
            $event->saveStackTrace($request->get('stack_trace'));
        }

        if($event->createIncidentIfNeeded() && $event->save()) {
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
        $event = Event::where('id', $id)->where('application_id', $app_id)->firstOrFail();
        return response()->json($event);
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
