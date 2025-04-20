<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Services\ServiceService;
use App\Utils\Response;

/**
 * Controller for managing service-related API endpoints.
 * 
 * Handles CRUD operations for services including listing, retrieving,
 * creating, updating and deleting service records.
 */
class ServiceController
{
    /**
     * Service layer for business logic
     * 
     * @var ServiceService
     */
    private ServiceService $serviceService;

    /**
     * Initialize controller with service dependency
     */
    public function __construct()
    {
        $this->serviceService = new ServiceService();
    }

    /**
     * Get all services
     * 
     * @return string JSON response
     */
    public function index(): string
    {
        $services = $this->serviceService->getAllServices(); 
        return Response::json(['data' => $services]); 
    }

    /**
     * Get service by ID
     *
     * @param int $id Service ID
     * @return string JSON response
     */
    public function show(int $id): string
    {
        $service = $this->serviceService->getServiceById($id);
        
        if (!$service) {
            return Response::json(['error' => 'Service not found'], 404);
        }
        
        return Response::json(['data' => $service]);
    }

    /**
     * Create a new service
     * 
     * @return string JSON response
     */
    public function store(): string
    {
        // Parse JSON request body
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (!isset($data['name']) || !isset($data['description'])) {
            return Response::json(['error' => 'Name and description are required'], 400);
        }
        
        $id = $this->serviceService->createService($data['name'], $data['description']);
        
        return Response::json([
            'data' => [
                'id' => $id,
                'message' => 'Service created successfully'
            ]
        ], 201);
    }

    /**
     * Update an existing service
     *
     * @param int $id Service ID
     * @return string JSON response
     */
    public function update(int $id): string
    {
        // Parse JSON request body
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (!isset($data['name']) || !isset($data['description'])) {
            return Response::json(['error' => 'Name and description are required'], 400);
        }
        
        $success = $this->serviceService->updateService($id, $data['name'], $data['description']);
        
        if (!$success) {
            return Response::json(['error' => 'Service not found or no changes made'], 404);
        }
        
        return Response::json([
            'data' => [
                'message' => 'Service updated successfully'
            ]
        ]);
    }

    /**
     * Delete a service
     *
     * @param int $id Service ID
     * @return string JSON response
     */
    public function destroy(int $id): string
    {
        $success = $this->serviceService->deleteService($id);
        
        if (!$success) {
            return Response::json(['error' => 'Service not found'], 404);
        }
        
        return Response::json([
            'data' => [
                'message' => 'Service deleted successfully'
            ]
        ]);
    }
}
