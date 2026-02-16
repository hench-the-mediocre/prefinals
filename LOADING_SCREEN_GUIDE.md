# Gooners Table - Loading Screen Guide

## Overview
A beautiful, animated loading screen with the restaurant logo, brand name "GOONERS TABLE", and an interactive progress bar. The loading screen features smooth animations, hover effects, particle animations, and a modern glassmorphism design.

## Features

### Visual Elements
- **Logo Display**: Centered logo with floating animation and glow effect
- **Restaurant Branding**: "GOONERS TABLE" with elegant typography
- **Progress Bar**: Animated gradient progress bar with shimmer effect
- **Particle Animation**: Floating particles in the background
- **Gradient Background**: Purple gradient matching the site theme

### Interactive Effects
- **Logo Hover**: Logo rotates 360° and scales up on hover
- **Name Hover**: Restaurant name expands letter spacing on hover
- **Progress Bar Hover**: Progress bar height increases on hover
- **Mouse Parallax**: Particles move based on mouse position
- **Sparkle Effects**: Sparkles appear on the progress bar

### Animations
- Fade in/up entrance animations
- Floating logo animation
- Pulsing glow effect
- Shimmer progress bar animation
- Particle floating animation

## Files Created

### CSS
- `css/loading.css` - All loading screen styles and animations

### JavaScript
- `js/loading.js` - Loading screen controller and interactive effects

### PHP Component
- `loading.php` - Reusable loading screen component

### Demo
- `loading_demo.html` - Standalone demo page

## Integration

### Automatic Integration
The loading screen is automatically included in all pages that use `header.php`:

```php
<?php require_once 'header.php'; ?>
<!-- Your page content -->
<?php require_once 'footer.php'; ?>
```

### Manual Integration
To add the loading screen to a custom page:

```html
<!DOCTYPE html>
<html>
<head>
    <link href="css/loading.css" rel="stylesheet">
</head>
<body>
    <?php include 'loading.php'; ?>
    
    <!-- Your content -->
    
    <script src="js/loading.js"></script>
</body>
</html>
```

## Customization

### Change Loading Duration
Edit `js/loading.js`:

```javascript
// Faster loading (change interval from 200 to 100)
}, 100);

// Slower loading (change interval from 200 to 400)
}, 400);
```

### Change Loading Messages
Edit `js/loading.js`:

```javascript
this.loadingMessages = [
    'Your custom message 1...',
    'Your custom message 2...',
    'Your custom message 3...',
];
```

### Change Colors
Edit `css/loading.css`:

```css
/* Background gradient */
background: linear-gradient(135deg, #YOUR_COLOR1 0%, #YOUR_COLOR2 100%);

/* Progress bar gradient */
background: linear-gradient(90deg, #COLOR1 0%, #COLOR2 50%, #COLOR3 100%);
```

### Change Logo Size
Edit `css/loading.css`:

```css
.loading-logo {
    width: 200px;  /* Change from 150px */
    height: 200px; /* Change from 150px */
}
```

### Change Restaurant Name
Edit `loading.php`:

```html
<h1 class="restaurant-name">YOUR RESTAURANT NAME</h1>
<p class="restaurant-tagline">Your Tagline</p>
```

## Browser Compatibility
- Chrome/Edge: Full support
- Firefox: Full support
- Safari: Full support
- Mobile browsers: Full support with responsive design

## Performance
- Lightweight: ~15KB total (CSS + JS)
- No external dependencies
- GPU-accelerated animations
- Optimized for 60fps

## Responsive Design
The loading screen automatically adapts to different screen sizes:
- Desktop: Full size with all effects
- Tablet: Medium size with adjusted spacing
- Mobile: Compact size with optimized layout

## Testing
1. Open `loading_demo.html` in your browser
2. Watch the loading animation
3. Test hover effects on logo and text
4. Move mouse to see parallax effect
5. Click "Show Loading Screen Again" to replay

## Troubleshooting

### Loading screen doesn't appear
- Check that `css/loading.css` is loaded
- Check that `js/loading.js` is loaded
- Verify `loading.php` is included

### Logo doesn't show
- Verify `images/logo.png` exists
- Check image path in `loading.php`
- Check browser console for errors

### Animations not smooth
- Check browser hardware acceleration is enabled
- Reduce particle count in `loading.php`
- Disable some animations in `css/loading.css`

### Loading takes too long
- Adjust interval in `js/loading.js` (line ~30)
- Reduce increment value for faster progress

## Advanced Usage

### Programmatic Control

```javascript
// Create instance
const loader = new LoadingScreen();

// Show loading screen
loader.show();

// Start loading animation
loader.init();

// Update progress manually
loader.updateProgress(50); // 50%

// Hide loading screen
loader.hide();

// Change message
loader.updateMessage('Custom message...');
```

### Event Hooks

```javascript
// After loading completes
document.addEventListener('DOMContentLoaded', function() {
    const loader = new LoadingScreen();
    loader.init();
    
    // Add custom code after loading
    setTimeout(() => {
        console.log('Loading complete!');
        // Your code here
    }, 3000);
});
```

## Credits
- Design: Modern glassmorphism with gradient aesthetics
- Animations: CSS3 keyframe animations
- Font: Google Fonts - Poppins
- Icons: Bootstrap Icons

## Support
For issues or questions, check the main README.md or contact the development team.

---

**Gooners Table Restaurant Management System**
*Fine Dining Experience*
