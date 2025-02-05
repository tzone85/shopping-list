<?php

namespace App\Database;

use App\Core\Database;
use PDO;

/**
 * Database Seeder
 * 
 * @package App\Database
 */
class Seeder
{
    /**
     * @var PDO The database connection
     */
    private PDO $db;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Run the database seeds
     * 
     * @return void
     */
    public function run(): void
    {
        $this->createTables();
        $this->seedUsers();
        $this->seedPosts();
        $this->seedComments();
    }

    /**
     * Create database tables
     * 
     * @return void
     */
    private function createTables(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(255) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

            CREATE TABLE IF NOT EXISTS posts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

            CREATE TABLE IF NOT EXISTS comments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                post_id INT NOT NULL,
                user_id INT NOT NULL,
                content TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        try {
            $this->db->exec($sql);
            echo "Tables created successfully\n";
        } catch (\PDOException $e) {
            die("Error creating tables: " . $e->getMessage());
        }
    }

    /**
     * Seed the posts table
     * 
     * @return void
     */
    private function seedUsers(): void
    {
        $users = [
            [
                'username' => 'john_doe',
                'email' => 'john@example.com',
                'password_hash' => password_hash('password123', PASSWORD_DEFAULT)
            ],
            [
                'username' => 'jane_smith',
                'email' => 'jane@example.com',
                'password_hash' => password_hash('password123', PASSWORD_DEFAULT)
            ]
        ];

        $sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";
        $stmt = $this->db->prepare($sql);

        try {
            foreach ($users as $user) {
                $stmt->execute($user);
            }
            echo "Users seeded successfully\n";
        } catch (\PDOException $e) {
            die("Error seeding users: " . $e->getMessage());
        }
    }

    private function seedPosts(): void
    {
        $posts = [
            [
                'user_id' => 1,
                'title' => 'Getting Started with PHP',
                'content' => 'PHP is a popular general-purpose scripting language that is especially suited to web development.'
            ],
            [
                'user_id' => 1,
                'title' => 'MVC Architecture',
                'content' => 'Model-View-Controller (MVC) is a software design pattern commonly used for developing user interfaces that divides the related program logic into three interconnected elements.'
            ],
            [
                'user_id' => 2,
                'title' => 'Database Design Best Practices',
                'content' => 'Good database design is crucial for building scalable and maintainable applications. Here are some best practices to follow...'
            ]
        ];

        $sql = "INSERT INTO posts (user_id, title, content) VALUES (:user_id, :title, :content)";
        $stmt = $this->db->prepare($sql);

        try {
            foreach ($posts as $post) {
                $stmt->execute($post);
            }
            echo "Posts seeded successfully\n";
        } catch (\PDOException $e) {
            die("Error seeding posts: " . $e->getMessage());
        }
    }

    /**
     * Seed the comments table
     * 
     * @return void
     */
    private function seedComments(): void
    {
        $comments = [
            [
                'post_id' => 1,
                'user_id' => 2,
                'content' => 'Great introduction to PHP! Very helpful for beginners.'
            ],
            [
                'post_id' => 1,
                'user_id' => 1,
                'content' => 'Thanks! Stay tuned for more PHP tutorials.'
            ],
            [
                'post_id' => 2,
                'user_id' => 2,
                'content' => 'MVC is indeed a powerful pattern. I use it in all my projects.'
            ],
            [
                'post_id' => 3,
                'user_id' => 1,
                'content' => 'These database design tips are invaluable. Thanks for sharing!'
            ]
        ];

        $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)";
        $stmt = $this->db->prepare($sql);

        try {
            foreach ($comments as $comment) {
                $stmt->execute($comment);
            }
            echo "Comments seeded successfully\n";
        } catch (\PDOException $e) {
            die("Error seeding comments: " . $e->getMessage());
        }
    }
}
