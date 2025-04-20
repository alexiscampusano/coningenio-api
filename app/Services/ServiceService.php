<?php
declare(strict_types=1);
namespace App\Services;

use App\Repositories\ServiceRepository;
use App\Models\Service;

/**
 * Service layer for services business logic.
 */
class ServiceService
{
    /**
     * Service repository
     * @var ServiceRepository
     */
    private ServiceRepository $serviceRepository;

    /**
     * Initialize with repository.
     */
    public function __construct()
    {
        $this->serviceRepository = new ServiceRepository();
    }

    /**
     * Get all services.
     *
     * @return array<int, Service> List of service objects.
     */
    public function getAllServices(): array
    {
        return $this->serviceRepository->getAll();
    }

    /**
     * Get service by ID.
     *
     * @param int $id Service ID.
     * @return Service|null Service object or null if not found.
     */
    public function getServiceById(int $id): ?Service
    {
        return $this->serviceRepository->getById($id);
    }

    /**
     * Create a new service.
     *
     * @param string $name Service name.
     * @param string $description Service description.
     * @return int ID of the new service.
     */
    public function createService(string $name, string $description): int
    {
        return $this->serviceRepository->insert($name, $description);
    }

    /**
     * Update an existing service.
     *
     * @param int $id Service ID.
     * @param string $name New name.
     * @param string $description New description.
     * @return bool True if updated, false otherwise.
     */
    public function updateService(int $id, string $name, string $description): bool
    {
        return $this->serviceRepository->update($id, $name, $description);
    }

    /**
     * Delete a service.
     *
     * @param int $id Service ID.
     * @return bool True if deleted, false otherwise.
     */
    public function deleteService(int $id): bool
    {
        return $this->serviceRepository->delete($id);
    }
}
