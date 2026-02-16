@echo off
echo ========================================
echo  Push to GitHub - Gooners Table
echo  Repository: hench-the-mediocre/prefinals
echo ========================================
echo.

echo Checking git status...
git status
echo.

echo ========================================
echo Choose an option:
echo 1. Push ALL changes
echo 2. Push ONLY loading screen files
echo 3. Cancel
echo ========================================
set /p choice="Enter your choice (1-3): "

if "%choice%"=="1" goto pushall
if "%choice%"=="2" goto pushloading
if "%choice%"=="3" goto cancel
goto invalid

:pushall
echo.
echo Adding all files...
git add .
echo.
echo Committing changes...
git commit -m "Add loading screen feature with animations and interactive effects"
echo.
echo Pushing to GitHub...
git push origin main
if errorlevel 1 (
    echo.
    echo Failed with 'main' branch, trying 'master'...
    git push origin master
)
goto end

:pushloading
echo.
echo Adding loading screen files...
git add css/loading.css
git add js/loading.js
git add loading.php
git add loading_config.php
git add header.php
git add footer.php
git add simple_test.html
git add test_loading.php
git add loading_demo.html
git add loading_diagnostic.php
git add direct_test.php
git add test_include.php
git add LOADING_SCREEN_GUIDE.md
git add LOADING_QUICK_START.md
git add LOADING_SCREEN_SUMMARY.md
git add LOADING_VISUAL_GUIDE.txt
git add LOADING_TROUBLESHOOTING.md
git add START_HERE.md
git add GIT_PUSH_GUIDE.md
git add README_LOADING_SCREEN.md
git add .gitignore
echo.
echo Committing changes...
git commit -m "Add loading screen feature with animations and interactive effects"
echo.
echo Pushing to GitHub...
git push origin main
if errorlevel 1 (
    echo.
    echo Failed with 'main' branch, trying 'master'...
    git push origin master
)
goto end

:invalid
echo.
echo Invalid choice! Please run the script again.
goto end

:cancel
echo.
echo Cancelled.
goto end

:end
echo.
echo ========================================
echo Done!
echo ========================================
echo.
pause
