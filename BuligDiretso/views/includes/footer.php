<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/footer.css'; ?>">
<footer class="footer" id="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-title">
                   <a href="<?php echo BASE_URL; ?>index.php?action=dashboard" class="header-logo">
                        <img src="<?php echo ASSETS_PATH . 'images/logo.png'; ?>" alt="Logo" class="logo">
                        <span>BuligDiretso.</span>
                    </a>
                </div>

                <div class="footer-desc">
                    <p>Providing rapid emergency assistance and life-saving support to communities.</p>
                </div>
            </div>
            <div class="footer-section">
                <div class="footer-title">
                    <h2>Platform</h2>
                </div>
                <ul class="footer-links">
                    <?php foreach ($footerLinks as $link): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>index.php?action=<?php echo $link['action']; ?>">
                                <?php echo $link['label']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="footer-section">
                <div class="footer-title">
                    <h2>Resources</h2>
                </div>
                <ul class="footer-links"> 
                    <?php foreach ($footerResources as $link): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>index.php?action=<?php echo $link['href']; ?>">
                                <?php echo $link['label']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="footer-section">
                <div class="footer-title">
                    <h2>Emergency Hotline</h2>
                </div>
                <ul class="footer-links">
                     <?php foreach ($footerHotline as $link): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>index.php?action=<?php echo $link['href']; ?>">
                                <?php echo $link['label']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
                
        </div>
        <div class="footer-bottom">
            <div class="footer-bottom-section">
                <p>© 2026 Emergency Response System. All rights reserved</p>
            </div>
    
            <div class="footer-bottom-section policies">
               
                    <p>Privacy</p> 
                    <p>Terms of Service</p>
                    <p>Contact</p>
                
            </div>
        </div>
    </div>
</footer>
       
       
  