<?php require_once VIEW_PATH . 'includes/header.php';?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/dashboard.css'; ?>">


<div class="page-wrapper">
    
    <!-- Top Bar -->
    <div class="top-bar">
         <!-- Red Emergency Banner -->
        
             <div class="banner-text">
                <i class="ri-error-warning-line"></i> 
                <h2>Emergency Response System</h2>
             </div>
             
             <div class="banner-icon">
                <i class="ri-group-fill"></i>
                <i class="ri-pulse-line"></i>
             </div>
         
    </div>

     <!-- Main Content -->
    <div class="content-box">
        
        <!-- Red Emergency Banner -->
        <div class="red-banner">
            <div class="red-banner-text">
                <h2>Emergency Response</h2>
                <p>Quick access to help when you need it most</p>
            </div>
            <div class="red-banner-icon">
                <i class="ri-error-warning-line"></i>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="card-list">
            
            <!-- Report Emergency -->
            <a href="index.php?action=report-system" class="card">
                <div class="card-circle red">
                    <i class="ri-phone-fill"></i>
                </div>
                <div class="card-info"> 
                    <h3>Report Emergency</h3>
                    <p>For immediate help</p>
                </div>
            </a>

            <!-- Emergency Dashboard -->
            <a href="index.php?action=emergency-dashboard" class="card">
                <div class="card-circle purple">
                    <i class="ri-pulse-line"></i>
                </div>
                <div class="card-info">
                    <h3>Emergency Dashboard</h3>
                    <p>View all emergencies</p>
                </div>
            </a>

            <!-- Track Rescue -->
            <a href="index.php?action=emergency-tracking" class="card">
                <div class="card-circle blue">
                    <i class="ri-send-plane-2-line"></i>
                </div>
                <div class="card-info">
                    <h3>Track Rescue</h3>
                    <p>24hr response status</p>
                </div>
            </a>

            <!-- Safety Guides -->
            <a href="index.php?action=safety-guides" class="card">
                <div class="card-circle green">
                    <i class="ri-book-open-line"></i>
                </div>
                <div class="card-info">
                    <h3>First Aid & Safety Guides</h3>
                    <p>Learn emergency care procedures</p>
                </div>
            </a>

        </div>

        <div class="hotline-box">
            <span class="hotline-icon">
                <i class="ri-error-warning-line"></i>
            </span>
            <div class="hotline-text">
                <strong>Emergency Hotline</strong>
                <p>Available 24/7 for immediate support</p>
            </div>
        </div>

    </div>



</div>

<?php include 'views/includes/footer.php'; ?>






