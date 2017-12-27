<?php
// Constants to connect with the database
define('DB_USERNAME', 'Username');
define('DB_PASSWORD', 'Password');
define('DB_HOST', 'host_location');
define('DB_NAME', 'project_db');

// Constants for Tables in database
define('TABLE_USER', 'Table For Name for Users');

// Generic File path constants
define('ROOT', 'Root_Path/');
define('MIDDLEWARE_PATH', 'Middleware_path/');
define('DEPENDENCIES_PATH', 'Dependencies_Path/');
define('PICTURES_PATH', 'asset_path/');
define('EXIF_PATH', 'exif_removal_path/');

// Seaweed FS Photostore Constants
define('MAX_VOL_SERVERS', 1); // Max number of volume servers. This will need to be updated evertime server is added
define('MAX_VOL_ON_SERVER', 7); // Max volumes per Volume Server

// URL Constants for SeaweedFS
define('MASTER_HOST', 'HOST_URL:9333/');
