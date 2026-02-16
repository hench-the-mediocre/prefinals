# Loading Screen - Quick Start Guide

## ✨ What You Get

A stunning animated loading screen with:
- 🎨 Purple gradient background matching your site theme
- 🖼️ Floating logo with glow effect
- 📊 Animated progress bar with shimmer
- ✨ Particle animations
- 🎯 Interactive hover effects
- 📱 Fully responsive design

## 🚀 Already Integrated!

The loading screen is **automatically active** on all pages using `header.php` and `footer.php`:
- ✅ Dashboard
- ✅ Categories
- ✅ Products
- ✅ Orders
- ✅ Billing
- ✅ Tables
- ✅ Taxes
- ✅ Users

## 🎮 Test It Now

1. **View Demo**: Open `loading_demo.html` in your browser
2. **See It Live**: Navigate to `dashboard.php`
3. **Refresh Any Page**: The loading screen appears automatically

## ⚙️ Quick Customization

### Change Restaurant Name
Edit `loading_config.php`:
```php
'restaurant_name' => 'YOUR RESTAURANT NAME',
'restaurant_tagline' => 'Your Tagline Here',
```

### Change Loading Messages
Edit `loading_config.php`:
```php
'loading_messages' => [
    'Welcome...',
    'Loading your experience...',
    'Almost there...'
],
```

### Adjust Loading Speed
Edit `loading_config.php`:
```php
'loading_interval' => 100,  // Faster (default: 200)
'loading_interval' => 400,  // Slower (default: 200)
```

### Change Colors
Edit `loading_config.php`:
```php
'background_gradient' => 'linear-gradient(135deg, #YOUR_COLOR1 0%, #YOUR_COLOR2 100%)',
'progress_gradient' => 'linear-gradient(90deg, #COLOR1 0%, #COLOR2 50%, #COLOR3 100%)',
```

### Disable Particles
Edit `loading_config.php`:
```php
'enable_particles' => false,
```

## 🎨 Interactive Features

### Logo Effects
- **Hover**: Logo rotates 360° and scales up
- **Click**: Restarts floating animation

### Text Effects
- **Hover on Name**: Letter spacing expands with glow

### Progress Bar
- **Hover**: Bar height increases
- **Auto**: Sparkles appear during loading

### Mouse Parallax
- **Move Mouse**: Particles follow cursor movement

## 📁 Files Created

```
css/
  └── loading.css          # All styles and animations
js/
  └── loading.js           # Loading controller and effects
loading.php                # Reusable component
loading_config.php         # Easy configuration
loading_demo.html          # Standalone demo
LOADING_SCREEN_GUIDE.md    # Full documentation
LOADING_QUICK_START.md     # This file
```

## 🔧 Troubleshooting

### Logo Not Showing?
- Check `images/logo.png` exists
- Update path in `loading_config.php`

### Loading Too Fast/Slow?
- Adjust `loading_interval` in `loading_config.php`

### Want to Disable?
- Remove `<?php include 'loading.php'; ?>` from `header.php`

## 📱 Mobile Friendly

The loading screen automatically adapts:
- **Desktop**: Full size (150px logo)
- **Tablet**: Medium size (120px logo)
- **Mobile**: Compact size (100px logo)

## 🎯 Key Features

1. **Smooth Animations**: 60fps GPU-accelerated
2. **Lightweight**: Only ~15KB total
3. **No Dependencies**: Pure CSS + Vanilla JS
4. **Accessible**: Screen reader friendly
5. **Customizable**: Easy config file

## 💡 Pro Tips

1. **Test First**: Use `loading_demo.html` to preview changes
2. **Keep It Short**: Users prefer 2-3 second loading screens
3. **Match Branding**: Update colors to match your theme
4. **Mobile Test**: Check on different devices
5. **Performance**: Disable particles on slow devices

## 🎬 What Happens

1. Page loads → Loading screen appears
2. Progress bar animates 0% → 100%
3. Messages change during progress
4. Screen fades out smoothly
5. Main content appears

## 📞 Need Help?

- Full docs: `LOADING_SCREEN_GUIDE.md`
- Config file: `loading_config.php`
- Demo page: `loading_demo.html`

---

**Enjoy your new loading screen! 🎉**

*Gooners Table - Fine Dining Experience*
