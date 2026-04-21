<?php
/**
 * User model — uses the shared db() connection from config.
 * Load config before this: require_once CONFIG_PATH . 'config.php';
 */

if (!function_exists('db')) {
    require_once dirname(__DIR__) . '/config/config.php';
}

class User {
    /**
     * Resolve DOB column name for mixed schemas.
     */
    private static function getDobColumn(): string {
        static $dobColumn = null;
        if ($dobColumn !== null) {
            return $dobColumn;
        }

        try {
            $pdo = db();
            $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'date_of_birth'");
            if ($stmt && $stmt->fetch(PDO::FETCH_ASSOC)) {
                $dobColumn = 'date_of_birth';
                return $dobColumn;
            }
        } catch (Throwable $e) {
            error_log("DOB column check failed: " . $e->getMessage());
        }

        $dobColumn = 'dob';
        return $dobColumn;
    }

    /**
     * Find a user by email (case-insensitive).
     * @return array|null User row or null
     */
    public static function findByEmail(string $email): ?array {
        $pdo = db();
        $dobColumn = self::getDobColumn();
        $stmt = $pdo->prepare("SELECT *, {$dobColumn} AS date_of_birth FROM users WHERE LOWER(email) = LOWER(?) AND is_active = 1 LIMIT 1");
        $stmt->execute([trim($email)]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Find a user by ID.
     * @return array|null User row or null
     */
    public static function findById(int $id): ?array {
        $pdo = db();
        $dobColumn = self::getDobColumn();
        $stmt = $pdo->prepare("SELECT *, {$dobColumn} AS date_of_birth FROM users WHERE id = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Create a new user. Pass associative array with keys matching table columns.
     * password_hash should already be hashed (e.g. via hash_password() from config).
     * @return int|false New user id or false on failure
     */
    public static function create(array $data) {
        $pdo = db();
        $dobColumn = self::getDobColumn();
        $stmt = $pdo->prepare(
            "INSERT INTO users (first_name, last_name, email, phone, {$dobColumn}, address, role, password_hash) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $ok = $stmt->execute([
            $data['first_name'] ?? '',
            $data['last_name'] ?? '',
            $data['email'] ?? '',
            $data['phone'] ?? '',
            ($data['date_of_birth'] ?? $data['dob'] ?? null) ?: null,
            $data['address'] ?? '',
            $data['role'] ?? 'users',
            $data['password_hash'] ?? '',
        ]);
        return $ok ? (int) $pdo->lastInsertId() : false;
    }

    /**
     * Verify login: returns user row if email exists and password matches.
     * @return array|null User row or null
     */
    public static function verifyLogin(string $email, string $password): ?array {
        try {
            $user = self::findByEmail($email);
            if (!$user) {
                return null;
            }

            $storedHash = (string)($user['password_hash'] ?? '');
            if ($storedHash === '') {
                return null;
            }

            $isValid = false;
            if (function_exists('verify_password') && password_get_info($storedHash)['algo'] !== null) {
                $isValid = verify_password($password, $storedHash);
            } else {
                // Backward compatibility for legacy plaintext/md5 passwords.
                $isValid = hash_equals($storedHash, $password) || hash_equals($storedHash, md5($password));
            }

            if (!$isValid) {
                return null;
            }

            // Auto-upgrade legacy password storage to strong hash.
            if (password_get_info($storedHash)['algo'] === null && function_exists('hash_password')) {
                $newHash = hash_password($password);
                $pdo = db();
                $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                $stmt->execute([$newHash, (int)$user['id']]);
                $user['password_hash'] = $newHash;
            }

            return $user;
        } catch (Throwable $e) {
            error_log("Login verification error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Update user profile fields.
     * profile_photo is only updated when a new value is provided.
     */
    public static function updateProfile(int $id, array $data): bool {
        $pdo       = db();
        $dobColumn = self::getDobColumn();

        $setClauses = [
            "first_name  = ?",
            "last_name   = ?",
            "phone       = ?",
            "address     = ?",
            "{$dobColumn} = ?",
            "updated_at  = NOW()",
        ];
        $params = [
            $data['first_name']     ?? '',
            $data['last_name']      ?? '',
            $data['phone']          ?? '',
            $data['address']        ?? '',
            ($data['date_of_birth'] ?: null),
        ];

        // Only overwrite photo column when a new file was uploaded
        if (!empty($data['profile_photo'])) {
            $setClauses[] = "profile_photo = ?";
            $params[]     = $data['profile_photo'];
        }

        $params[] = $id;
        $sql  = "UPDATE users SET " . implode(', ', $setClauses) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Update a user's password hash.
     */
    public static function updatePassword(int $id, string $password_hash): bool {
        $pdo  = db();
        $stmt = $pdo->prepare(
            "UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?"
        );
        return $stmt->execute([$password_hash, $id]);
    }
}
