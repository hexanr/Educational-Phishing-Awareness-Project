-- =====================================================
-- Phishing Educational Project - Database Setup
-- =====================================================
-- ⚠️ EDUCATIONAL PURPOSE ONLY
-- 
-- هذا المشروع بهدف توعوي لشرح:
-- 1. كيف يعمل هجوم Phishing
-- 2. الثغرات الأمنية الشائعة
-- 3. أفضل الممارسات الأمنية
-- =====================================================

-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS phishing CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE phishing;

-- =====================================================
-- جدول المستخدمين
-- =====================================================

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- جدول السجلات (للأغراض التعليمية والأمنية)
-- =====================================================

CREATE TABLE IF NOT EXISTS `security_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_type` varchar(100) NOT NULL,
  `description` text,
  `username` varchar(255),
  `ip_address` varchar(45),
  `timestamp` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_timestamp` (`timestamp`),
  INDEX `idx_event_type` (`event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- جدول المصادقة الثنائية (2FA)
-- =====================================================

CREATE TABLE IF NOT EXISTS `two_factor_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `secret_key` varchar(32) NOT NULL,
  `enabled` boolean DEFAULT FALSE,
  `backup_codes` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- ملاحظات توعوية
-- =====================================================

-- 🔐 SECURITY NOTES / ملاحظات الأمان:

-- 1. كلمات المرور يجب أن تكون مشفرة باستخدام PASSWORD_BCRYPT
--    Passwords must be hashed using PASSWORD_BCRYPT
--    
--    ❌ خطأ: INSERT INTO users VALUES (1, 'ahmed', '12345678')
--    ✅ صحيح: INSERT INTO users VALUES (1, 'ahmed', '$2y$12$...')

-- 2. استخدم Prepared Statements لتجنب SQL Injection
--    Use Prepared Statements to prevent SQL Injection
--
--    ❌ خطأ: $query = "SELECT * FROM users WHERE username = '$username'";
--    ✅ صحيح: $stmt->bind_param("s", $username);

-- 3. تفقد المدخلات قبل حفظها
--    Validate input before saving
--
--    ✅ استخدم FILTER_SANITIZE_STRING و regex validation

-- 4. استخدم HTTPS فقط
--    Use HTTPS only

-- 5. أضف المصادقة الثنائية (2FA)
--    Add Two-Factor Authentication

-- 6. احفظ السجلات الأمنية
--    Keep security logs

-- 7. استخدم CSRF tokens
--    Use CSRF tokens for forms

-- =====================================================
-- البيانات الوهمية للاختبار
-- (استخدم في بيئة التطوير فقط - Dev Only)
-- =====================================================

-- ملاحظة: هذه كلمات مرور مشفرة باستخدام PASSWORD_BCRYPT
-- password: "test123" -> hash: $2y$12$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36CHqDNa

INSERT INTO `users` (`username`, `password`) VALUES
-- للأغراض التعليمية فقط
-- ('demo_user', '$2y$12$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36CHqDNa');

-- =====================================================
-- إنشاء حساب مسؤول جامعي (اختياري)
-- =====================================================

-- CREATE TABLE IF NOT EXISTS `admin_users` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `username` varchar(255) NOT NULL UNIQUE,
--   `password` varchar(255) NOT NULL,
--   `role` varchar(50),
--   `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- صلاحيات الجداول (اختياري)
-- =====================================================

-- لأغراض الأمان، يمكنك تحديد صلاحيات لمستخدم خاص
-- CREATE USER 'phishing_app'@'localhost' IDENTIFIED BY 'secure_password';
-- GRANT SELECT, INSERT ON phishing.* TO 'phishing_app'@'localhost';
-- FLUSH PRIVILEGES;

-- =====================================================
-- تعليمات الحذف (في حالة التنظيف)
-- =====================================================

-- لحذف كل البيانات وإعادة تعيين:
-- TRUNCATE TABLE users;
-- TRUNCATE TABLE security_logs;
-- TRUNCATE TABLE two_factor_auth;

-- =====================================================
-- نهاية ملف قاعدة البيانات
-- =====================================================
