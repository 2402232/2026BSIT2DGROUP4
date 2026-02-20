<?php
require_once CONTROLLER_PATH . 'HomeController.php';

class AuthController extends HomeController
{
    public function showLogin()
    {
        $pageTitle = 'Login - BuligDiretso';
        extract($this->getSharedData());
        require_once VIEW_PATH . 'login.php';
    }

    public function showSignup()
    {
        $pageTitle = 'Sign Up - BuligDiretso';
        extract($this->getSharedData());
        require_once VIEW_PATH . 'signup.php';
    }

    /* ------------------------------------------------------------------ */
    public function processLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=login');
            exit;
        }

        $email    = trim($_POST['email']    ?? '');
        $password =      $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $_SESSION['error'] = 'Please enter your email and password.';
            header('Location: ' . BASE_URL . 'index.php?action=login');
            exit;
        }

        try {
            $pdo  = db();
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND is_active = 1 LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id']       = $user['id'];
                $_SESSION['user_name']     = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email']    = $user['email'];
                $_SESSION['user_role']     = $user['role'];
                $_SESSION['user_photo']    = $user['profile_photo'] ?? '';
                $_SESSION['login_success'] = true;

                $dest = ($user['role'] === 'admin') ? 'admin-dashboard' : 'dashboard';
                header('Location: ' . BASE_URL . 'index.php?action=' . $dest);
                exit;
            }

            $_SESSION['error'] = 'Invalid email or password. Please check your credentials and try again.';
            header('Location: ' . BASE_URL . 'index.php?action=login');
            exit;

        } catch (PDOException $e) {
            // Show the real DB error so you can diagnose it
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'index.php?action=login');
            exit;
        }
    }

    /* ------------------------------------------------------------------ */
    public function processSignup()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=signup');
            exit;
        }

        $fname    = trim($_POST['first_name']       ?? '');
        $lname    = trim($_POST['last_name']        ?? '');
        $email    = trim($_POST['email']            ?? '');
        $phone    = trim($_POST['phone']            ?? '');
        $dob      = trim($_POST['dob']              ?? '');
        $address  = trim($_POST['address']          ?? '');
        $role     = trim($_POST['role']             ?? '');
        $password =      $_POST['password']         ?? '';
        $confirm  =      $_POST['confirm_password'] ?? '';

        $errors = [];

        if ($fname   === '') $errors[] = 'First name is required.';
        if ($lname   === '') $errors[] = 'Last name is required.';
        if ($dob     === '') $errors[] = 'Date of birth is required.';
        if ($address === '') $errors[] = 'Address is required.';
        if ($role    === '') $errors[] = 'Please select a role.';

        if ($email === '') {
            $errors[] = 'Email address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if ($phone === '' || !preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
            $errors[] = 'Please enter a valid phone number (digits only, 7-20 characters).';
        }

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        if ($password !== $confirm) {
            $errors[] = 'Passwords do not match.';
        }

        // Profile photo — ONLY validate if a file was actually chosen
        $photoFilename = '';
        $hasPhoto      = !empty($_FILES['profile_photo']['name']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK;

        if ($hasPhoto) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $mime    = mime_content_type($_FILES['profile_photo']['tmp_name']);
            if (!in_array($mime, $allowed)) {
                $errors[] = 'Profile photo must be JPG, PNG, GIF, or WEBP.';
            } elseif ($_FILES['profile_photo']['size'] > 5 * 1024 * 1024) {
                $errors[] = 'Profile photo must be under 5 MB.';
            } else {
                $ext           = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
                $photoFilename = uniqid('prof_', true) . '.' . $ext;
            }
        }

        if (!empty($errors)) {
            $_SESSION['signup_errors'] = $errors;
            $_SESSION['signup_old']    = compact('fname', 'lname', 'email', 'phone', 'dob', 'address', 'role');
            header('Location: ' . BASE_URL . 'index.php?action=signup');
            exit;
        }

        try {
            $pdo = db();

            // Check for duplicate email
            $chk = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
            $chk->execute([$email]);
            if ($chk->fetch()) {
                $_SESSION['signup_errors'] = ['An account with that email already exists. Please login instead.'];
                $_SESSION['signup_old']    = compact('fname', 'lname', 'email', 'phone', 'dob', 'address', 'role');
                header('Location: ' . BASE_URL . 'index.php?action=signup');
                exit;
            }

            $hash = password_hash($password, PASSWORD_BCRYPT);

            $ins = $pdo->prepare('
                INSERT INTO users
                    (first_name, last_name, email, phone, dob, address, password_hash, role, profile_photo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ');
            $ins->execute([$fname, $lname, $email, $phone, $dob, $address, $hash, $role, $photoFilename]);

            // Move photo ONLY if one was uploaded and DB insert succeeded
            if ($hasPhoto && $photoFilename !== '') {
                // Make sure upload dirs exist
                $profileDir = UPLOAD_PATH . 'profiles';
                if (!is_dir($profileDir)) {
                    mkdir($profileDir, 0755, true);
                }
                move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profileDir . '/' . $photoFilename);
            }

            $_SESSION['signup_success'] = true;
            header('Location: ' . BASE_URL . 'index.php?action=login');
            exit;

        } catch (PDOException $e) {
            // Show the real DB error so you can diagnose it
            $_SESSION['signup_errors'] = ['Database error: ' . $e->getMessage()];
            $_SESSION['signup_old']    = compact('fname', 'lname', 'email', 'phone', 'dob', 'address', 'role');
            header('Location: ' . BASE_URL . 'index.php?action=signup');
            exit;
        }
    }

    /* ------------------------------------------------------------------ */
    public function logout()
    {
        session_destroy();
        header('Location: ' . BASE_URL . 'index.php?action=home');
        exit;
    }
}
