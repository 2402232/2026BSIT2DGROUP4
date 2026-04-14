<?php
// submit_emergency.php - Handle emergency report submission

require_once __DIR__ . '/config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "index.php?action=login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "index.php?action=report-system");
    exit();
}

$user_id = $_SESSION['user_id'];
$emergency_type = $_POST['emergency_type'] ?? '';
$severity = $_POST['severity'] ?? 'moderate';
$description = $_POST['additional_details'] ?? '';
$location = $_POST['address'] ?? 'Unknown';

// Generate report code
$report_code = 'ER-' . strtoupper(substr(md5(uniqid()), 0, 6));

try {
    $pdo = db();

    // Insert emergency report
    $stmt = $pdo->prepare("
        INSERT INTO emergency_reports 
        (report_code, user_id, emergency_type, severity, status, description, location, created_at, updated_at) 
        VALUES (?, ?, ?, ?, 'pending', ?, ?, NOW(), NOW())
    ");

    $stmt->execute([
        $report_code,
        $user_id,
        $db_type,
        $severity,
        $description,
        $location
    ]);

    $report_id = $pdo->lastInsertId();

    // Handle file uploads
    if (!empty($_FILES['file_uploads']['name'][0])) {
        $upload_dir = __DIR__ . '/uploads/emergency_reports/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        foreach ($_FILES['file_uploads']['tmp_name'] as $key => $tmp_name) {
            if (!empty($tmp_name)) {
                $file_name = $_FILES['file_uploads']['name'][$key];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov'];

                if (in_array($file_ext, $allowed_exts)) {
                    $new_file_name = $report_code . '_' . $key . '.' . $file_ext;
                    $file_path = $upload_dir . $new_file_name;

                    if (move_uploaded_file($tmp_name, $file_path)) {
                        // In a real app, you'd store file paths in a separate table
                        // For now, just log the upload
                        error_log("Uploaded file: " . $file_path);
                    }
                }
            }
        }
    }

    $_SESSION['success'] = "Emergency report submitted successfully! Report code: " . $report_code;
    header("Location: " . BASE_URL . "index.php?action=emergency-tracking");
    exit();

} catch (Exception $e) {
    error_log("Emergency submission error: " . $e->getMessage());
    $_SESSION['error'] = "Failed to submit emergency report. Please try again.";
    header("Location: " . BASE_URL . "index.php?action=report-system");
    exit();
}
?>