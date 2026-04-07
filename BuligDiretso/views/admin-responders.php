<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/admin-responders.css'; ?>">

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

            <div class="search-section">
                <div class="search-box">
                    <i class="ri-search-line"></i>
                    <input type="text" placeholder="Search Responder...">
                </div>
            </div>

            <div class="filter-buttons">
                <button class="filter-btn active">All</button>
                <button class="filter-btn">Active</button>
                <button class="filter-btn">Responding</button>
                <button class="filter-btn">Offline</button>
            </div>

            <button class="add-btn">
                <i class="ri-add-line"></i> Add
            </button>

            <div class="responders-table">
                <table>
                    <thead>
                        <tr>
                            <th>RESPONDER</th>
                            <th>LOCATION</th>
                            <th>ASSIGNMENT</th>
                            <th>PERFORMANCE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="responder-info">
                                    <div class="avatar">JS</div>
                                    <div>
                                        <div class="name">John Santoso</div>
                                        <div class="status status-active">Active</div>
                                    </div>
                                </div>
                            </td>
                            <td>Brgy. Balud</td>
                            <td><span class="assignment-link">ER-A7K3K</span></td>
                            <td><span class="rating"><i class="ri-star-s-line"></i> 4.5 (82)</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="icon-btn" title="View"><i class="ri-eye-line"></i></button>
                                    <button class="icon-btn" title="Edit"><i class="ri-edit-line"></i></button>
                                    <button class="icon-btn" title="Delete"><i class="ri-delete-bin-line"></i></button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="responder-info">
                                    <div class="avatar">MR</div>
                                    <div>
                                        <div class="name">Mario Reyes</div>
                                        <div class="status status-responding">Responding</div>
                                    </div>
                                </div>
                            </td>
                            <td>Brgy. Balud</td>
                            <td><span class="assignment-link">ER-C8NDP</span></td>
                            <td><span class="rating"><i class="ri-star-s-line"></i> 4.6 (203)</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="icon-btn" title="View"><i class="ri-eye-line"></i></button>
                                    <button class="icon-btn" title="Edit"><i class="ri-edit-line"></i></button>
                                    <button class="icon-btn" title="Delete"><i class="ri-delete-bin-line"></i></button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="responder-info">
                                    <div class="avatar">DC</div>
                                    <div>
                                        <div class="name">David Cruz</div>
                                        <div class="status status-active">Active</div>
                                    </div>
                                </div>
                            </td>
                            <td>Brgy. Balud</td>
                            <td>None</td>
                            <td><span class="rating"><i class="ri-star-s-line"></i> 5.0 (60)</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="icon-btn" title="View"><i class="ri-eye-line"></i></button>
                                    <button class="icon-btn" title="Edit"><i class="ri-edit-line"></i></button>
                                    <button class="icon-btn" title="Delete"><i class="ri-delete-bin-line"></i></button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="responder-info">
                                    <div class="avatar">SL</div>
                                    <div>
                                        <div class="name">Sarah Lim</div>
                                        <div class="status status-offline">Offline</div>
                                    </div>
                                </div>
                            </td>
                            <td>Brgy. Balud</td>
                            <td>None</td>
                            <td><span class="rating"><i class="ri-star-s-line"></i> 4.7 (42)</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="icon-btn" title="View"><i class="ri-eye-line"></i></button>
                                    <button class="icon-btn" title="Edit"><i class="ri-edit-line"></i></button>
                                    <button class="icon-btn" title="Delete"><i class="ri-delete-bin-line"></i></button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="responder-info">
                                    <div class="avatar">MT</div>
                                    <div>
                                        <div class="name">Mike Tan</div>
                                        <div class="status status-active">Active</div>
                                    </div>
                                </div>
                            </td>
                            <td>Brgy. Balud</td>
                            <td>None</td>
                            <td><span class="rating"><i class="ri-star-s-line"></i> 4.3 (79)</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="icon-btn" title="View"><i class="ri-eye-line"></i></button>
                                    <button class="icon-btn" title="Edit"><i class="ri-edit-line"></i></button>
                                    <button class="icon-btn" title="Delete"><i class="ri-delete-bin-line"></i></button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="responder-info">
                                    <div class="avatar">JG</div>
                                    <div>
                                        <div class="name">John Santos</div>
                                        <div class="status status-active">Active</div>
                                    </div>
                                </div>
                            </td>
                            <td>Brgy. Balud</td>
                            <td><span class="assignment-link">ER-A7K3K</span></td>
                            <td><span class="rating"><i class="ri-star-s-line"></i> 4.8 (67)</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="icon-btn" title="View"><i class="ri-eye-line"></i></button>
                                    <button class="icon-btn" title="Edit"><i class="ri-edit-line"></i></button>
                                    <button class="icon-btn" title="Delete"><i class="ri-delete-bin-line"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php require_once VIEW_PATH . 'includes/footer.php'; ?>
<script src="<?php echo ASSETS_PATH . 'js/admin-responders.js'; ?>"></script>