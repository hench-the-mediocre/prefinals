<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direct Loading Screen Test</title>
    <link href="css/loading.css?v=<?= time() ?>" rel="stylesheet">
</head>
<body>
    <!-- Loading Screen - Direct HTML -->
    <div id="loading-screen">
        <div class="loading-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>

        <div class="loading-content">
            <div class="loading-logo-container">
                <div class="loading-glow"></div>
                <img src="images/logo.png" alt="Logo" class="loading-logo" onerror="this.style.display='none'">
            </div>

            <h1 class="restaurant-name">GOONERS TABLE</h1>
            <p class="restaurant-tagline">Fine Dining Experience</p>

            <div class="progress-container">
                <div class="progress-bar"></div>
            </div>

            <div class="loading-text">
                Preparing your table...
                <span class="loading-percentage">0%</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div style="padding: 2rem; text-align: center; background: white; min-height: 100vh;">
        <h1>✅ Direct Test Page</h1>
        <p>If you saw the loading screen, the HTML and CSS work fine.</p>
        <p>The issue is with the PHP include or JavaScript timing.</p>
    </div>

    <script src="js/loading.js?v=<?= time() ?>"></script>
    
    <script>
        // Additional debug
        console.log('Page loaded');
        console.log('Element check:', document.getElementById('loading-screen'));
    </script>
</body>
</html>
