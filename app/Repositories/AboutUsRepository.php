<?php
declare(strict_types=1);
namespace App\Repositories;

use PDO;
use App\Models\AboutUs;

/**
 * Repository for about us data.
 * 
 * Handles database operations for About Us content sections.
 */
class AboutUsRepository
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
     * Get all about us items.
     *
     * @return array<int, AboutUs> Array of AboutUs objects
     */
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM about_us ORDER BY id ASC');
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'createAboutUsFromArray'], $items);
    }

    /**
     * Get about us item by ID.
     *
     * @param int $id Item ID.
     * @return AboutUs|null Item data or null if not found.
     */
    public function getById(int $id): ?AboutUs
    {
        $stmt = $this->db->prepare('SELECT * FROM about_us WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$item) return null;
        
        return $this->createAboutUsFromArray($item);
    }

    /**
     * Get about us items by type.
     *
     * @param string $type Item type (e.g., 'general', 'mission', 'vision').
     * @return array<int, AboutUs> Array of AboutUs objects matching the type
     */
    public function getByType(string $type): array
    {
        $stmt = $this->db->prepare('SELECT * FROM about_us WHERE type = :type');
        $stmt->execute(['type' => $type]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'createAboutUsFromArray'], $items);
    }

    /**
     * Get about us item by title
     * 
     * @param string $title Item title
     * @return AboutUs|null AboutUs object or null if not found
     */
    public function getByTitle(string $title): ?AboutUs
    {
        $stmt = $this->db->prepare('SELECT * FROM about_us WHERE title = :title');
        $stmt->execute(['title' => $title]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$item) return null;
        
        return $this->createAboutUsFromArray($item);
    }

    /**
     * Insert a new about us item.
     *
     * @param string $title Item title.
     * @param string $description Item description.
     * @param string $type Item type.
     * @return int ID of the new item.
     */
    public function insert(string $title, string $description, string $type): int
    {
        $stmt = $this->db->prepare('INSERT INTO about_us (title, description, type) VALUES (:title, :description, :type)');
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'type' => $type
        ]);
        
        return (int) $this->db->lastInsertId();
    }

    /**
     * Update an existing about us item.
     *
     * @param int $id Item ID.
     * @param string $title New title.
     * @param string $description New description.
     * @param string $type New type.
     * @return bool True if updated, false otherwise.
     */
    public function update(int $id, string $title, string $description, string $type): bool
    {
        $stmt = $this->db->prepare('UPDATE about_us SET title = :title, description = :description, type = :type WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'type' => $type
        ]);
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Delete an about us item.
     *
     * @param int $id Item ID.
     * @return bool True if deleted, false otherwise.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM about_us WHERE id = :id');
        $stmt->execute(['id' => $id]);
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Delete all about us items.
     *
     * @return bool True if deleted, false otherwise.
     */
    public function deleteAll(): bool
    {
        $stmt = $this->db->prepare('DELETE FROM about_us');
        $stmt->execute();
        
        return true;
    }

    /**
     * Create AboutUs object from database row
     * 
     * @param array<string, mixed> $data Database row as associative array
     * @return AboutUs Populated AboutUs object
     */
    private function createAboutUsFromArray(array $data): AboutUs
    {
        $aboutUs = new AboutUs(
            (int)$data['id'],
            $data['title'],
            $data['description'],
            $data['type']
        );
        
        $aboutUs->created_at = $data['created_at'] ?? null;
        $aboutUs->updated_at = $data['updated_at'] ?? null;
        
        return $aboutUs;
    }
}
