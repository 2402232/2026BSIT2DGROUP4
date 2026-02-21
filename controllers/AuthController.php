<?php
require_once 'HomeController.php';

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
     * Process login form submission
     */
  public function processLogin() {
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate inputs
        if(empty($email) || empty($password)) {
            $_SESSION['error'] = "All fields are required!";
            header("Location: " . BASE_URL . "index.php?action=login");
            exit();
        }

        // Demo credentials array for easy testing
        $demoUsers = [
            [
                'email' => 'admin@gmail.com',
                'password' => 'admin123',
                'id' => 1,
                'name' => 'Admin User',
                'role' => 'admin'
            ],
            [
                'email' => 'user@gmail.com',
                'password' => 'user123',
                'id' => 2,
                'name' => 'Juan Dela Cruz',
                'role' => 'pwd'
            ],
        ];

        // Merge demo users with any users registered during this session
        $registeredUsers = $_SESSION['registered_users'] ?? [];
        $allUsers = array_merge($demoUsers, $registeredUsers);

        // Check credentials
        $loggedIn = false;
        foreach ($allUsers as $user) {
            if (strtolower($email) == strtolower($user['email']) && $password == $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['success'] = "Login successful!";
                $loggedIn = true;
                
                // Redirect based on role
                if ($user['role'] == 'admin') {
                    header("Location: " . BASE_URL . "index.php?action=admin-dashboard");
                } else {
                    header("Location: " . BASE_URL . "index.php?action=dashboard");
                }
                exit();
            }
        }

        if (!$loggedIn) {
            $_SESSION['error'] = "Invalid credentials!";
            header("Location: " . BASE_URL . "index.php?action=login");
            exit();
        }
    }
}

    /**
     * Process signup form submission
     */
    public function processSignup() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Collect and sanitize inputs
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

            // --- Validation ---
            if (empty($fname))    $errors[] = "First name is required.";
            if (empty($lname))    $errors[] = "Last name is required.";
            if (empty($dob))      $errors[] = "Date of birth is required.";
            if (empty($address))  $errors[] = "Address is required.";
            if (empty($role))     $errors[] = "Please select a role.";

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

            // Check for duplicate email among demo + registered users
            $registeredUsers = $_SESSION['registered_users'] ?? [];
            $demoEmails = ['admin@gmail.com', 'user@gmail.com'];
            $allEmails   = array_merge(
                $demoEmails,
                array_column($registeredUsers, 'email')
            );
            if (in_array(strtolower($email), array_map('strtolower', $allEmails))) {
                $errors[] = "An account with that email address already exists.";
            }

            // --- If errors, bounce back with messages + old input ---
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

            // --- Registration successful: store new user in session ---
            if (!isset($_SESSION['registered_users'])) {
                $_SESSION['registered_users'] = [];
            }

            $newId = 100 + count($_SESSION['registered_users']) + 1;
            $_SESSION['registered_users'][] = [
                'id'       => $newId,
                'name'     => $fname . ' ' . $lname,
                'email'    => $email,
                'phone'    => $phone,
                'dob'      => $dob,
                'address'  => $address,
                'role'     => $role,
                'password' => $password,   // plain-text for demo; hash in production
            ];

            // Flash success and redirect to login
            $_SESSION['success'] = "Account created successfully! Please log in.";
            header("Location: " . BASE_URL . "index.php?action=login");
            exit();
        }

        // If accessed via GET, just show the form
        header("Location: " . BASE_URL . "index.php?action=signup");
        exit();
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