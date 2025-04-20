<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Services\AboutUsService;
use App\Utils\Response;

/**
 * Controller for handling About Us section API endpoints.
 * 
 * Provides CRUD operations for about us content including general information,
 * mission, vision and other company details.
 */
class AboutUsController
{
    /**
     * Service layer for about us data operations
     * 
     * @var AboutUsService
     */
    private AboutUsService $aboutUsService;

    /**
     * Initialize controller with service dependency
     */
    public function __construct()
    {
        $this->aboutUsService = new AboutUsService();
    }

    /**
     * Get all about us items
     * 
     * @return string JSON response
     */
    public function index(): string
    {
        $items = $this->aboutUsService->getAllAboutUsItems();
        return Response::json(['data' => $items]);
    }

    /**
     * Get about us item by ID
     *
     * @param int $id Item ID
     * @return string JSON response
     */
    public function show(int $id): string
    {
        $item = $this->aboutUsService->getAboutUsItemById($id);
        
        if (!$item) {
            return Response::json(['error' => 'Item not found'], 404);
        }
        
        return Response::json(['data' => $item]);
    }

    /**
     * Get about us items by type (general, mission, vision, etc.)
     *
     * @param string $type Item type identifier
     * @return string JSON response
     */
    public function getByType(string $type): string
    {
        $items = $this->aboutUsService->getAboutUsItemsByType($type);
        return Response::json(['data' => $items]);
    }

    /**
     * Create a new about us item
     * 
     * @return string JSON response
     */
    public function store(): string
    {
        // Parse request body
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (!isset($data['title']) || !isset($data['description']) || !isset($data['type'])) {
            return Response::json(['error' => 'Title, description and type are required'], 400);
        }
        
        $id = $this->aboutUsService->createAboutUsItem($data['title'], $data['description'], $data['type']);
        
        return Response::json([
            'data' => [
                'id' => $id,
                'message' => 'Item created successfully'
            ]
        ], 201);
    }

    /**
     * Update an existing about us item
     *
     * @param int $id Item ID
     * @return string JSON response
     */
    public function update(int $id): string
    {
        // Parse request body
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (!isset($data['title']) || !isset($data['description']) || !isset($data['type'])) {
            return Response::json(['error' => 'Title, description and type are required'], 400);
        }
        
        $success = $this->aboutUsService->updateAboutUsItem($id, $data['title'], $data['description'], $data['type']);
        
        if (!$success) {
            return Response::json(['error' => 'Item not found or no changes made'], 404);
        }
        
        return Response::json([
            'data' => [
                'message' => 'Item updated successfully'
            ]
        ]);
    }

    /**
     * Delete an about us item
     *
     * @param int $id Item ID
     * @return string JSON response
     */
    public function destroy(int $id): string
    {
        $success = $this->aboutUsService->deleteAboutUsItem($id);
        
        if (!$success) {
            return Response::json(['error' => 'Item not found'], 404);
        }
        
        return Response::json([
            'data' => [
                'message' => 'Item deleted successfully'
            ]
        ]);
    }
}