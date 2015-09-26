<?php
/*
 * Set the app directory to that of the currently executing file
 * Thus when looking at the code in the home_view.php
 * include APP_DIR . "/view/header.php";
 * the APP_DIR = /htdocs/ b/c the config.php file is located in the htdocs file
 */

define("APP_DIR", __DIR__);

