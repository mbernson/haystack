<?php

namespace App\Http\Controllers\Api;

use App\Database\User;
use App\Mixins\WithLoggedInUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Database\Application;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApplicationsController extends Controller
{
    use WithLoggedInUser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apps = Application::all();
        return response()->json($apps);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $app = new Application();
        $app->fill($request->all());
        $app->user_id = $this->user->getKey();
        $app->api_token = Uuid::uuid4();
        
        if($app->save()) {
            return new JsonResponse($app, 202);
        } else {
            abort(500, 'App could not be saved');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $app = Application::findOrFail($id);
        return new JsonResponse($app);
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
