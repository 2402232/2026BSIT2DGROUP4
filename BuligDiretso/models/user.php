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
     * Find a user by email (case-insensitive).
     * @return array|null User row or null
     */
    public static function findByEmail(string $email): ?array {
        $pdo = db();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE LOWER(email) = LOWER(?) AND is_active = 1 LIMIT 1");
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
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND is_active = 1 LIMIT 1");
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
        $stmt = $pdo->prepare(
            "INSERT INTO users (first_name, last_name, email, phone, date_of_birth, address, role, password_hash) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $ok = $stmt->execute([
            $data['first_name'] ?? '',
            $data['last_name'] ?? '',
            $data['email'] ?? '',
            $data['phone'] ?? '',
            $data['date_of_birth'] ?: null,
            $data['address'] ?? '',
            $data['role'] ?? 'pwd',
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
}
