<?php
require '../config/constants.php';
require ROOT . 'vendor/autoload.php';

/*
	Include files needed here 
	(i.e. this file is currently looking for Authentication middleware)
 */

// Start sessions (I can probably remove this. Do later)
session_start();

// Instantiate the app
$settings = require ROOT . SETTINGS_PATH . 'settings.php';
$app = new Slim\App($settings);

// Dependencies
require ROOT . DEPENDENCIES_PATH . 'dependencies.php';

// Middleware

// Cross-Site Request Forgery Guard Protection for Slim Framework
//$app->add(new Slim\Csrf\Guard);
$app->add(new Authentication($app->getContainer()->get('UserDb')));

// Routes
require ROOT . ROUTES_PATH . 'routes.php';

$app->run();
