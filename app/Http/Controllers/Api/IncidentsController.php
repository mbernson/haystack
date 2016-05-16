<?php

namespace App\Http\Controllers\Api;

use App\Database\Incident;
use Illuminate\Http\Request;

class IncidentsController extends Controller
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
        $query = Incident::query();
        $query->where('application_id', $app_id)
            ->where('status', $request->get('status', 'open'));
        
        if($request->has('type')) {
            $query->where('type', $request->get('type'));
        }
        
        $query->orderBy('occurences', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(100);
        return response()->json($query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($app_id, $id)
    {
        $incident = Incident::where('id', $id)->where('application_id', $app_id)->firstOrFail();
        return response()->json($incident);
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
