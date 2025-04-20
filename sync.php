<?php
declare(strict_types=1);

/**
 * Data Synchronization Script
 * 
 * Synchronizes data from external API to local database.
 * This script is meant to be run as a console command or scheduled task.
 * Example: php sync.php
 */

// Include required dependencies
require_once(__DIR__ . '/config/database.php');
require_once(__DIR__ . '/app/Models/Service.php');
require_once(__DIR__ . '/app/Models/AboutUs.php');
require_once(__DIR__ . '/app/Core/HttpClient.php');
require_once(__DIR__ . '/app/Utils/Logger.php');
require_once(__DIR__ . '/app/Commands/SyncExternalData.php');
require_once(__DIR__ . '/app/Repositories/ServiceRepository.php');
require_once(__DIR__ . '/app/Repositories/AboutUsRepository.php');

use App\Commands\SyncExternalData;
use App\Utils\Logger;

// Set script execution time limit (300 seconds = 5 minutes)
set_time_limit(300);

try {
    Logger::info('Starting data synchronization process');
    
    // Create and run the sync command
    $command = new SyncExternalData();
    $command->run();
    
    Logger::info('Data synchronization completed successfully');
    exit(0); // Success exit code
} catch (\Throwable $e) {
    // Log any unhandled exceptions
    Logger::error('Synchronization failed: ' . $e->getMessage());
    Logger::error('Exception trace: ' . $e->getTraceAsString());
    
    // Display error message to console
    echo "Error: " . $e->getMessage() . PHP_EOL;
    
    exit(1); // Error exit code
}
