<?php

include_once __DIR__ . '/../vendor/autoload.php';

use PHPLegends\Http\Response;
use PHPLegends\Http\ServerRequest;
use PHPLegends\Http\Redirector;
use PHPLegends\Http\JsonResponse;


$request = ServerRequest::createFromGlobals();

//$response = new Response('Wallace', 302);

if (! isset($_GET['legal'])) {

	$response = new Redirector('?legal=5');

} else {

	$response = new JsonResponse(['nome' => 'Wallace']);
}

$response->withHeaders(['X-App' => 'PHPLegends\Http'])->send();
