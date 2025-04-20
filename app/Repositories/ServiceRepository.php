<?php
declare(strict_types=1);
namespace App\Repositories;

use PDO;
use App\Models\Service;

/**
 * Repository for services data.
 * 
 * Handles database operations for Service objects.
 */
class ServiceRepository
{
    /**
     * Database connection
     * @var PDO
     */
    private PDO $db;

    /**
     * Initialize with database connection.
     */
    public function __construct()
    {
        $this->db = require __DIR__ . '/../../config/database.php';
    }

    /**
     * Get all services.
     *
     * @return array<int, Service> Array of Service objects
     */
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM services ORDER BY id DESC');
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'createServiceFromArray'], $items);
    }

    /**
     * Get service by ID.
     *
     * @param int $id Service ID.
     * @return Service|null Service object or null if not found.
     */
    public function getById(int $id): ?Service
    {
        $stmt = $this->db->prepare('SELECT * FROM services WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $service = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$service) return null;
        
        return $this->createServiceFromArray($service);
    }

    /**
     * Get service by external ID
     * 
     * @param string|int $externalId External ID
     * @return Service|null Service object or null if not found
     */
    public function getByExternalId($externalId): ?Service
    {
        $stmt = $this->db->prepare('SELECT * FROM services WHERE external_id = :external_id');
        $stmt->execute(['external_id' => (string)$externalId]);
        $service = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$service) return null;
        
        return $this->createServiceFromArray($service);
    }

    /**
     * Insert a new service.
     *
     * @param string $name Service name.
     * @param string $description Service description.
     * @return int ID of the new service.
     */
    public function insert(string $name, string $description): int
    {
        $stmt = $this->db->prepare('INSERT INTO services (name, description) VALUES (:name, :description)');
        $stmt->execute([
            'name' => $name,
            'description' => $description
        ]);
        
        return (int) $this->db->lastInsertId();
    }

    /**
     * Insert a new service with external ID
     *
     * @param string|int $externalId External ID
     * @param string $name Service name
     * @param string $description Service description
     * @return int ID of the new service
     */
    public function insertWithExternalId($externalId, string $name, string $description): int
    {
        $stmt = $this->db->prepare('INSERT INTO services (external_id, name, description) VALUES (:external_id, :name, :description)');
        $stmt->execute([
            'external_id' => (string)$externalId,
            'name' => $name,
            'description' => $description
        ]);
        
        return (int) $this->db->lastInsertId();
    }

    /**
     * Update an existing service.
     *
     * @param int $id Service ID.
     * @param string $name New name.
     * @param string $description New description.
     * @return bool True if updated, false otherwise.
     */
    public function update(int $id, string $name, string $description): bool
    {
        $stmt = $this->db->prepare('UPDATE services SET name = :name, description = :description WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description
        ]);
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Delete a service.
     *
     * @param int $id Service ID.
     * @return bool True if deleted, false otherwise.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM services WHERE id = :id');
        $stmt->execute(['id' => $id]);
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Delete all services
     *
     * @return bool True if deleted, false otherwise
     */
    public function deleteAll(): bool
    {
        $stmt = $this->db->prepare('DELETE FROM services');
        $stmt->execute();
        
        return true;
    }
    
    /**
     * Create Service object from database row
     * 
     * @param array<string, mixed> $data Database row as associative array
     * @return Service Populated Service object
     */
    private function createServiceFromArray(array $data): Service
    {
        $service = new Service(
            (int)$data['id'],
            $data['name'],
            $data['description'],
            $data['external_id'] ?? null
        );
        
        $service->created_at = $data['created_at'] ?? null;
        $service->updated_at = $data['updated_at'] ?? null;
        
        return $service;
    }
}
