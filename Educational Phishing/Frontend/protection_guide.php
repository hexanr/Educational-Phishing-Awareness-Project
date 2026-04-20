<?php
include 'includes/languages.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>" dir="<?php echo $pageDir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php _e('protection_guide'); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            color: #333;
        }
        
        .language-switcher {
            position: fixed;
            top: 20px;
            <?php echo $pageAlign; ?>: 20px;
            z-index: 100;
        }
        
        .lang-button {
            padding: 8px 16px;
            border: 2px solid #667eea;
            background: white;
            color: #667eea;
            cursor: pointer;
            border-radius: 6px;
            font-weight: bold;
            transition: all 0.3s;
            margin-left: 10px;
        }
        
        .lang-button:hover {
            background: #667eea;
            color: white;
        }
        
        .lang-button.active {
            background: #667eea;
            color: white;
        }
        
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: <?php echo $pageAlign; ?>;
        }
        
        .section h2 {
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
            font-size: 24px;
        }
        
        .section h3 {
            color: #333;
            margin-top: 20px;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        
        .bad, .good {
            padding: 20px;
            border-radius: 8px;
            border-<?php echo $pageAlign; ?>: 5px solid;
        }
        
        .bad {
            background: #ffebee;
            border-<?php echo $pageAlign; ?>-color: #d32f2f;
        }
        
        .bad h4 {
            color: #d32f2f;
            margin-bottom: 10px;
        }
        
        .good {
            background: #e8f5e9;
            border-<?php echo $pageAlign; ?>-color: #4caf50;
        }
        
        .good h4 {
            color: #4caf50;
            margin-bottom: 10px;
        }
        
        .code {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
            margin-top: 10px;
            direction: ltr;
            text-align: left;
        }
        
        .warning-banner {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .warning-banner strong {
            color: #856404;
        }
        
        .protection-list {
            list-style: none;
            padding-<?php echo $pageAlign; ?>: 0;
        }
        
        .protection-list li {
            padding: 10px 0;
            padding-<?php echo $pageAlign; ?>: 30px;
            border-bottom: 1px solid #eee;
            position: relative;
        }
        
        .protection-list li:before {
            content: "✓";
            color: #4caf50;
            font-weight: bold;
            position: absolute;
            <?php echo $pageAlign; ?>: 0;
        }
        
        .table-comparison {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .table-comparison th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: <?php echo $pageAlign; ?>;
            border: 1px solid #ddd;
        }
        
        .table-comparison td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: <?php echo $pageAlign; ?>;
        }
        
        .table-comparison tr:nth-child(even) {
            background: #f5f5f5;
        }
        
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            border-bottom: 2px solid #eee;
        }
        
        .tab-button {
            padding: 12px 20px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .tab-button.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        
        .tab-button:hover {
            color: #667eea;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .contacts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .contact-card {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            border-<?php echo $pageAlign; ?>: 4px solid #667eea;
            text-align: <?php echo $pageAlign; ?>;
        }
        
        .contact-card h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .contact-card p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        
        .contact-card .type {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .contact-card a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        
        .contact-card a:hover {
            text-decoration: underline;
        }
        
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
        
        @media (max-width: 768px) {
            .comparison {
                grid-template-columns: 1fr;
            }
            
            header h1 {
                font-size: 24px;
            }
            
            .code {
                font-size: 11px;
            }
            
            .contacts-grid {
                grid-template-columns: 1fr;
            }
            
            .language-switcher {
                top: 10px;
                <?php echo $pageAlign; ?>: 10px;
            }
            
            .lang-button {
                padding: 6px 12px;
                font-size: 12px;
                margin-left: 5px;
            }
        }
    </style>
</head>
<body>
    <!-- Language Switcher -->
    <div class="language-switcher">
        <a href="?lang=ar">
            <button class="lang-button <?php echo ($currentLang === 'ar') ? 'active' : ''; ?>">
                العربية
            </button>
        </a>
        <a href="?lang=en">
            <button class="lang-button <?php echo ($currentLang === 'en') ? 'active' : ''; ?>">
                English
            </button>
        </a>
    </div>
    
    <header>
        <h1>🛡️ <?php _e('protection_guide'); ?></h1>
        <p><?php _e('learn_protect_yourself'); ?></p>
    </header>
    
    <div class="container">
        
        <div class="warning-banner">
            <strong>⚠️ <?php _e('remember_educational_content'); ?></strong>
        </div>
        
        <!-- ==================== الجزء 1 ==================== -->
        <div class="section">
            <h2>🔍 <?php _e('identifying_phishing'); ?></h2>
            
            <p><?php _e('phishing_mimics'); ?></p>
            
            <table class="table-comparison">
                <thead>
                    <tr>
                        <th><?php _e('sign'); ?></th>
                        <th><?php _e('fake_site'); ?></th>
                        <th><?php _e('real_site'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong><?php _e('url'); ?></strong></td>
                        <td><strong>https://gmail-secure.com</strong></td>
                        <td><strong>https://gmail.com</strong></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('ssl_certificate'); ?></strong></td>
                        <td><?php _e('no_lock'); ?></td>
                        <td><?php _e('green_lock'); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('design'); ?></strong></td>
                        <td><?php _e('simple_old'); ?></td>
                        <td><?php _e('professional_modern'); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('email'); ?></strong></td>
                        <td><?php _e('strange_address'); ?></td>
                        <td><?php _e('official_address'); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('style'); ?></strong></td>
                        <td><?php _e('spelling_errors'); ?></td>
                        <td><?php _e('professional_error_free'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- ==================== الجزء 2 ==================== -->
        <div class="section">
            <h2>⚙️ <?php _e('common_vulnerabilities'); ?></h2>
            
            <h3>1. <?php _e('not_encrypting_passwords'); ?></h3>
            <div class="comparison">
                <div class="bad">
                    <h4><?php _e('error_no_encryption'); ?></h4>
                    <div class="code">
INSERT INTO users (username, password)
VALUES ('ahmed', '12345678')

-- <?php _e('password_visible'); ?>
                    </div>
                </div>
                <div class="good">
                    <h4><?php _e('correct_with_encryption'); ?></h4>
                    <div class="code">
$hashed = password_hash(
  '12345678',
  PASSWORD_BCRYPT
);
INSERT INTO users (username, password)
VALUES ('ahmed', '$2y$12$...')
                    </div>
                </div>
            </div>
            
            <h3>2. <?php _e('sql_injection_vulnerability'); ?></h3>
            <div class="comparison">
                <div class="bad">
                    <h4><?php _e('error_vulnerable'); ?></h4>
                    <div class="code">
$username = $_POST['username'];
$query = "SELECT * FROM users
  WHERE username = '$username'";
                    </div>
                </div>
                <div class="good">
                    <h4><?php _e('correct_prepared_statements'); ?></h4>
                    <div class="code">
$stmt = $conn->prepare(
  "SELECT * FROM users WHERE username = ?"
);
$stmt->bind_param("s", $_POST['username']);
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ==================== الجزء 3 ==================== -->
        <div class="section">
            <h2>🛡️ <?php _e('practical_protection'); ?></h2>
            
            <h3><?php _e('before_login'); ?></h3>
            <ul class="protection-list">
                <li><?php _e('carefully_check_url'); ?></li>
                <li><?php _e('look_green_lock_https'); ?></li>
                <li><?php _e('verify_sender_email'); ?></li>
                <li><?php _e('dont_click_links'); ?></li>
                <li><?php _e('check_grammar'); ?></li>
                <li><?php _e('verify_trust_signs'); ?></li>
            </ul>
            
            <h3><?php _e('protect_your_account'); ?></h3>
            <ul class="protection-list">
                <li><?php _e('strong_passwords'); ?></li>
                <li><?php _e('different_passwords'); ?></li>
                <li><?php _e('enable_2fa_all_accounts'); ?></li>
                <li><?php _e('use_password_manager'); ?></li>
                <li><?php _e('update_software'); ?></li>
                <li><?php _e('use_vpn_public_wifi'); ?></li>
                <li><?php _e('monitor_account_activity'); ?></li>
            </ul>
            
            <h3><?php _e('if_fell_victim'); ?></h3>
            <ol style="padding-<?php echo $pageAlign; ?>: 20px;">
                <li><?php _e('change_password_immediately'); ?></li>
                <li><?php _e('enable_2fa_immediately'); ?></li>
                <li><?php _e('monitor_account'); ?></li>
                <li><?php _e('report_to_company'); ?></li>
                <li><?php _e('report_to_authorities'); ?></li>
                <li><?php _e('monitor_bank_transfers'); ?></li>
            </ol>
        </div>
        
        <!-- ==================== الجزء 4 ==================== -->
        <div class="section">
            <h2>📞 <?php _e('reporting_contacts'); ?></h2>
            
            <!-- Tabs -->
            <div class="tabs">
                <button class="tab-button active" onclick="switchTab(event, 'international')">🌍 <?php _e('international'); ?></button>
                <button class="tab-button" onclick="switchTab(event, 'major-platforms')">🏢 <?php _e('major_platforms'); ?></button>
                <button class="tab-button" onclick="switchTab(event, 'regional')">🗺️ <?php _e('regional'); ?></button>
            </div>
            
            <!-- International -->
            <div id="international" class="tab-content active">
                <div class="contacts-grid">
                    <div class="contact-card">
                        <span class="type">🌐 <?php _e('international'); ?></span>
                        <h4>Google Safe Browsing</h4>
                        <p><?php _e('report_harmful_sites'); ?></p>
                        <a href="https://safebrowsing.google.com/safebrowsing/report_phishing/" target="_blank">
                            report_phishing ↗️
                        </a>
                    </div>
                    
                    <div class="contact-card">
                        <span class="type">🌐 <?php _e('international'); ?></span>
                        <h4>Microsoft SmartScreen</h4>
                        <p><?php _e('report_edge_threats'); ?></p>
                        <a href="https://www.microsoft.com/en-us/wdsi/filesubmission" target="_blank">
                            Submit a file ↗️
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Major Platforms -->
            <div id="major-platforms" class="tab-content">
                <div class="contacts-grid">
                    <div class="contact-card">
                        <span class="type">📘 Facebook</span>
                        <h4>Facebook Phishing Report</h4>
                        <p><?php _e('how_to_detect_phishing'); ?></p>
                        <a href="mailto:report@facebook.com">
                            report@facebook.com ✉️
                        </a>
                    </div>
                    
                    <div class="contact-card">
                        <span class="type">📧 Gmail</span>
                        <h4>Gmail Phishing Report</h4>
                        <p><?php _e('if_fell_victim'); ?></p>
                        <a href="mailto:spam@google.com">
                            spam@google.com ✉️
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Regional -->
            <div id="regional" class="tab-content">
                <div class="contacts-grid">
                    <div class="contact-card">
                        <span class="type">🇪🇬 Egypt</span>
                        <h4>Cyber Police</h4>
                        <p><?php _e('report_to_authorities'); ?></p>
                    </div>
                    
                    <div class="contact-card">
                        <span class="type">🇺🇸 USA</span>
                        <h4>FBI IC3</h4>
                        <p><?php _e('report_harmful_sites'); ?></p>
                        <a href="https://www.ic3.gov/" target="_blank">
                            IC3 ↗️
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer>
        <p><?php _e('copyright'); ?></p>
    </footer>
    
    <script>
        function switchTab(evt, tabName) {
            var i, tabcontent, tabbuttons;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
            }
            tabbuttons = document.getElementsByClassName("tab-button");
            for (i = 0; i < tabbuttons.length; i++) {
                tabbuttons[i].classList.remove("active");
            }
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
    </script>
</body>
</html>
