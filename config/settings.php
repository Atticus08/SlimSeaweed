<?php
require_once 'constants.php';

$logDate = new DateTime('2017-06-03', new DateTimeZone('America/New_York'));

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Database connection settings
        'db' => [
            'host' => DB_HOST,
            'dbname' => DB_NAME,
            'user' => DB_USERNAME,
            'pass' => DB_PASSWORD
        ],

        // File System connection settings
        'fsClient' => [
            'master' => MASTER_HOST
        ]
    ],
];
