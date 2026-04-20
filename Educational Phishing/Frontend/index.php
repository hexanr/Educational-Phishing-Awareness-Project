<?php
include 'includes/languages.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>" dir="<?php echo $pageDir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php _e('login_title'); ?> - <?php _e('warning_title'); ?></title>
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
            align-items: flex-start;
            padding: 20px;
            padding-top: 40px;
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
        
        .main-container {
            display: flex;
            gap: 20px;
            max-width: 1000px;
            width: 100%;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .login-container {
            width: 400px;
            background-color: #ffffff;
            border: 2px solid #ccc;
            padding: 20px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            text-align: center;
            position: relative;
        }
        
        .security-warning {
            background: #ffebee;
            border: 2px solid #d32f2f;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 15px;
            color: #d32f2f;
            font-size: 12px;
            text-align: <?php echo $pageAlign; ?>;
        }
        
        .security-warning strong {
            display: block;
            margin-bottom: 5px;
        }
        
        .header {
            background-color: #223a4e;
            color: #ffffff;
            padding: 15px;
            font-size: 18px;
            position: relative;
            width: 100%;
            text-align: center;
            margin: -20px -20px 15px -20px;
            border-radius: 6px 6px 0 0;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            margin: 15px 0;
            text-align: <?php echo $pageAlign; ?>;
        }
        
        label {
            color: #333;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            justify-content: flex-<?php echo ($pageAlign === 'right') ? 'end' : 'start'; ?>;
            margin-top: 10px;
            font-size: 12px;
        }
        
        .remember-me input {
            margin-<?php echo ($pageAlign === 'right') ? 'left' : 'right'; ?>: 5px;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        
        .remember-me label {
            margin: 0;
            font-weight: normal;
        }
        
        .login-button {
            width: 100%;
            background-color: #223a4e;
            color: #ffffff;
            padding: 12px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s;
        }
        
        .login-button:hover {
            background-color: #1a2e3b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .register {
            text-align: center;
            color: #667eea;
            font-size: 14px;
            cursor: pointer;
            margin-top: 15px;
            text-decoration: underline;
        }
        
        .register:hover {
            color: #764ba2;
        }
        
        .info-panel {
            width: 350px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            font-size: 13px;
            line-height: 1.6;
            color: #333;
            text-align: <?php echo $pageAlign; ?>;
        }
        
        .info-panel h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 16px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .info-panel h4 {
            color: #d32f2f;
            margin-top: 15px;
            margin-bottom: 8px;
            font-size: 13px;
        }
        
        .info-panel ul {
            list-style: none;
            padding-<?php echo $pageAlign; ?>: 15px;
        }
        
        .info-panel li {
            padding: 5px 0;
            padding-<?php echo $pageAlign; ?>: 20px;
            position: relative;
        }
        
        .info-panel li:before {
            content: "▶";
            color: #667eea;
            position: absolute;
            <?php echo $pageAlign; ?>: 0;
        }
        
        .cookie-banner {
            background-color: #3d5d56;
            color: #ffffff;
            padding: 10px;
            font-size: 12px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }
        
        .cookie-banner button {
            background-color: #667eea;
            border: none;
            padding: 6px 15px;
            font-size: 12px;
            cursor: pointer;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .cookie-banner button:hover {
            background-color: #764ba2;
        }
        
        .links {
            margin-top: 15px;
            text-align: center;
            font-size: 12px;
        }
        
        .links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 10px;
            cursor: pointer;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 900px) {
            .main-container {
                flex-direction: column;
                align-items: center;
            }
            
            .info-panel {
                width: 400px;
            }
        }
        
        @media (max-width: 500px) {
            body {
                padding-top: 120px;
            }
            
            .login-container,
            .info-panel {
                width: 100%;
                max-width: 350px;
            }
            
            .cookie-banner {
                flex-direction: column;
                gap: 10px;
                padding: 15px;
            }
            
            .language-switcher {
                top: 60px;
            }
        }
    </style>
    <script>
        function toggleLanguageDropdown() {
            const dropdown = document.getElementById('languageDropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }
        
        function validateForm(event) {
            event.preventDefault();
            
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            if (!username || !password) {
                alert('<?php _e('fill_all_fields'); ?>');
                return false;
            }
            
            if (username.length < 3 || username.length > 20) {
                alert('<?php _e('username_length'); ?>');
                return false;
            }
            
            if (password.length < 6) {
                alert('<?php _e('password_length'); ?>');
                return false;
            }
            
            document.getElementById('loginForm').submit();
        }
        
        window.addEventListener('load', function() {
            console.log('%c⚠️ <?php echo ($currentLang === 'ar') ? 'تحذير أمني:' : 'Security Warning:'; ?>', 'color: red; font-size: 16px; font-weight: bold;');
            console.log('%c<?php echo ($currentLang === 'ar') ? 'هذا موقع توعوي تعليمي فقط!' : 'This is an educational website only!'; ?>', 'color: orange; font-size: 12px;');
            console.log('%c<?php echo ($currentLang === 'ar') ? 'لا تدخل بيانات حقيقية في هذا الموقع' : 'Do not enter real data on this website'; ?>', 'color: red; font-size: 12px;');
        });
    </script>
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
    
    <div class="main-container">
        <!-- Login Form -->
        <div class="login-container">
            <div class="header">
                <?php _e('login_title'); ?>
            </div>
            
            <div class="security-warning">
                <strong>⚠️ <?php _e('warning_dont_enter_real_data'); ?></strong>
            </div>
            
            <form id="loginForm" onsubmit="validateForm(event)" method="POST" action="login_secure_clean.php">
                <input type="hidden" name="csrf_token" value="<?php echo bin2hex(random_bytes(32)); ?>">
                
                <div class="form-group">
                    <label for="username"><?php _e('username'); ?>:</label>
                    <input type="text" id="username" name="username" placeholder="<?php _e('enter_username'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><?php _e('password'); ?>:</label>
                    <input type="password" id="password" name="password" placeholder="<?php _e('enter_password'); ?>" required>
                </div>
                
                <div class="remember-me">
                    <label for="remember"><?php _e('remember_me'); ?></label>
                    <input type="checkbox" id="remember" name="remember">
                </div>
                
                <button type="submit" class="login-button"><?php _e('login'); ?></button>
            </form>
            
            <div class="register">
                <?php _e('create_account'); ?>
            </div>
            
            <div class="links">
                <a href="protection_guide.php"><?php _e('protection_guide_link'); ?></a>
                <a href="warning.php"><?php _e('warning_link'); ?></a>
            </div>
        </div>
        
        <!-- Info Panel -->
        <div class="info-panel">
            <h3>📚 <?php _e('educational_project'); ?></h3>
            
            <p><?php _e('project_description'); ?></p>
            
            <h4>🔴 <?php _e('demo_part'); ?>:</h4>
            <ul>
                <li><?php _e('fake_website'); ?></li>
                <li><?php _e('mimics_real_attack'); ?></li>
                <li><?php _e('shows_vulnerabilities'); ?></li>
            </ul>
            
            <h4>🟢 <?php _e('defense_part'); ?>:</h4>
            <ul>
                <li><?php _e('how_to_detect'); ?></li>
                <li><?php _e('correct_protection'); ?></li>
                <li><?php _e('best_practices'); ?></li>
            </ul>
            
            <h4>⚠️ <?php _e('legal_warning'); ?>:</h4>
            <ul>
                <li><?php _e('criminal_use'); ?></li>
                <li><?php _e('deceiving_real_people'); ?></li>
                <li><?php _e('educational_only'); ?></li>
                <li><?php _e('full_user_responsibility'); ?></li>
            </ul>
            
            <h4>🎓 <?php _e('correct_teaching'); ?>:</h4>
            <ul>
                <li><?php _e('understand_vulnerabilities'); ?></li>
                <li><?php _e('learn_defense'); ?></li>
                <li><?php _e('apply_best_practices'); ?></li>
            </ul>
        </div>
    </div>
    
    <div class="cookie-banner">
        <span><?php _e('website_uses_cookies'); ?></span>
        <button onclick="this.parentElement.style.display='none'"><?php _e('ok_button'); ?></button>
    </div>
</body>
</html>
