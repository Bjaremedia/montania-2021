<?php
/**
 * Constants
 */
define('ROOT', __DIR__ . '/app/');

/**
 * Autoloader for controllers, views and models
 * @param String $name PHP file and class name
 */
spl_autoload_register(function ($name) {
    if (file_exists(ROOT . '/controllers/' . $name . '.php')) require ROOT . '/controllers/' . $name . '.php';
    if (file_exists(ROOT . '/models/' . $name . '.php')) require ROOT . '/models/' . $name . '.php';
    if (file_exists(ROOT . '/views/' . $name . '.php')) require ROOT . '/views/' . $name . '.php';
});

/**
 * Initiate Index Controller
 */
$IndexController = new IndexController;