<?php

/* 
 Include namespace files here, for objects you need to reference.
 NAMESPACE_PATH
 */
// This file adds services to the app's container.

$container = $app->getContainer();

// PDO Database Library (PDO is a standard for PHP)
$container['db'] = function ($c) {
    $settings = $c->get('settings')['db'];
    try {
        $pdo = new PDO('mysql:host=' . $settings['host'] . ';dbname=' . $settings['dbname'], $settings['user'], $settings['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        echo json_encode('DB Connection Failed: ' . $e->getMessage());
        die();
    }
    return $pdo;
};

// SeaweedFS Client
$container['FSClient'] = function ($c) {
    $settings = $c->get('settings')['fsClient'];
    $fsClient = new SeaweedClient($settings['master']);
    return $fsClient;
};

// User Database Controller
$container['UserDb'] = function ($c) {
    return new UserDb($c->get('db'));
};

// User Controller
$container['UserController'] = function ($c) {
    return new UserController($c->get('UserDb'), $c->get('PhotoStoreController'));
};

// Photo Store Controller
$container['PhotoStoreController'] = function ($c) {
    return new PhotoStoreController($c->get('FSClient'));
};
