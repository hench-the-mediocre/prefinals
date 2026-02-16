<?php
/**
 * Loading Screen Configuration
 * Customize the loading screen appearance and behavior
 */

return [
    // Restaurant Information
    'restaurant_name' => 'GOONERS TABLE',
    'restaurant_tagline' => 'Fine Dining Experience',
    
    // Logo Settings
    'logo_path' => 'images/logo.png',
    'logo_alt' => 'Gooners Table Logo',
    'logo_width' => '150px',
    'logo_height' => '150px',
    
    // Loading Messages (shown during progress)
    'loading_messages' => [
        'Preparing your table...',
        'Loading menu items...',
        'Setting up kitchen...',
        'Warming up the grill...',
        'Almost ready...'
    ],
    
    // Animation Settings
    'enable_particles' => true,
    'particle_count' => 9,
    'enable_logo_animation' => true,
    'enable_parallax' => true,
    'enable_sparkles' => true,
    
    // Timing Settings (in milliseconds)
    'loading_interval' => 200,      // Speed of progress updates
    'min_display_time' => 1000,     // Minimum time to show loading screen
    'fade_out_duration' => 500,     // Fade out animation duration
    
    // Color Scheme (CSS gradient values)
    'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    'progress_gradient' => 'linear-gradient(90deg, #f093fb 0%, #f5576c 50%, #4facfe 100%)',
    
    // Progress Bar Settings
    'progress_bar_width' => '350px',
    'progress_bar_height' => '6px',
    'show_percentage' => true,
    
    // Responsive Settings
    'mobile_logo_width' => '100px',
    'mobile_logo_height' => '100px',
    'mobile_progress_width' => '250px',
    
    // Advanced Options
    'auto_hide' => true,            // Automatically hide when loaded
    'simulate_loading' => true,     // Simulate loading progress
    'real_progress' => false,       // Use real loading progress (requires custom implementation)
];
