<?php
declare(strict_types=1);
namespace App\Models;

/**
 * AboutUs entity representing company information content.
 * 
 * This model represents various company information sections
 * such as general info, mission, vision, and other content
 * that appears in the "About Us" section of the website.
 */
class AboutUs implements \JsonSerializable
{
    /**
     * Unique identifier
     * @var int
     */
    public int $id;
    
    /**
     * Content title
     * @var string
     */
    public string $title;
    
    /**
     * Detailed description or main content text
     * @var string
     */
    public string $description;
    
    /**
     * Content type (general, mission, vision, etc)
     * @var string
     */
    public string $type;
    
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
     * @param int|null $id Optional ID for this about us item
     * @param string|null $title Optional title
     * @param string|null $description Optional description
     * @param string|null $type Optional type (general, mission, vision, etc)
     */
    public function __construct(
        ?int $id = null, 
        ?string $title = null, 
        ?string $description = null, 
        ?string $type = null
    ) {
        if ($id !== null) $this->id = $id;
        if ($title !== null) $this->title = $title;
        if ($description !== null) $this->description = $description;
        if ($type !== null) $this->type = $type;
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
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}