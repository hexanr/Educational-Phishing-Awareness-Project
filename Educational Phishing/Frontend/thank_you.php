<?php
include 'includes/languages.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>" dir="<?php echo $pageDir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php _e('thank_you'); ?> - <?php _e('protection_guide'); ?></title>
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
        
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 700px;
            width: 100%;
            padding: 40px;
            animation: slideIn 0.5s ease-out;
            text-align: <?php echo $pageAlign; ?>;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #667eea;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #666;
            font-size: 16px;
        }
        
        .section {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-<?php echo $pageAlign; ?>: 4px solid #667eea;
        }
        
        .section h2 {
            color: #667eea;
            font-size: 18px;
            margin-bottom: 15px;
        }
        
        .section p {
            color: #333;
            line-height: 1.8;
            margin-bottom: 10px;
        }
        
        .section ul {
            list-style: none;
            padding-<?php echo $pageAlign; ?>: 20px;
        }
        
        .section li {
            padding: 8px 0;
            color: #555;
            position: relative;
            padding-<?php echo $pageAlign; ?>: 25px;
        }
        
        .section li:before {
            content: "→";
            color: #667eea;
            position: absolute;
            <?php echo $pageAlign; ?>: 0;
            font-weight: bold;
        }
        
        .warning-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: <?php echo $pageAlign; ?>;
        }
        
        .warning-box h3 {
            color: #856404;
            margin-bottom: 10px;
        }
        
        .warning-box p {
            color: #856404;
            line-height: 1.6;
        }
        
        .danger-box {
            background: #ffebee;
            border: 2px solid #d32f2f;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: <?php echo $pageAlign; ?>;
        }
        
        .danger-box h3 {
            color: #d32f2f;
            margin-bottom: 10px;
        }
        
        .danger-box p {
            color: #d32f2f;
            line-height: 1.6;
        }
        
        .success-box {
            background: #e8f5e9;
            border: 2px solid #4caf50;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: <?php echo $pageAlign; ?>;
        }
        
        .success-box h3 {
            color: #2e7d32;
            margin-bottom: 10px;
        }
        
        .success-box p {
            color: #2e7d32;
            line-height: 1.6;
        }
        
        .comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        
        .comparison-box {
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }
        
        .real-world {
            background: #ffebee;
            border: 2px solid #d32f2f;
        }
        
        .real-world h4 {
            color: #d32f2f;
            margin-bottom: 10px;
        }
        
        .real-world p {
            color: #d32f2f;
            font-size: 13px;
            line-height: 1.6;
        }
        
        .educational {
            background: #e8f5e9;
            border: 2px solid #4caf50;
        }
        
        .educational h4 {
            color: #2e7d32;
            margin-bottom: 10px;
        }
        
        .educational p {
            color: #2e7d32;
            font-size: 13px;
            line-height: 1.6;
        }
        
        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        button, a.button {
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            font-weight: bold;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
            flex: 1;
            min-width: 150px;
        }
        
        .btn-primary:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: #f5f5f5;
            color: #333;
            border: 2px solid #ddd;
            flex: 1;
            min-width: 150px;
        }
        
        .btn-secondary:hover {
            background: #eee;
            border-color: #667eea;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            color: #666;
            font-size: 12px;
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 25px;
            }
            
            h1 {
                font-size: 22px;
            }
            
            .comparison {
                grid-template-columns: 1fr;
            }
            
            .buttons {
                flex-direction: column;
            }
            
            button, a.button {
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
    
    <div class="container">
        <header>
            <div class="icon">✅</div>
            <h1><?php _e('thank_you'); ?></h1>
            <p class="subtitle"><?php _e('now_learn'); ?></p>
        </header>
        
        <!-- Warning -->
        <div class="warning-box">
            <h3>⚠️ <?php _e('important_note'); ?></h3>
            <p>
                <?php _e('saw_fake_website'); ?><br>
                <?php _e('safe_educational_environment'); ?>
            </p>
        </div>
        
        <!-- What Happened -->
        <div class="section">
            <h2>📊 <?php _e('what_happened'); ?></h2>
            <ul>
                <li><?php _e('entered_data'); ?></li>
                <li><?php _e('website_saved_data'); ?></li>
                <li><?php _e('data_encrypted_safe'); ?></li>
                <li><?php _e('redirected_to_page'); ?></li>
            </ul>
        </div>
        
        <!-- Real World vs Educational -->
        <div class="section">
            <h2>🌍 <?php _e('real_vs_educational'); ?></h2>
            <div class="comparison">
                <div class="comparison-box real-world">
                    <h4>❌ <?php _e('in_real_world'); ?></h4>
                    <p><?php _e('attacker_sends'); ?></p>
                </div>
                <div class="comparison-box educational">
                    <h4>✅ <?php _e('in_this_project'); ?></h4>
                    <p><?php _e('safe_educational_site'); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Threats in Real World -->
        <div class="danger-box">
            <h3>🚨 <?php _e('real_dangers'); ?></h3>
            <p><strong><?php _e('real_phishing_can_lead'); ?></strong></p>
            <ul style="padding-<?php echo $pageAlign; ?>: 20px; margin-top: 10px;">
                <li style="padding-<?php echo $pageAlign; ?>: 25px;">❌ <?php _e('steal_money'); ?></li>
                <li style="padding-<?php echo $pageAlign; ?>: 25px;">❌ <?php _e('steal_personal_data'); ?></li>
                <li style="padding-<?php echo $pageAlign; ?>: 25px;">❌ <?php _e('blackmail_threats'); ?></li>
                <li style="padding-<?php echo $pageAlign; ?>: 25px;">❌ <?php _e('identity_theft'); ?></li>
                <li style="padding-<?php echo $pageAlign; ?>: 25px;">❌ <?php _e('lose_hard_work'); ?></li>
            </ul>
        </div>
        
        <!-- What You Should Learn -->
        <div class="section">
            <h2>🎓 <?php _e('what_should_learn'); ?></h2>
            <ul>
                <li><strong><?php _e('how_to_detect_phishing'); ?>:</strong> <?php _e('protect_from_phishing'); ?></li>
                <li><strong><?php _e('protect_yourself'); ?>:</strong> <?php _e('strong_unique_passwords'); ?></li>
                <li><strong><?php _e('trust_official'); ?>:</strong> <?php _e('verify_sender_email'); ?></li>
                <li><strong><?php _e('understand_risks'); ?>:</strong> <?php _e('report_to_authorities'); ?></li>
                <li><strong><?php _e('use_security_tools'); ?>:</strong> <?php _e('update_software'); ?></li>
            </ul>
        </div>
        
        <!-- Best Practices -->
        <div class="success-box">
            <h3>✅ <?php _e('best_security_practices'); ?></h3>
            <p><strong><?php _e('protect_from_phishing'); ?></strong></p>
            <ul style="padding-<?php echo $pageAlign; ?>: 20px; margin-top: 10px;">
                <li style="padding-<?php echo $pageAlign; ?>: 25px;">✓ <?php _e('open_from_correct_url'); ?></li>
                <li style="padding-<?php echo $pageAlign; ?>: 25px;">✓ <?php _e('look_for_green_lock'); ?></li>
                <li style="padding-<?php echo $pageAlign; ?>: 25px;">✓ <?php _e('dont_click_email_links'); ?></li>
                <li style="padding-<?php echo $pageAlign; ?>: 25px;">✓ <?php _e('strong_unique_passwords'); ?></li>
                <li style="padding-<?php echo $pageAlign; ?>: 25px;">✓ <?php _e('enable_2fa'); ?></li>
                <li style="padding-<?php echo $pageAlign; ?>: 25px;">✓ <?php _e('monitor_accounts'); ?></li>
            </ul>
        </div>
        
        <!-- Next Steps -->
        <div class="section">
            <h2>📝 <?php _e('next_steps'); ?></h2>
            <ul>
                <li><?php _e('read_full_guide'); ?></li>
                <li><?php _e('learn_vulnerabilities'); ?></li>
                <li><?php _e('discover_errors'); ?></li>
                <li><?php _e('teach_others'); ?></li>
                <li><?php _e('stay_vigilant'); ?></li>
            </ul>
        </div>
        
        <!-- Buttons -->
        <div class="buttons">
            <a href="protection_guide.php" class="button btn-primary">📚 <?php _e('next_steps_full_guide'); ?></a>
            <a href="warning.php" class="button btn-secondary"><?php _e('go_back_to_start'); ?></a>
        </div>
        
        <div class="footer">
            <p><strong><?php _e('remember_educational'); ?></strong></p>
            <p><?php _e('use_knowledge_wisely'); ?></p>
            <p><?php _e('copyright'); ?></p>
        </div>
    </div>
</body>
</html>
