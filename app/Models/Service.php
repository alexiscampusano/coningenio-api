<?php
declare(strict_types=1);
namespace App\Models;

/**
 * Service entity representing a company service offering.
 * 
 * This model represents services that can be managed through 
 * the API and displayed in the frontend.
 */
class Service implements \JsonSerializable
{
    /**
     * Unique identifier
     * @var int
     */
    public int $id;
    
    /**
     * External identifier from source API
     * @var string|null
     */
    public ?string $external_id = null;
    
    /**
     * Service name
     * @var string
     */
    public string $name;
    
    /**
     * Detailed description of the service
     * @var string
     */
    public string $description;
    
    /**
     * Timestamp when record was created
     * @var string|null
     */
    public ?string $created_at;
    
    /**
     * Timestamp when record was last updated
     * @var string|null
     */
    public ?string $updated_at;
    
    /**
     * Constructor with optional parameter initialization
     *
     * @param int|null $id Optional ID for this service
     * @param string|null $name Optional service name
     * @param string|null $description Optional service description
     * @param string|null $external_id Optional external ID
     */
    public function __construct(
        ?int $id = null, 
        ?string $name = null, 
        ?string $description = null,
        ?string $external_id = null
    ) {
        if ($id !== null) $this->id = $id;
        if ($name !== null) $this->name = $name;
        if ($description !== null) $this->description = $description;
        if ($external_id !== null) $this->external_id = $external_id;
    }

    /**
     * Specify data which should be serialized to JSON
     * 
     * @return array<string, mixed> Data to be serialized
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'external_id' => $this->external_id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
