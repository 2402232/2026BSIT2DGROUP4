<?php

if (!function_exists('db')) {
    require_once dirname(__DIR__) . '/config/config.php';
}

class EmergencyContact
{
    public static function ensureTable(): void
    {
        $pdo = db();
        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS emergency_contacts (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                user_id INT UNSIGNED NOT NULL,
                contact_name VARCHAR(120) NOT NULL,
                relationship VARCHAR(80) NOT NULL,
                phone VARCHAR(30) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_emergency_contacts_user_id (user_id),
                CONSTRAINT fk_emergency_contacts_user
                    FOREIGN KEY (user_id) REFERENCES users(id)
                    ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
    }

    public static function getByUserId(int $userId): array
    {
        self::ensureTable();
        $pdo = db();
        $stmt = $pdo->prepare(
            "SELECT id, contact_name, relationship, phone
             FROM emergency_contacts
             WHERE user_id = ?
             ORDER BY id DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function countByUserId(int $userId): int
    {
        self::ensureTable();
        $pdo = db();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM emergency_contacts WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public static function create(int $userId, string $name, string $relationship, string $phone): bool
    {
        self::ensureTable();
        $pdo = db();
        $stmt = $pdo->prepare(
            "INSERT INTO emergency_contacts (user_id, contact_name, relationship, phone)
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$userId, $name, $relationship, $phone]);
    }

    public static function deleteForUser(int $contactId, int $userId): bool
    {
        self::ensureTable();
        $pdo = db();
        $stmt = $pdo->prepare("DELETE FROM emergency_contacts WHERE id = ? AND user_id = ? LIMIT 1");
        return $stmt->execute([$contactId, $userId]);
    }
}

