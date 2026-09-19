@echo off
echo Limpiando cache de Blade...
del /Q /F "C:\SAAS\saas_gimnasio\storage\framework\views\*" 2>nul
echo Cache limpiado.
echo.
echo Ejecutando php artisan view:clear...
cd /d C:\SAAS\saas_gimnasio
php artisan view:clear
php artisan cache:clear
php artisan config:clear
echo.
echo Listo! Recarga el navegador.
pause
