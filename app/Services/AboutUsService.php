<?php
declare(strict_types=1);
namespace App\Services;

use App\Repositories\AboutUsRepository;
use App\Models\AboutUs;

/**
 * Service layer for About Us business logic
 */
class AboutUsService
{
    /**
     * AboutUs repository
     * @var AboutUsRepository
     */
    private AboutUsRepository $aboutUsRepository;

    /**
     * Initialize service with repository
     */
    public function __construct()
    {
        $this->aboutUsRepository = new AboutUsRepository();
    }

    /**
     * Get all about us items
     *
     * @return array<int, AboutUs> Array of AboutUs objects
     */
    public function getAllAboutUsItems(): array
    {
        return $this->aboutUsRepository->getAll();
    }

    /**
     * Get about us item by ID
     *
     * @param int $id Item ID
     * @return AboutUs|null AboutUs object or null if not found
     */
    public function getAboutUsItemById(int $id): ?AboutUs
    {
        return $this->aboutUsRepository->getById($id);
    }

    /**
     * Get about us items by type
     *
     * @param string $type Item type
     * @return array<int, AboutUs> Array of AboutUs objects
     */
    public function getAboutUsItemsByType(string $type): array
    {
        return $this->aboutUsRepository->getByType($type);
    }

    /**
     * Create a new about us item.
     *
     * @param string $title Item title.
     * @param string $description Item description.
     * @param string $type Item type.
     * @return int ID of the new item.
     */
    public function createAboutUsItem(string $title, string $description, string $type): int
    {
        return $this->aboutUsRepository->insert($title, $description, $type);
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
    public function updateAboutUsItem(int $id, string $title, string $description, string $type): bool
    {
        return $this->aboutUsRepository->update($id, $title, $description, $type);
    }

    /**
     * Delete an about us item.
     *
     * @param int $id Item ID.
     * @return bool True if deleted, false otherwise.
     */
    public function deleteAboutUsItem(int $id): bool
    {
        return $this->aboutUsRepository->delete($id);
    }
}