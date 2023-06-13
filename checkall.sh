#!/bin/bash
echo "Check All"
echo "========================================================================="
echo "MySQL drop db, create db"
echo "========================================================================="
mysqladmin -uroot -proot drop devot
mysqladmin -uroot -proot create devot
echo "==================== Composer dump autoload===================="
composer dump-autoload
echo "==================== Artisan migrate:reset===================="
php artisan migrate:reset
echo "==================== Artisan migrate===================="
php artisan migrate
echo "==================== Artisan db:seed===================="
php artisan db:seed
echo "======================================================"
echo "git status"
git status
echo "======================================================"
