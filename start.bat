@echo off

cd api

%systemDrive%\xampp\mysql\bin\mysql -uroot -e "CREATE DATABASE IF NOT EXISTS dating;"

php -r "copy('.env.example', '.env');"

call composer install

call php artisan migrate:fresh --seed

call php artisan key:generate

call php artisan serve

cd ..

cd client

call npm install

call ng build

call ng serve





echo "Setup completed successfully."
