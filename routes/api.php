<?php

$app->get('/applications/{app_id}', 'ApplicationsController@show');
$app->get('/applications', 'ApplicationsController@index');
$app->post('/applications', 'ApplicationsController@store');
$app->delete('/applications/{app_id}', 'ApplicationsController@destroy');

$app->get('/applications/{app_id}/events', 'EventsController@index');
$app->post('/applications/{app_id}/events', 'EventsController@store');
$app->get('/applications/{app_id}/events/{event_id}', 'EventsController@show');

$app->get('/applications/{app_id}/incidents', 'IncidentsController@index');
$app->get('/applications/{app_id}/incidents/{incident_id}', 'IncidentsController@show');
$app->put('/applications/{app_id}/incidents/{incident_id}', 'IncidentsController@update');
