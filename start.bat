@echo off

REM Set up Laravel API

cd api

REM 
%systemDrive%\xampp\mysql\bin\mysql -uroot -e "CREATE DATABASE IF NOT EXISTS ai1_lab11;"

REM 
php -r "copy('.env.example', '.env');"

REM 
call composer install

REM
call php artisan migrate:fresh --seed

REM 
call php artisan key:generate

cd ..

REM 

cd client

REM 
call npm install

REM 
call ng build

cd ..

echo "Setup completed successfully."
