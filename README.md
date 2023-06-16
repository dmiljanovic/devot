# Devot
## Home budget API

* This mini project is a response to the task.

## Criteria
* Tech stack:
  * use any framework you are comfortable with
  * use RDBS (additional point is ORM)
  * output should be JSON formatted
  * API is documented (Swagger or similar)

* Features:
  * user authentication (register, login)
  * entities and attributes are defined by you, and what you think is important
  * for simplicity, every user has a predefined X amount of money on their account
  * categories CRUD
  * expenses CRUD (you can add expense when you spend the money)
  * every bill is in relation with category
  * filter bills by parameters (category, price min-max, date, and any other parameter
  you want)
  * data aggregation endpoint (example: money earned & spent in last month, quarter,
  year, here you can play )

* Bonus:
  * there are predefined categories of your own choice (food, car, accommodation, gifts,
  ...)
  * Tests

## Instructions and installation

* Clone repo
* From your console at your root folder execute 'composer install' to install dependencies.
* At your project root, rename '.env.example' file to '.env' and set DB connection.
* From your console at your root folder execute 'php artisan key:generate' to generate and set APP_KEY.
* From your console at your root folder execute 'sh checkall.sh' to start migrations and seeders.
* To enter API documentation go to localhost/docs
* To run tests execute 'php artisan test' from your console at your root folder

Enjoy :) 
