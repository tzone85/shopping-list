<?php

namespace App\Core;

use PDO;
use App\Core\Database;

/**
 * Base Model
 * 
 * @package App\Core
 */
abstract class Model
{
    /**
     * @var PDO The database connection
     */
    protected PDO $db;
    
    /**
     * @var string The table associated with the model
     */
    protected string $table;
    
    /**
     * @var string The primary key of the table
     */
    protected string $primaryKey = 'id';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all records
     * 
     * @return array
     */
    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    /**
     * Find a record by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function find(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Create a new record
     * 
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $values = implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES (:$values)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($data);
    }

    /**
     * Update a record
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= "$key=:$key,";
        }
        $fields = rtrim($fields, ',');
        
        $sql = "UPDATE {$this->table} SET $fields WHERE {$this->primaryKey} = :id";
        $data['id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Delete a record
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Find records by conditions
     * 
     * @param array $conditions
     * @return array
     */
    public function where(array $conditions): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE ";
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "$key = :$key";
        }
        $sql .= implode(' AND ', $where);
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($conditions);
        return $stmt->fetchAll();
    }
}
