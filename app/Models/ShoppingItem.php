<?php

namespace App\Models;

use App\Core\Model;

/**
 * ShoppingItem Model
 * 
 * @package App\Models
 */
class ShoppingItem extends Model
{
    /**
     * @var string The table associated with the model
     */
    protected string $table = 'shopping_items';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->createTableIfNotExists();
    }

    /**
     * Create the shopping_items table if it doesn't exist
     */
    private function createTableIfNotExists(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            quantity INT DEFAULT 1,
            checked BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        $this->db->exec($sql);
    }

    /**
     * Get all shopping items ordered by creation date
     * 
     * @return array
     */
    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    /**
     * Create a new shopping item
     * 
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (name, quantity) VALUES (:name, :quantity)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':name' => $data['name'],
            ':quantity' => $data['quantity'] ?? 1
        ]);
    }

    /**
     * Update a shopping item
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
            $values[":{$key}"] = $value;
        }
        
        $values[':id'] = $id;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($values);
    }

    /**
     * Toggle the checked status of an item
     * 
     * @param int $id
     * @return bool
     */
    public function toggleChecked(int $id): bool
    {
        $sql = "UPDATE {$this->table} SET checked = NOT checked WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Delete a shopping item
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
