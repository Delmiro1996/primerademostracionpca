<?php

use Symfony\Component\HttpFoundation\Request;
date_default_timezone_set('America/Bogota');

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});


//Ruta de demostración, para validar que se recibe(n) dato(s) y se responde con este mismo
$app->post('/enviarDato', function (Request $request) use ($app) {
   return $request;
});


//Ruta de demostración, se recibe(n) dato(s) y se manipulan
$app->post('/guardarDato', function (Request $request) use ($app) {


	$dbconn = pg_pconnect("host=ec2-23-23-36-227.compute-1.amazonaws.com port=5432 dbname=d7lsabr1bj66cl user=yhgeuiosgoltxh password=7de37d1e54e0afae2c8fc0867184b8791a7da2403211f94feabe7573dc224897");

	$data = array(
		"fecha"=>date('Y-m-d H:i:s'),
		"puerta1" => $request->get('puerta1'),
        "puerta2" => $request->get('puerta2'),
        "ventana1"=> $request->get('ventana1'),
        "ventana2"=> $request->get('ventana2'),
        "garaje"=> $request->get('garaje'),
        "luces_externas"=> $request->get('luces_externas'),
		);

	$respuesta = pg_insert($dbconn, "security", $data);
   	
   	return $respuesta;
});

//Ruta de demostración, se recibe(n) dato(s) y se manipulan
$app->post('/postArduino', function (Request $request) use ($app) {
   	return "OK";
});

$app->run();
