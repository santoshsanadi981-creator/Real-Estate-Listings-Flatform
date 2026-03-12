<?php
/**
 * Example: Login Page with Multilanguage Support
 * 
 * This file demonstrates how to use the translation system in PHP
 */

session_start();

// Include language system
require_once 'components/language.php';

// Get current language for HTML lang attribute
$currentLang = getLanguageManager()->getCurrentLanguage();
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php __te('login.loginAccount'); ?> | HomeHive</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .login-container {
            max-width: 500px;
            margin: 80px auto;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .login-container h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }

        .form-group.checkbox {
            display: flex;
            align-items: center;
        }

        .form-group.checkbox input {
            width: auto;
            margin-right: 10px;
        }

        .form-group.checkbox label {
            margin: 0;
            margin-left: 5px;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link p {
            color: #666;
            margin: 0;
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: none;
        }

        .alert.show {
            display: block;
        }

        .alert.error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .alert.success {
            background: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }
    </style>
</head>
<body>

<!-- Include Header with Language Switcher -->
<?php include 'components/user_header.php'; ?>

<!-- Login Container -->
<div class="login-container">
    <!-- Page Title -->
    <h1><?php __te('login.loginAccount'); ?></h1>

    <!-- Alert Messages -->
    <div id="alert" class="alert"></div>

    <!-- Login Form -->
    <form method="POST" action="login_process.php" id="loginForm">
        <!-- Email Field -->
        <div class="form-group">
            <label for="email"><?php __te('login.email'); ?></label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                required 
                placeholder="example@email.com"
            >
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <label for="password"><?php __te('login.password'); ?></label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                placeholder="••••••••"
            >
        </div>

        <!-- Remember Me Checkbox -->
        <div class="form-group checkbox">
            <input type="checkbox" id="remember" name="remember" value="1">
            <label for="remember"><?php __te('login.rememberMe'); ?></label>
        </div>

        <!-- Forgot Password Link -->
        <div style="text-align: right; margin-bottom: 20px;">
            <a href="forgot-password.html" style="color: #667eea; text-decoration: none;">
                <?php __te('login.forgotPassword'); ?>
            </a>
        </div>

        <!-- Login Button -->
        <button type="submit" class="login-btn">
            <?php __te('login.loginButton'); ?>
        </button>
    </form>

    <!-- Register Link -->
    <div class="register-link">
        <p>
            <?php __te('login.donHaveAccount'); ?>
            <a href="register.php"><?php __te('login.registerHere'); ?></a>
        </p>
    </div>
</div>

<!-- Footer -->
<?php include 'components/footer.php'; ?>

<!-- Scripts -->
<script src="js/script.js"></script>

<script>
    // Handle login form submission
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            const response = await fetch('login_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
            });

            const result = await response.json();

            if (result.success) {
                // Show success message using translated text
                showAlert('<?php echo __t('login.loginSuccess'); ?>', 'success');
                setTimeout(() => {
                    window.location.href = 'dashboard.php';
                }, 1500);
            } else {
                // Show error message using translated text
                showAlert('<?php echo __t('login.invalidCredentials'); ?>', 'error');
            }
        } catch (error) {
            console.error('Login error:', error);
            showAlert('<?php echo __t('messages.errorOccurred'); ?>', 'error');
        }
    });

    // Display alert message function
    function showAlert(message, type) {
        const alert = document.getElementById('alert');
        alert.textContent = message;
        alert.className = `alert show ${type}`;
        
        // Auto-hide after 5 seconds for success messages
        if (type === 'success') {
            setTimeout(() => {
                alert.classList.remove('show');
            }, 5000);
        }
    }

    // Example: Change translations when language changes
    window.addEventListener('languageChanged', (e) => {
        console.log('Login page language changed to:', e.detail.language);
        
        // Page automatically updates because backend re-renders
        // But if you have client-side content, you can update it here
        location.reload(); // Reload to use new language
    });
</script>

</body>
</html>
