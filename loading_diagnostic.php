<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading Screen Diagnostic</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            padding: 2rem;
            background: #1a1a1a;
            color: #00ff00;
        }
        .check { color: #00ff00; }
        .error { color: #ff0000; }
        .warning { color: #ffaa00; }
        .section {
            margin: 2rem 0;
            padding: 1rem;
            border: 1px solid #333;
            border-radius: 5px;
        }
        h2 { color: #00aaff; }
        pre {
            background: #000;
            padding: 1rem;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>🔍 Loading Screen Diagnostic Tool</h1>
    
    <div class="section">
        <h2>1. File Existence Check</h2>
        <pre><?php
        $files = [
            'css/loading.css' => 'Loading CSS',
            'js/loading.js' => 'Loading JavaScript',
            'loading.php' => 'Loading Component',
            'loading_config.php' => 'Loading Config',
            'images/logo.png' => 'Restaurant Logo'
        ];
        
        foreach ($files as $file => $desc) {
            $exists = file_exists($file);
            $status = $exists ? '<span class="check">✓</span>' : '<span class="error">✗</span>';
            $size = $exists ? ' (' . round(filesize($file) / 1024, 2) . ' KB)' : '';
            echo "$status $desc: $file$size\n";
        }
        ?></pre>
    </div>
    
    <div class="section">
        <h2>2. CSS File Content Check</h2>
        <pre><?php
        if (file_exists('css/loading.css')) {
            $css = file_get_contents('css/loading.css');
            $hasLoadingScreen = strpos($css, '#loading-screen') !== false;
            $hasAnimations = strpos($css, '@keyframes') !== false;
            $hasResponsive = strpos($css, '@media') !== false;
            
            echo ($hasLoadingScreen ? '<span class="check">✓</span>' : '<span class="error">✗</span>') . " Contains #loading-screen selector\n";
            echo ($hasAnimations ? '<span class="check">✓</span>' : '<span class="error">✗</span>') . " Contains animations\n";
            echo ($hasResponsive ? '<span class="check">✓</span>' : '<span class="error">✗</span>') . " Contains responsive styles\n";
            echo "\nFile size: " . strlen($css) . " bytes\n";
        } else {
            echo '<span class="error">✗ CSS file not found!</span>';
        }
        ?></pre>
    </div>
    
    <div class="section">
        <h2>3. JavaScript File Content Check</h2>
        <pre><?php
        if (file_exists('js/loading.js')) {
            $js = file_get_contents('js/loading.js');
            $hasClass = strpos($js, 'class LoadingScreen') !== false;
            $hasInit = strpos($js, 'init()') !== false;
            $hasDOMReady = strpos($js, 'DOMContentLoaded') !== false;
            
            echo ($hasClass ? '<span class="check">✓</span>' : '<span class="error">✗</span>') . " Contains LoadingScreen class\n";
            echo ($hasInit ? '<span class="check">✓</span>' : '<span class="error">✗</span>') . " Contains init() method\n";
            echo ($hasDOMReady ? '<span class="check">✓</span>' : '<span class="error">✗</span>') . " Contains DOMContentLoaded listener\n";
            echo "\nFile size: " . strlen($js) . " bytes\n";
        } else {
            echo '<span class="error">✗ JavaScript file not found!</span>';
        }
        ?></pre>
    </div>
    
    <div class="section">
        <h2>4. Header.php Integration Check</h2>
        <pre><?php
        if (file_exists('header.php')) {
            $header = file_get_contents('header.php');
            $hasLoadingCSS = strpos($header, 'loading.css') !== false;
            $hasLoadingPHP = strpos($header, 'loading.php') !== false;
            
            echo ($hasLoadingCSS ? '<span class="check">✓</span>' : '<span class="error">✗</span>') . " Loading CSS linked in header\n";
            echo ($hasLoadingPHP ? '<span class="check">✓</span>' : '<span class="error">✗</span>') . " Loading component included in header\n";
        } else {
            echo '<span class="error">✗ header.php not found!</span>';
        }
        ?></pre>
    </div>
    
    <div class="section">
        <h2>5. Footer.php Integration Check</h2>
        <pre><?php
        if (file_exists('footer.php')) {
            $footer = file_get_contents('footer.php');
            $hasLoadingJS = strpos($footer, 'loading.js') !== false;
            
            echo ($hasLoadingJS ? '<span class="check">✓</span>' : '<span class="error">✗</span>') . " Loading JS included in footer\n";
        } else {
            echo '<span class="error">✗ footer.php not found!</span>';
        }
        ?></pre>
    </div>
    
    <div class="section">
        <h2>6. Configuration Check</h2>
        <pre><?php
        if (file_exists('loading_config.php')) {
            $config = include 'loading_config.php';
            echo '<span class="check">✓</span> Configuration loaded successfully' . "\n\n";
            echo "Restaurant Name: " . $config['restaurant_name'] . "\n";
            echo "Tagline: " . $config['restaurant_tagline'] . "\n";
            echo "Logo Path: " . $config['logo_path'] . "\n";
            echo "Particles Enabled: " . ($config['enable_particles'] ? 'Yes' : 'No') . "\n";
            echo "Particle Count: " . $config['particle_count'] . "\n";
        } else {
            echo '<span class="warning">⚠</span> Config file not found (will use defaults)';
        }
        ?></pre>
    </div>
    
    <div class="section">
        <h2>7. Test Links</h2>
        <p>
            <a href="simple_test.html" style="color: #00aaff;">→ Simple HTML Test</a><br>
            <a href="test_loading.php" style="color: #00aaff;">→ PHP Test Page</a><br>
            <a href="loading_demo.html" style="color: #00aaff;">→ Full Demo</a><br>
            <a href="dashboard.php" style="color: #00aaff;">→ Dashboard (Live)</a>
        </p>
    </div>
    
    <div class="section">
        <h2>8. Browser Cache Notice</h2>
        <p class="warning">
            ⚠ If files exist but loading screen doesn't show:<br>
            1. Clear your browser cache (Ctrl+Shift+Delete)<br>
            2. Hard refresh the page (Ctrl+F5 or Cmd+Shift+R)<br>
            3. Try opening in incognito/private mode<br>
            4. Check browser console for JavaScript errors (F12)
        </p>
    </div>
    
    <div class="section">
        <h2>✅ Summary</h2>
        <pre><?php
        $allGood = true;
        $required = ['css/loading.css', 'js/loading.js', 'loading.php'];
        foreach ($required as $file) {
            if (!file_exists($file)) {
                $allGood = false;
                break;
            }
        }
        
        if ($allGood) {
            echo '<span class="check">✓ All required files are present!</span>' . "\n";
            echo '<span class="check">✓ Integration appears correct!</span>' . "\n\n";
            echo "Next steps:\n";
            echo "1. Clear browser cache\n";
            echo "2. Test with: simple_test.html\n";
            echo "3. Then try: dashboard.php\n";
        } else {
            echo '<span class="error">✗ Some files are missing!</span>' . "\n";
            echo "Please check the file paths above.\n";
        }
        ?></pre>
    </div>
</body>
</html>
