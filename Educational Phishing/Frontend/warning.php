<?php
include 'includes/languages.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>" dir="<?php echo $pageDir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php _e('warning_title'); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .language-switcher {
            position: fixed;
            top: 20px;
            <?php echo $pageAlign; ?>: 20px;
            z-index: 100;
        }
        
        .lang-button {
            padding: 8px 16px;
            border: 2px solid white;
            background: transparent;
            color: white;
            cursor: pointer;
            border-radius: 6px;
            font-weight: bold;
            transition: all 0.3s;
            margin-left: 10px;
        }
        
        .lang-button:hover {
            background: white;
            color: #667eea;
        }
        
        .lang-button.active {
            background: white;
            color: #667eea;
        }
        
        .warning-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
            text-align: center;
        }
        
        .warning-icon {
            font-size: 60px;
            margin-bottom: 20px;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        h1 {
            color: #d32f2f;
            margin-bottom: 20px;
            font-size: 28px;
        }
        
        .warning-text {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            text-align: <?php echo $pageAlign; ?>;
            border-radius: 4px;
        }
        
        .warning-text p {
            color: #333;
            line-height: 1.6;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .info-section {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: <?php echo $pageAlign; ?>;
        }
        
        .info-section h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .info-section ul {
            list-style: none;
            text-align: <?php echo $pageAlign; ?>;
        }
        
        .info-section li {
            padding: 8px 0;
            color: #666;
            font-size: 14px;
            border-bottom: 1px solid #ddd;
        }
        
        .info-section li:last-child {
            border-bottom: none;
        }
        
        .info-section li:before {
            content: "✓ ";
            color: #4caf50;
            font-weight: bold;
            margin-<?php echo ($pageAlign === 'right') ? 'right' : 'left'; ?>: 10px;
        }
        
        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        button {
            padding: 12px 30px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
        }
        
        .btn-danger {
            background: #d32f2f;
            color: white;
            flex: 1;
            min-width: 200px;
        }
        
        .btn-danger:hover {
            background: #b71c1c;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
        }
        
        .btn-success {
            background: #4caf50;
            color: white;
            flex: 1;
            min-width: 200px;
        }
        
        .btn-success:hover {
            background: #388e3c;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }
        
        .legal-disclaimer {
            background: #ffe0e0;
            border: 2px solid #d32f2f;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 12px;
            color: #333;
            text-align: <?php echo $pageAlign; ?>;
        }
        
        .legal-disclaimer strong {
            color: #d32f2f;
        }
        
        .features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
            text-align: <?php echo $pageAlign; ?>;
        }
        
        .feature-box {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 6px;
            border-<?php echo $pageAlign; ?>: 4px solid #4caf50;
        }
        
        .feature-box h4 {
            color: #2e7d32;
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .feature-box p {
            font-size: 12px;
            color: #555;
            line-height: 1.5;
        }
        
        @media (max-width: 600px) {
            .warning-container {
                padding: 20px;
            }
            
            h1 {
                font-size: 22px;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
            
            .buttons {
                flex-direction: column;
            }
            
            button {
                min-width: 100%;
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
    
    <div class="warning-container">
        <div class="warning-icon">⚠️</div>
        
        <h1><?php _e('warning_title'); ?></h1>
        
        <div class="warning-text">
            <p><strong><?php _e('warning_subtitle'); ?></strong></p>
            <p><?php _e('warning_description'); ?></p>
        </div>
        
        <div class="info-section">
            <h3><?php _e('what_will_learn'); ?></h3>
            <ul>
                <li><?php _e('how_to_detect_phishing'); ?></li>
                <li><?php _e('common_vulnerabilities'); ?></li>
                <li><?php _e('practical_protection'); ?></li>
                <li><?php _e('best_security_practices'); ?></li>
            </ul>
        </div>
        
        <div class="features">
            <div class="feature-box">
                <h4>🔴 <?php _e('demo_part'); ?></h4>
                <p><?php _e('demo_desc'); ?></p>
            </div>
            <div class="feature-box">
                <h4>🟢 <?php _e('defense_part'); ?></h4>
                <p><?php _e('defense_desc'); ?></p>
            </div>
        </div>
        
        <div class="info-section">
            <h3><?php _e('legal_warning'); ?></h3>
            <ul>
                <li><?php _e('legal_text'); ?></li>
                <li><?php _e('real_attacks'); ?></li>
                <li><?php _e('educational_use'); ?></li>
                <li><?php _e('full_responsibility'); ?></li>
            </ul>
        </div>
        
        <div class="buttons">
            <button class="btn-danger" onclick="goBack()"><?php _e('disagree'); ?></button>
            <button class="btn-success" onclick="continuDemo()"><?php _e('agree'); ?></button>
        </div>
        
        <div class="legal-disclaimer">
            <strong><?php _e('disclaimer'); ?></strong>
        </div>
    </div>
    
    <script>
        function goBack() {
            if (document.referrer) {
                window.history.back();
            } else {
                window.location.href = 'https://www.google.com';
            }
        }
        
        function continuDemo() {
            sessionStorage.setItem('phishing_demo_consent', 'true');
            
            console.log('%c⚠️ <?php echo ($currentLang === 'ar') ? 'تحذير:' : 'Warning:'; ?>', 'color: red; font-size: 16px; font-weight: bold;');
            console.log('%c<?php echo ($currentLang === 'ar') ? 'هذا موقع توعوي فقط. لا تستخدم هذا الكود في أغراض احتيالية.' : 'This is an educational site only. Do not use this code for fraudulent purposes.'; ?>', 'color: orange; font-size: 12px;');
            
            window.location.href = 'index.php';
        }
        
        window.addEventListener('load', function() {
            const hasConsent = sessionStorage.getItem('phishing_demo_consent');
            if (hasConsent === 'true') {
                // User has already seen the warning
            }
        });
    </script>
</body>
</html>
