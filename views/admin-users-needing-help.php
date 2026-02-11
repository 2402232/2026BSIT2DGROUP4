<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/admin-users-needing-help.css'; ?>">

<?php require_once VIEW_PATH . 'includes/header.php'; ?>

<div class="admin-wrapper">

 <!-- Top Bar -->
    <div class="top-bar">
         <!-- Red Emergency Banner -->
        
             <div class="banner">
                <i class="ri-shield-line"></i>
                <div class="banner-text">
                    <h2>Admin Dashboard</h2>
                    <p>Emergency Response System</p>
                </div>
             </div>
    </div>


        <div class="dashboard-card">

            <div class="filter-buttons">
                <button class="filter-btn active">All Emergencies</button>
                <button class="filter-btn">Critical</button>
                <button class="filter-btn">Pending Assignment</button>
                <button class="filter-btn">Responding</button>
            </div>

            <div class="emergency-list">
                                <div class="emergency-card">
                    <div class="card-header">
                        <div class="emergency-id">
                            <span class="badge badge-critical">CRITICAL</span>
                            <span class="badge badge-pending">PENDING</span>
                            <h3>ER-A7ZX</h3>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-user-line"></i>
                                <div>
                                    <span class="label">User Information</span>
                                    <span class="value">Juan Dela Cruz</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-time-line"></i>
                                <div>
                                    <span class="label">Created On</span>
                                    <span class="value">12:34:05 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-phone-line"></i>
                                <div>
                                    <span class="label">Contact Number</span>
                                    <span class="value">+63 912 345 6789</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-calendar-line"></i>
                                <div>
                                    <span class="label">Updated On</span>
                                    <span class="value">12:34:12 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-map-pin-line"></i>
                                <div>
                                    <span class="label">Location</span>
                                    <span class="value">123 Main St, Iloilo City</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-alert-line"></i>
                                <div>
                                    <span class="label">Emergency Type</span>
                                    <span class="value">Medical Emergency</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-file-text-line"></i>
                                <div>
                                    <span class="label">Description</span>
                                    <span class="value">Person having difficulty breathing</span>
                                </div>
                            </div>
                        </div>

                        <div class="responder-section">
                            <span class="responder-label">Responder Assigned:</span>
                            <span class="no-responder">No responder assigned yet</span>
                        </div>
                    </div>

                    <div class="card-actions">
                        <button class="btn btn-assign">Assign Responders</button>
                        <button class="btn btn-view">View User Details</button>
                    </div>
                </div>

                <!-- Emergency Card 2 -->
                <div class="emergency-card">
                    <div class="card-header">
                        <div class="emergency-id">
                            <span class="badge badge-medium">MEDIUM</span>
                            <span class="badge badge-pending">PENDING</span>
                            <h3>ER-C3NDP</h3>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-user-line"></i>
                                <div>
                                    <span class="label">User Information</span>
                                    <span class="value">Maria Santos</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-time-line"></i>
                                <div>
                                    <span class="label">Created On</span>
                                    <span class="value">12:45:22 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-phone-line"></i>
                                <div>
                                    <span class="label">Contact Number</span>
                                    <span class="value">+63 923 456 7890</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-calendar-line"></i>
                                <div>
                                    <span class="label">Updated On</span>
                                    <span class="value">12:45:30 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-map-pin-line"></i>
                                <div>
                                    <span class="label">Location</span>
                                    <span class="value">456 Oak Avenue, Mandurriao</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-alert-line"></i>
                                <div>
                                    <span class="label">Emergency Type</span>
                                    <span class="value">Fire Emergency</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-file-text-line"></i>
                                <div>
                                    <span class="label">Description</span>
                                    <span class="value">Small kitchen fire spreading quickly</span>
                                </div>
                            </div>
                        </div>

                        <div class="responder-section">
                            <span class="responder-label">Responder Assigned:</span>
                            <span class="no-responder">No responder assigned yet</span>
                        </div>
                    </div>

                    <div class="card-actions">
                        <button class="btn btn-assign">Assign Responders</button>
                        <button class="btn btn-view">View User Details</button>
                    </div>
                </div>

                <!-- Emergency Card 3 -->
                <div class="emergency-card">
                    <div class="card-header">
                        <div class="emergency-id">
                            <span class="badge badge-low">LOW</span>
                            <span class="badge badge-pending">PENDING</span>
                            <h3>ER-J7WBY</h3>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-user-line"></i>
                                <div>
                                    <span class="label">User Information</span>
                                    <span class="value">Pedro Garcia</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-time-line"></i>
                                <div>
                                    <span class="label">Created On</span>
                                    <span class="value">01:02:15 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-phone-line"></i>
                                <div>
                                    <span class="label">Contact Number</span>
                                    <span class="value">+63 934 567 8901</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-calendar-line"></i>
                                <div>
                                    <span class="label">Updated On</span>
                                    <span class="value">01:02:18 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-map-pin-line"></i>
                                <div>
                                    <span class="label">Location</span>
                                    <span class="value">789 Pine Street, Jaro District</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-alert-line"></i>
                                <div>
                                    <span class="label">Emergency Type</span>
                                    <span class="value">Police Assistance</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-file-text-line"></i>
                                <div>
                                    <span class="label">Description</span>
                                    <span class="value">Suspicious activity reported in the area</span>
                                </div>
                            </div>
                        </div>

                        <div class="responder-section">
                            <span class="responder-label">Responder Assigned:</span>
                            <span class="no-responder">No responder assigned yet</span>
                        </div>
                    </div>

                    <div class="card-actions">
                        <button class="btn btn-assign">Assign Responders</button>
                        <button class="btn btn-view">View User Details</button>
                    </div>
                </div>

                <!-- Emergency Card 4 -->
                <div class="emergency-card">
                    <div class="card-header">
                        <div class="emergency-id">
                            <span class="badge badge-critical">CRITICAL</span>
                            <span class="badge badge-pending">PENDING</span>
                            <h3>ER-D7T2S</h3>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-user-line"></i>
                                <div>
                                    <span class="label">User Information</span>
                                    <span class="value">Ana Reyes</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-time-line"></i>
                                <div>
                                    <span class="label">Created On</span>
                                    <span class="value">01:15:45 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item">
                                <i class="ri-phone-line"></i>
                                <div>
                                    <span class="label">Contact Number</span>
                                    <span class="value">+63 945 678 9012</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="ri-calendar-line"></i>
                                <div>
                                    <span class="label">Updated On</span>
                                    <span class="value">01:15:52 AM</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-map-pin-line"></i>
                                <div>
                                    <span class="label">Location</span>
                                    <span class="value">321 Elm Road, Molo District</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-alert-line"></i>
                                <div>
                                    <span class="label">Emergency Type</span>
                                    <span class="value">Vehicle Accident</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item full-width">
                                <i class="ri-file-text-line"></i>
                                <div>
                                    <span class="label">Description</span>
                                    <span class="value">Multi-vehicle collision with injuries</span>
                                </div>
                            </div>
                        </div>

                        <div class="responder-section">
                            <span class="responder-label">Responder Assigned:</span>
                            <span class="no-responder">No responder assigned yet</span>
                        </div>
                    </div>

                    <div class="card-actions">
                        <button class="btn btn-assign">Assign Responders</button>
                        <button class="btn btn-view">View User Details</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
<script src="<?php echo ASSETS_PATH . 'js/admin-users-needing-help.js'; ?>"></script>