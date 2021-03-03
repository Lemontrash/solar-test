
## Solar Digital test app

Was built in 6-7h with around additional 4h in search of ways on how to write tests better.

## Stack used

- PHP 7.2
- Openserver Appache 
- Mysql 5.6
- Laravel 5.8 (Task specification mentions Laravel 5, but does not explicitly say 5.0 , so I figured 5.8 would do just fine)
- Postman
- PhpUnit 8
- Win 10
- <b>No Docker</b>

## How do I run it?

1. Clone the project from GitHub
2. Make sure your domain is set in the <code>public</code> folder
3. Go to the project directory in terminal
4. <code>composer install</code>
5. Copy the <code>env.example</code> contents to a new <code>.env</code> in root
6. Setup database connection parameters in <code>.env</code> and in <code>phpunit.xml</code>
7. <code>php artisan migrate</code>

At this point you are good to go and can run tests, play with the postman cllection <code>solar-test.postman_collection.json</code> in root or else.


<b>  Thanks for checking! </b>
