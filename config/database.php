<?php
declare(strict_types=1);

/**
 * Database connection configuration
 *
 * Creates and returns a PDO connection to the MySQL database
 * with error handling and proper character set settings.
 *
 * @return \PDO The database connection instance
 */

return new \PDO(
    'mysql:host=coningenio-mysql;port=3306;dbname=coningenio;charset=utf8mb4',
    'root',
    'secret123',
    [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false
    ]
);

