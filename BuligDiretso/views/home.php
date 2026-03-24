<body>
    <?php  require_once VIEW_PATH . 'includes/header.php';?>
    <div class="hero-container" id="getStarted">
        <div class="warning-alarm">
            <i class="ri-alarm-warning-fill"></i>
            <p>24/7  Emergency Support available</p>
        </div>

            <div class="hero-content">
                <h1>Help  is Just a <span>Click Away</span></h1>
                <p>Connect with Emergency responders instantly. Real-time tracking, automated <br>
                location detection and comprehensive first aid guides all in one platform.</p>
            </div>
                <div class="hero-buttons">
                    <a href="#" class="cta-button report" id="reportBtn">Report Emergency</a>
                    <a href="index.php?action=login" class="cta-button learn-more">Get Started</a>
                </div>

                <div class="hero-active">
                    <div class="free-access">
                        <i class="ri-check-line"></i>
                        <p>Instant Response</p>
                    </div>
                    <div class="free-access">
                        <i class="ri-check-line"></i>
                        <p>Live Tracking</p>
                    </div>
                    <div class="free-access">
                        <i class="ri-check-line"></i>
                        <p>Free Access</p>
                    </div>

        
                </div>
    </div>

        <!-- STATS -->
        <section class="stats">

            <div class="stat-box">
                <h2>24/7</h2>
                <p>Available Support</p>
            </div>

            <div class="stat-box">
                <h2>3 min</h2>
                <p>Avg Response</p>
            </div>

            <div class="stat-box">
                <h2>10K+</h2>
                <p>Lives Helped</p>
            </div>

            <div class="stat-box">
                <h2>98%</h2>
                <p>Success Rate</p>
            </div>

        </section>


        <!-- FEATURES -->
        <section class="features" id="features">

            <h2 class="title">Our Features</h2>

            <div class="feature-grid">
                <?php foreach ($features as $feature): ?>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="<?php echo $feature['icon']; ?>"></i>
                        </div>
                        <h3 class="feature-title"><?php echo $feature['title']; ?></h3>
                        <p class="feature-desc"><?php echo $feature['desc']; ?></p>
                        <div class="feature-link">
                            <a href="#">
                                <?php echo $feature['link']; ?>
                                <i class="<?php echo $feature['arrow']; ?>"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </section>


        <!-- HOW IT WORKS -->
        <section class="steps" id="howItWorks">

            <h2 class="title"> How It Works</h2>

            <div class="step-grid">
                <?php foreach ($how_it_works as $hiw): ?>
                    <div class="step">
                       <span class="step-number"> <?php echo $hiw['number']; ?></span>
                        <h3 class="step-title"><?php echo $hiw['title']; ?></h3>
                        <p class="step-desc"><?php echo $hiw['desc']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

        </section>


        <!-- MODAL -->
        <div class="modal" id="modal">

            <div class="modal-content">

                <h3>Emergency Report</h3>

                <p id="locationText">Detecting location...</p>

                <p>Status: <strong>Sending alert...</strong></p>

                <button id="closeModal">Close</button>

            </div>

        </div>

        <?php  require_once VIEW_PATH . 'includes/footer.php';?>    
        <!-- JS -->
        <script src="<?php echo ASSETS_PATH . 'js/home.js'; ?>"></script>

</body>

