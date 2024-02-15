## REQUIERMENTS
- PHP ^8.1
- COMPOSER ^2.6.0
- NODEJS ^18.15.0
- NPM ^9.5.0
- MYSQL

## INSTALLATION
- install laravel package:
```sh
composer install
```
- install vuejs package:
```sh
npm install
```
- duplicate the .env.example file with new filename, .env
```sh
cp .env.example .env
```
- generate key:
```sh
php artisan key:generate
```
- Adjust the database configuration on the .env file

- make link from storage to public
```sh
php artisan storage:link
```
- run migration and default data
```sh
php artisan migrate --seed
```
- run laravel server
```sh
php artisan serve
```
- run vuejs server
```sh
npm run dev
```
