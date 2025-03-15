@echo off
cd /d "%~dp0"

:: Start Laravel server (Backend)
cd backend
start cmd /k "php artisan serve"

:: Start Vue app (Frontend)
cd ../frontend
start cmd /k "npm run dev"
