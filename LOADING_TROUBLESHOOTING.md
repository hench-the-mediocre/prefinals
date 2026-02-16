# Loading Screen Troubleshooting Guide

## 🔧 Quick Fix Steps

If the loading screen isn't showing, follow these steps:

### Step 1: Clear Browser Cache
The most common issue is browser caching old files.

**Windows/Linux:**
- Press `Ctrl + Shift + Delete`
- Select "Cached images and files"
- Click "Clear data"
- Or use `Ctrl + F5` for hard refresh

**Mac:**
- Press `Cmd + Shift + Delete`
- Select cached files
- Or use `Cmd + Shift + R` for hard refresh

### Step 2: Run Diagnostic Tool
Open in your browser:
```
http://localhost/your-project/loading_diagnostic.php
```

This will check:
- ✓ All files exist
- ✓ Files have correct content
- ✓ Integration is correct
- ✓ Configuration is loaded

### Step 3: Test with Simple Page
Open the simple test page:
```
http://localhost/your-project/simple_test.html
```

This is a minimal test without PHP to isolate issues.

### Step 4: Check Browser Console
1. Press `F12` to open Developer Tools
2. Go to "Console" tab
3. Refresh the page
4. Look for errors (red text)

You should see:
```
DOM Content Loaded - Initializing loading screen
LoadingScreen: Initializing...
LoadingScreen: Elements found successfully
LoadingScreen: Starting simulation...
LoadingScreen: Hiding...
LoadingScreen: Hidden successfully
```

## 🐛 Common Issues & Solutions

### Issue 1: Loading Screen Doesn't Appear

**Symptoms:**
- Page loads directly without loading screen
- No animation visible

**Solutions:**
1. **Clear browser cache** (most common fix)
2. **Check file paths:**
   ```
   css/loading.css  ← Must exist
   js/loading.js    ← Must exist
   loading.php      ← Must exist
   ```
3. **Verify in browser console:**
   - Open F12 → Console
   - Look for "LoadingScreen: Initializing..."
   - If missing, JS file isn't loading

4. **Check CSS is loaded:**
   - Open F12 → Network tab
   - Refresh page
   - Look for `loading.css` (should be 200 status)

### Issue 2: Loading Screen Shows But Doesn't Animate

**Symptoms:**
- Screen appears but progress bar doesn't move
- No fade out

**Solutions:**
1. **Check JavaScript console for errors**
2. **Verify loading.js is loaded:**
   ```javascript
   // In browser console, type:
   typeof LoadingScreen
   // Should return: "function"
   ```
3. **Check if elements exist:**
   ```javascript
   // In browser console:
   document.getElementById('loading-screen')
   // Should return: <div id="loading-screen">...
   ```

### Issue 3: Logo Doesn't Show

**Symptoms:**
- Everything works but logo is missing
- Broken image icon

**Solutions:**
1. **Check logo file exists:**
   ```
   images/logo.png  ← Must exist
   ```
2. **Verify path in loading_config.php:**
   ```php
   'logo_path' => 'images/logo.png',
   ```
3. **Check file permissions** (on server)
4. **Try absolute path:**
   ```php
   'logo_path' => '/full/path/to/images/logo.png',
   ```

### Issue 4: Loading Screen Stays Forever

**Symptoms:**
- Loading screen appears but never disappears
- Progress bar reaches 100% but screen stays

**Solutions:**
1. **Check JavaScript console for errors**
2. **Verify hide() function works:**
   ```javascript
   // In console after page loads:
   const loader = new LoadingScreen();
   loader.hide();
   ```
3. **Check CSS transition:**
   - Verify `#loading-screen.hidden` class exists in CSS
4. **Increase timeout in loading.js:**
   ```javascript
   // Change from 500 to 1000
   setTimeout(() => {
       this.loadingScreen.style.display = 'none';
   }, 1000);
   ```

### Issue 5: Works on Some Pages, Not Others

**Symptoms:**
- Loading screen works on test pages
- Doesn't work on dashboard.php or other pages

**Solutions:**
1. **Verify header.php is included:**
   ```php
   <?php require_once 'header.php'; ?>
   ```
2. **Verify footer.php is included:**
   ```php
   <?php require_once 'footer.php'; ?>
   ```
3. **Check for JavaScript conflicts:**
   - Other scripts might interfere
   - Check console for errors
4. **Ensure proper order:**
   ```php
   // Correct order:
   require_once 'header.php';  // Includes loading.php
   // Your page content
   require_once 'footer.php';  // Includes loading.js
   ```

## 🔍 Diagnostic Commands

### Check Files Exist (Command Line)
```bash
# Windows (PowerShell)
Test-Path css\loading.css
Test-Path js\loading.js
Test-Path loading.php

# Linux/Mac
ls -la css/loading.css
ls -la js/loading.js
ls -la loading.php
```

### Check File Sizes
```bash
# Windows (PowerShell)
Get-Item css\loading.css | Select-Object Length

# Linux/Mac
ls -lh css/loading.css
```

Expected sizes:
- loading.css: ~9-10 KB
- loading.js: ~6-7 KB
- loading.php: ~2 KB

### Test in Browser Console
```javascript
// 1. Check if loading screen element exists
document.getElementById('loading-screen')

// 2. Check if CSS is applied
getComputedStyle(document.getElementById('loading-screen')).display

// 3. Check if LoadingScreen class exists
typeof LoadingScreen

// 4. Manually trigger loading screen
const loader = new LoadingScreen();
loader.show();
loader.init();

// 5. Manually hide loading screen
loader.hide();
```

## 📋 Verification Checklist

Use this checklist to verify everything is working:

- [ ] Files exist:
  - [ ] css/loading.css
  - [ ] js/loading.js
  - [ ] loading.php
  - [ ] loading_config.php
  - [ ] images/logo.png

- [ ] Integration:
  - [ ] header.php includes loading.php
  - [ ] header.php links loading.css
  - [ ] footer.php includes loading.js

- [ ] Browser:
  - [ ] Cache cleared
  - [ ] Hard refresh performed (Ctrl+F5)
  - [ ] No console errors
  - [ ] Network tab shows files loading (200 status)

- [ ] Testing:
  - [ ] simple_test.html works
  - [ ] test_loading.php works
  - [ ] loading_demo.html works
  - [ ] dashboard.php works

## 🎯 Test Pages

### 1. Simple HTML Test
**File:** `simple_test.html`
**Purpose:** Test without PHP
**URL:** `http://localhost/your-project/simple_test.html`

### 2. PHP Test
**File:** `test_loading.php`
**Purpose:** Test with PHP integration
**URL:** `http://localhost/your-project/test_loading.php`

### 3. Full Demo
**File:** `loading_demo.html`
**Purpose:** Interactive demo with controls
**URL:** `http://localhost/your-project/loading_demo.html`

### 4. Diagnostic Tool
**File:** `loading_diagnostic.php`
**Purpose:** Check all files and integration
**URL:** `http://localhost/your-project/loading_diagnostic.php`

### 5. Live Dashboard
**File:** `dashboard.php`
**Purpose:** Real application test
**URL:** `http://localhost/your-project/dashboard.php`

## 🚀 Force Reload Instructions

### Method 1: Hard Refresh
- **Windows/Linux:** `Ctrl + F5` or `Ctrl + Shift + R`
- **Mac:** `Cmd + Shift + R`

### Method 2: Clear Cache
1. Open Developer Tools (F12)
2. Right-click the refresh button
3. Select "Empty Cache and Hard Reload"

### Method 3: Incognito Mode
- **Windows/Linux:** `Ctrl + Shift + N`
- **Mac:** `Cmd + Shift + N`
- Open your page in incognito/private mode

### Method 4: Disable Cache (Developer Tools)
1. Open F12 (Developer Tools)
2. Go to Network tab
3. Check "Disable cache"
4. Keep DevTools open while testing

## 📞 Still Not Working?

If you've tried everything above:

1. **Run the diagnostic tool:**
   ```
   loading_diagnostic.php
   ```

2. **Check the console output:**
   - Open F12 → Console
   - Copy any error messages

3. **Verify file contents:**
   - Open `css/loading.css` in text editor
   - Should start with: `/* Loading Screen Styles */`
   - Open `js/loading.js` in text editor
   - Should start with: `// Loading Screen Controller`

4. **Test with absolute minimum:**
   - Open `simple_test.html`
   - If this doesn't work, there's a file path issue

5. **Check server configuration:**
   - Ensure PHP is running
   - Ensure files are in correct directory
   - Check file permissions

## ✅ Success Indicators

You'll know it's working when:

1. **Visual:**
   - Purple gradient background appears immediately
   - Logo floats in center
   - "GOONERS TABLE" text visible
   - Progress bar animates 0% → 100%
   - Screen fades out smoothly

2. **Console:**
   ```
   DOM Content Loaded - Initializing loading screen
   LoadingScreen: Initializing...
   LoadingScreen: Elements found successfully
   LoadingScreen: Starting simulation...
   LoadingScreen: Hiding...
   LoadingScreen: Hidden successfully
   ```

3. **Network:**
   - loading.css: 200 OK
   - loading.js: 200 OK
   - logo.png: 200 OK

## 🎉 Quick Win

Try this RIGHT NOW:

1. Open: `http://localhost/your-project/simple_test.html`
2. Press `Ctrl + Shift + Delete` (clear cache)
3. Press `Ctrl + F5` (hard refresh)

If you see the loading screen → Everything works! Just needed cache clear.
If you don't see it → Run `loading_diagnostic.php` for detailed analysis.

---

**Need more help?** Check the other documentation files:
- `LOADING_QUICK_START.md` - Quick setup guide
- `LOADING_SCREEN_GUIDE.md` - Complete documentation
- `LOADING_VISUAL_GUIDE.txt` - Visual reference
