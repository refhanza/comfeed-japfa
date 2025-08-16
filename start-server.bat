@echo off
echo.
echo ========================================
echo   COMFEED JAPFA - INVENTORY MANAGEMENT
echo ========================================
echo.
echo Starting Laravel Development Server...
echo.

REM Clear cache first
echo Clearing Laravel cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear

echo.
echo Server will start at: http://localhost:8080
echo Press Ctrl+C to stop the server
echo.

REM Change to public directory and start server
cd public
php -S localhost:8080 -t .