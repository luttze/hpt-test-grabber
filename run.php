<?php

declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';


use HPT\CzcGrabber;
use HPT\Dispatcher;
use HPT\Output;



$grabber = new CzcGrabber();
$output = new Output();
$dispatcher = new Dispatcher($grabber, $output);

$dispatcher->run();
