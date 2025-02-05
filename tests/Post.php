<?php

namespace App\Models;

use App\Core\Model;

/**
 * Post Model
 * 
 * @package App\Models
 */
class Post extends Model
{
    /**
     * @var string The table associated with the model
     */
    protected string $table = 'posts';

    /**
     * Get recent posts
     * 
     * @param int $limit
     * @return array
     */
    public function getRecent(int $limit = 5): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Update a post
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
            $fields[] = "{$key} = :$key";
            $values[":$key"] = $value;
        }
        
        $values[':id'] = $id;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($values);
    }

    /**
     * Delete a post
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
