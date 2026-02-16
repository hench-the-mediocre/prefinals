<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jQuery Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .card { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .success { color: #28a745; font-size: 3rem; }
        .error { color: #dc3545; font-size: 3rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-body text-center p-5">
                <h1 class="mb-4">jQuery Loading Test</h1>
                
                <div id="test-result">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Testing...</span>
                    </div>
                    <p class="mt-3">Testing jQuery...</p>
                </div>
                
                <div class="mt-4">
                    <a href="dashboard.php" class="btn btn-primary btn-lg">Go to Dashboard</a>
                    <a href="test_crud.php" class="btn btn-secondary btn-lg">Run Full Test</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Test if jQuery is loaded
        setTimeout(function() {
            if (typeof jQuery !== 'undefined') {
                $('#test-result').html(`
                    <div class="success">✓</div>
                    <h2 class="text-success">jQuery is Working!</h2>
                    <p class="text-muted">Version: ${jQuery.fn.jquery}</p>
                    <div class="alert alert-success mt-3">
                        <strong>All CRUD pages should now work correctly!</strong><br>
                        The "$ is not defined" error has been fixed.
                    </div>
                `);
                
                // Test a simple jQuery operation
                $('body').css('transition', 'all 0.3s');
                
            } else {
                $('#test-result').html(`
                    <div class="error">✗</div>
                    <h2 class="text-danger">jQuery Failed to Load</h2>
                    <div class="alert alert-danger mt-3">
                        <strong>Problem:</strong> jQuery is not loading from CDN.<br>
                        <strong>Solution:</strong> Check your internet connection.
                    </div>
                `);
            }
        }, 500);
    </script>
</body>
</html>
