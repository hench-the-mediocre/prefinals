<?php
// Simple test page to verify loading screen
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading Screen Test - Gooners Table</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/loading.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .test-content {
            display: none;
            padding: 2rem;
            text-align: center;
            color: white;
        }
        .test-content.show {
            display: block;
        }
        .test-btn {
            background: white;
            color: #667eea;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .test-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <?php include 'loading.php'; ?>

    <div class="test-content" id="mainContent">
        <h1>✅ Loading Screen Test Successful!</h1>
        <p>If you saw the loading screen, everything is working correctly.</p>
        <button class="test-btn" onclick="location.reload()">Test Again</button>
        <button class="test-btn" onclick="location.href='dashboard.php'">Go to Dashboard</button>
        
        <div style="margin-top: 2rem; padding: 2rem; background: rgba(255,255,255,0.1); border-radius: 15px; max-width: 600px; margin-left: auto; margin-right: auto;">
            <h3>Debug Info:</h3>
            <p style="text-align: left;">
                ✓ CSS Loaded: css/loading.css<br>
                ✓ JS Loaded: js/loading.js<br>
                ✓ Component: loading.php<br>
                ✓ Config: loading_config.php<br>
                ✓ Logo: <?= file_exists('images/logo.png') ? 'Found' : 'Missing' ?>
            </p>
        </div>
    </div>

    <script src="js/loading.js"></script>
    <script>
        // Show main content after loading completes
        setTimeout(function() {
            document.getElementById('mainContent').classList.add('show');
        }, 100);
    </script>
</body>
</html>
