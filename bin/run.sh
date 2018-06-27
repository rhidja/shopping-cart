* Run your application:
  1. Change to the project directory
  2. Create your code repository with the git init command
  3. Execute the php -S 127.0.0.1:8000 -t public command
  4. Browse to the http://localhost:8000/ URL.

     Quit the server with CTRL-C.
     Run composer require server --dev for a better web server.

* Read the documentation at https://symfony.com/doc


Database Configuration


* Modify your DATABASE_URL config in .env

* Configure the driver (mysql) and
  server_version (5.7) in config/packages/doctrine.yaml


How to test?


* Write test cases in the tests/ folder
* Run php bin/phpunit
