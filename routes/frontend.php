<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/crash', function () use ($app) {
    try {
//        $a = 0 / 0;
        throw new \Exception('Oh no!', 1337);
    } catch(\Exception $e) {
        var_dump($e);
    }
});
