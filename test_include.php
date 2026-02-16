<?php
// Test if loading.php is working correctly
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Include Test</title>
</head>
<body>
    <h1>Testing loading.php include</h1>
    
    <h2>Before Include:</h2>
    <pre><?php var_dump(file_exists('loading.php')); ?></pre>
    
    <h2>Including loading.php:</h2>
    <?php 
    ob_start();
    include 'loading.php'; 
    $output = ob_get_clean();
    ?>
    
    <h2>Output Length:</h2>
    <pre><?php echo strlen($output); ?> characters</pre>
    
    <h2>Output Preview (first 500 chars):</h2>
    <pre><?php echo htmlspecialchars(substr($output, 0, 500)); ?></pre>
    
    <h2>Check for #loading-screen:</h2>
    <pre><?php echo strpos($output, 'id="loading-screen"') !== false ? 'FOUND ✓' : 'NOT FOUND ✗'; ?></pre>
    
    <h2>Full Output:</h2>
    <div style="border: 2px solid red; padding: 10px;">
        <?php echo $output; ?>
    </div>
    
    <script>
        console.log('Checking for #loading-screen element...');
        const element = document.getElementById('loading-screen');
        console.log('Element found:', element);
        if (element) {
            console.log('✓ Element exists!');
            console.log('Display:', getComputedStyle(element).display);
            console.log('Position:', getComputedStyle(element).position);
            console.log('Z-index:', getComputedStyle(element).zIndex);
        } else {
            console.error('✗ Element NOT found!');
        }
    </script>
</body>
</html>
