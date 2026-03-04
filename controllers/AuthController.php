<?php
require_once 'HomeController.php';
require_once MODEL_PATH . 'user.php';

class AuthController extends HomeController {
    public function showLogin() {
        $pageTitle = "Login - BuligDiretso";

        // Shared header/footer data
        extract($this->getSharedData());

        require_once VIEW_PATH . 'login.php';
    }

    public function showSignup() {
        $pageTitle = "Sign Up - BuligDiretso";

        // Shared header/footer data
        extract($this->getSharedData());

        require_once VIEW_PATH . 'signup.php';
    }

    /**
     * Process login form submission — validates against users table in SQL
     */
    public function processLogin() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . BASE_URL . "index.php?action=login");
            exit();
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "All fields are required!";
            header("Location: " . BASE_URL . "index.php?action=login");
            exit();
        }

        $user = User::verifyLogin($email, $password);
        if ($user) {
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_name'] = trim($user['first_name'] . ' ' . $user['last_name']);
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['success'] = "Login successful!";

            if ($user['role'] === 'admin') {
                header("Location: " . BASE_URL . "index.php?action=admin-dashboard");
            } else {
                header("Location: " . BASE_URL . "index.php?action=dashboard");
            }
            exit();
        }

        $_SESSION['error'] = "Invalid email or password.";
        header("Location: " . BASE_URL . "index.php?action=login");
        exit();
    }

    /**
     * Process signup form submission
     */
    public function processSignup() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . BASE_URL . "index.php?action=signup");
            exit();
        }

        $fname            = trim($_POST['first_name']       ?? '');
        $lname            = trim($_POST['last_name']        ?? '');
        $email            = trim($_POST['email']            ?? '');
        $phone            = trim($_POST['phone']            ?? '');
        $dob              = trim($_POST['dob']              ?? '');
        $address          = trim($_POST['address']          ?? '');
        $role             = trim($_POST['role']             ?? '');
        $password         = $_POST['password']              ?? '';
        $confirm_password = $_POST['confirm_password']      ?? '';

        $errors = [];

        if (empty($fname))    $errors[] = "First name is required.";
        if (empty($lname))    $errors[] = "Last name is required.";
        if (empty($dob))      $errors[] = "Date of birth is required.";
        if (empty($address))  $errors[] = "Address is required.";
        if (empty($role))     $errors[] = "Please select a role.";

        $validRoles = ['admin', 'pwd', 'responder'];
        if (!empty($role) && !in_array($role, $validRoles)) {
            $errors[] = "Invalid role selected.";
        }

        if (empty($email)) {
            $errors[] = "Email address is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        }

        if (empty($phone)) {
            $errors[] = "Phone number is required.";
        } elseif (!preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
            $errors[] = "Please enter a valid phone number.";
        }

        if (empty($password)) {
            $errors[] = "Password is required.";
        } elseif (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters.";
        }

        if ($password !== $confirm_password) {
            $errors[] = "Passwords do not match.";
        }

        if (empty($errors) && User::findByEmail($email)) {
            $errors[] = "An account with that email address already exists.";
        }

        if (!empty($errors)) {
            $_SESSION['signup_errors'] = $errors;
            $_SESSION['signup_old']    = [
                'first_name' => $fname,
                'last_name'  => $lname,
                'email'      => $email,
                'phone'      => $phone,
                'dob'        => $dob,
                'address'    => $address,
                'role'       => $role,
            ];
            header("Location: " . BASE_URL . "index.php?action=signup");
            exit();
        }

        $password_hash = hash_password($password);
        $data = [
            'first_name'    => $fname,
            'last_name'     => $lname,
            'email'         => $email,
            'phone'         => $phone,
            'date_of_birth' => $dob ?: null,
            'address'       => $address,
            'role'          => $role,
            'password_hash' => $password_hash,
        ];

        try {
            $userId = User::create($data);
            if (!$userId) {
                $_SESSION['signup_errors'] = ["Registration failed. Please try again."];
                $_SESSION['signup_old'] = [
                    'first_name' => $fname,
                    'last_name'  => $lname,
                    'email'      => $email,
                    'phone'      => $phone,
                    'dob'        => $dob,
                    'address'    => $address,
                    'role'       => $role,
                ];
                header("Location: " . BASE_URL . "index.php?action=signup");
                exit();
            }

            if ($role === 'responder') {
                $pdo = db();
                $pdo->prepare("INSERT INTO responders (user_id) VALUES (?)")->execute([$userId]);
            }

            $_SESSION['success'] = "Account created successfully! Please log in.";
            header("Location: " . BASE_URL . "index.php?action=login");
            exit();
        } catch (Throwable $e) {
            error_log("Signup error: " . $e->getMessage());
            $msg = "Registration failed. Please try again later.";
            if (defined('IS_LOCAL') && IS_LOCAL) {
                $msg .= " [Debug: " . htmlspecialchars($e->getMessage()) . "]";
            }
            $_SESSION['signup_errors'] = [$msg];
            $_SESSION['signup_old'] = [
                'first_name' => $fname,
                'last_name'  => $lname,
                'email'      => $email,
                'phone'      => $phone,
                'dob'        => $dob,
                'address'    => $address,
                'role'       => $role,
            ];
            header("Location: " . BASE_URL . "index.php?action=signup");
            exit();
        }
    }

    /**
     * Logout user
     */
    public function logout() {
        session_destroy();
        header("Location: " . BASE_URL . "index.php?action=home");
        exit();
    }
}