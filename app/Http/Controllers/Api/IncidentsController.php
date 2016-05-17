<?php

namespace App\Http\Controllers\Api;

use App\Database\Incident;
use App\Mixins\WithLoggedInUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class IncidentsController extends Controller
{
    use WithLoggedInUser;

    /**
     * Display a listing of the resource.
     *
     * @param $app_id
     * @param Request $request
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
     * @param $app_id
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($app_id, $id)
    {
        /** @var Incident $incident */
        $incident = Incident::where('id', $id)->where('application_id', $app_id)->firstOrFail();
        return new JsonResponse($incident);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $app_id
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $app_id, $id)
    {
        /** @var Incident $incident */
        $incident = Incident::where('id', $id)->where('application_id', $app_id)->firstOrFail();
        $incident->fill($request->only('status'));
        if($incident->save()) {
            return new JsonResponse($incident, 202);
        } else {
            return abort(500, "Incident could not be saved");
        }
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
