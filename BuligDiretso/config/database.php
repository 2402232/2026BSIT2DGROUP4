<?php
// config/database.php
// Requires config.php to be loaded first (defines DB_* constants).
// Use db() from config.php in application code; this class is for test-db-connection.php

if (!defined('DB_HOST')) {
    require_once dirname(__DIR__) . '/config/config.php';
}

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        $this->host = DB_HOST;
        $this->db_name = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;
    }

    public function getConnection() {
        $this->conn = null;

        $charset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4';
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $charset;

        try {
            $this->conn = new PDO(
                $dsn,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch(PDOException $e) {
            error_log("Connection Error: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }

        return $this->conn;
    }

    public function testConnection() {
        try {
            $conn = $this->getConnection();
            return $conn !== null;
        } catch(Exception $e) {
            return false;
        }
    }
}