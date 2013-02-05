<?php
/**
 * bootstrap.php
 *
 * Bootstrap for PHPUnit
 */

// Naneau Autoloader
spl_autoload_register(function ($className) {

    // Only load "Abc" classes
    if (substr($className, 0, 6) !== 'Naneau') {
        return false;
    }

    // Simple transliteration
    require_once __DIR__ . '/../src/' . str_replace('\\', '/', $className) . '.php';
});

// Require composer autoloader
include __DIR__ . '/../vendor/autoload.php';
