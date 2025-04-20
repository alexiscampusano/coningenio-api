<?php
declare(strict_types=1);
namespace App\Commands;

use App\Core\HttpClient;
use App\Repositories\ServiceRepository;
use App\Repositories\AboutUsRepository;

/**
 * Synchronizes data from external API to local database.
 * 
 * This command fetches services and about-us information from an external API
 * and stores it in the local database.
 */
class SyncExternalData
{
    /**
     * HTTP client for API requests
     * @var HttpClient
     */
    private HttpClient $client;
    
    /**
     * Repository for services data
     * @var ServiceRepository
     */
    private ServiceRepository $serviceRepository;
    
    /**
     * Repository for about-us data
     * @var AboutUsRepository
     */
    private AboutUsRepository $aboutUs;

    /**
     * Initializes dependencies and API connection
     */
    public function __construct()
    {
        $this->client = new HttpClient('https://ciisa.coningenio.cl/v1', 'ciisa');
        $this->serviceRepository = new ServiceRepository();
        $this->aboutUs = new AboutUsRepository();
    }

    /**
     * Executes the synchronization process
     */
    public function run(): void
    {
        $this->syncServices();
        $this->syncAboutUs();
    }

    /**
     * Synchronizes services data from external API
     * 
     * Fetches services information from the external API and
     * stores it in the local database.
     */
    private function syncServices(): void
    {
        try {
            $response = $this->client->get('/services');
            $servicios = $response['data'] ?? [];
            
            $insertCount = 0;
            $updateCount = 0;
            
            foreach ($servicios as $servicio) {
                try {
                    if (isset($servicio['titulo']['esp']) && isset($servicio['descripcion']['esp'])) {
                        $name = $servicio['titulo']['esp'];
                        $description = $servicio['descripcion']['esp'];
                        
                        if (isset($servicio['id'])) {
                            $existingService = $this->serviceRepository->getByExternalId($servicio['id']);
                            
                            if (!$existingService) {
                                $this->serviceRepository->insertWithExternalId($servicio['id'], $name, $description);
                                $insertCount++;
                                echo "Service inserted: $name\n";
                            } else {
                                $this->serviceRepository->update($existingService->id, $name, $description);
                                $updateCount++;
                                echo "Service updated: $name\n";
                            }
                        } else {
                            $this->serviceRepository->insert($name, $description);
                            $insertCount++;
                            echo "Service inserted (no external ID): $name\n";
                        }
                    } else {
                        $this->logError("Invalid data structure for service: " . json_encode($servicio));
                    }
                } catch (\Exception $e) {
                    $this->logError("Error processing service: " . $e->getMessage());
                }
            }
            
            echo "Services synchronization completed. $insertCount services inserted, $updateCount updated.\n";
        } catch (\Exception $e) {
            $this->logError("General error in services synchronization: " . $e->getMessage());
        }
    }

    /**
     * Synchronizes about-us data from external API
     * 
     * Fetches about-us information from the external API,
     * categorizes content by type (general, mission, vision)
     * and updates or inserts into the local database.
     */
    private function syncAboutUs(): void
    {
        try {
            $response = $this->client->get('/about-us');
            echo "About-us data retrieved from external API\n";
            $aboutUsItems = $response['data'];
            
            $insertCount = 0;
            $updateCount = 0;
            
            foreach ($aboutUsItems as $index => $item) {
                try {
                    if (isset($item['titulo']['esp']) && isset($item['descripcion']['esp'])) {
                        $title = $item['titulo']['esp'];
                        $description = $item['descripcion']['esp'];
                        
                        $type = 'general';
                        if (strtolower($title) === 'misión' || strtolower($title) === 'mision') {
                            $type = 'mission';
                        } else if (strtolower($title) === 'visión' || strtolower($title) === 'vision') {
                            $type = 'vision';
                        }
                        
                        $existingItem = $this->aboutUs->getByTitle($title);
                        
                        if (!$existingItem) {
                            $this->aboutUs->insert($title, $description, $type);
                            $insertCount++;
                            echo "About-us inserted: $title (type: $type)\n";
                        } else {
                            $this->aboutUs->update($existingItem->id, $title, $description, $type);
                            $updateCount++;
                            echo "About-us updated: $title (type: $type)\n";
                        }
                    } else {
                        $this->logError("Invalid data structure for about-us: " . json_encode($item));
                    }
                } catch (\Exception $e) {
                    $this->logError("Error processing about-us: " . $e->getMessage());
                }
            }
            
            echo "About-us synchronization completed. $insertCount items inserted, $updateCount updated.\n";
        } catch (\Exception $e) {
            $this->logError("General error in about-us synchronization: " . $e->getMessage());
        }
    }

    /**
     * Logs error messages to console and file
     * 
     * @param string $message Error message to log
     */
    private function logError(string $message): void
    {
        echo "ERROR: $message\n";
        file_put_contents(__DIR__ . '/../../storage/logs/sync_errors.log', 
            date('[Y-m-d H:i:s] ') . $message . PHP_EOL, 
            FILE_APPEND
        );
    }
}
