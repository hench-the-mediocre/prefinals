<?php
// Load configuration
if (file_exists('loading_config.php')) {
    $loadingConfig = include 'loading_config.php';
    // If include returns true/1 instead of array, use defaults
    if (!is_array($loadingConfig)) {
        $loadingConfig = [
            'restaurant_name' => 'GOONERS TABLE',
            'restaurant_tagline' => 'Fine Dining Experience',
            'logo_path' => 'images/logo.png',
            'logo_alt' => 'Gooners Table Logo',
            'enable_particles' => true,
            'particle_count' => 9,
        ];
    }
} else {
    $loadingConfig = [
        'restaurant_name' => 'GOONERS TABLE',
        'restaurant_tagline' => 'Fine Dining Experience',
        'logo_path' => 'images/logo.png',
        'logo_alt' => 'Gooners Table Logo',
        'enable_particles' => true,
        'particle_count' => 9,
    ];
}
?>
<!-- Loading Screen Component -->
<div id="loading-screen">
    <!-- Animated Particles Background -->
    <?php if ($loadingConfig['enable_particles']): ?>
    <div class="loading-particles">
        <?php for ($i = 0; $i < $loadingConfig['particle_count']; $i++): ?>
        <div class="particle"></div>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

    <!-- Main Loading Content -->
    <div class="loading-content">
        <!-- Logo with Glow Effect -->
        <div class="loading-logo-container">
            <div class="loading-glow"></div>
            <img src="<?= htmlspecialchars($loadingConfig['logo_path']) ?>" 
                 alt="<?= htmlspecialchars($loadingConfig['logo_alt']) ?>" 
                 class="loading-logo">
        </div>

        <!-- Restaurant Name -->
        <h1 class="restaurant-name"><?= htmlspecialchars($loadingConfig['restaurant_name']) ?></h1>
        <p class="restaurant-tagline"><?= htmlspecialchars($loadingConfig['restaurant_tagline']) ?></p>

        <!-- Progress Bar -->
        <div class="progress-container">
            <div class="progress-bar"></div>
        </div>

        <!-- Loading Text -->
        <div class="loading-text">
            Preparing your table...
            <span class="loading-percentage">0%</span>
        </div>
    </div>
</div>
