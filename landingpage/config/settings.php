<?php

// Load default settings
$settings = require __DIR__ . '/defaults.php';

// Overwrite default settings with environment specific local settings
if (file_exists(__DIR__ . '/../../env.php')) {
    require __DIR__ . '/../../env.php';
} elseif (file_exists(__DIR__ . '/env.php')) {
    require __DIR__ . '/env.php';
}

// Test and integration environment
$environment = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? '';
if ($environment) {
    require __DIR__ . '/local.' . $environment . '.php';
}

// Twig settings
$settings['twig'] = [
    // Template paths
    'paths' => [
        __DIR__ . '/../templates',
    ],
    // Twig environment options
    'options' => [
        // Should be set to true in production
        'cache_enabled' => false,
        'cache_path' => __DIR__ . '/../tmp/twig',
    ],
];

return $settings;
