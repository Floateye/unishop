@echo off
echo Starting UniShop Development Environment...

:: Start Laravel Backend
start "UniShop Backend (Laravel)" cmd /k "php artisan serve"

:: Start Vite Frontend
start "UniShop Frontend (Vite)" cmd /k "npm run dev"

echo.
echo Development servers are starting in separate windows.
echo Laravel: http://127.0.0.1:8000
echo.
pause
