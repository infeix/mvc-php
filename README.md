# mvc-php
This is a mvc Framework.

Ontop there are some controllers, views and examplethings so you see how it works.

## installation
* install apache and php
 * in my case it was php 7.0.8

### configuration
* uncomment `extension=mysqli.so` in the php.ini
* to use the `.htaccess` file you need to allow `AllowOverride All`
* in the file `config/db/db_config.php` you need to put your mysql configuration. (like in the example file) 
* the example database schema and example seed data you can add with calling the path `/reset/index/secret42`

## testing
To run the test suite you need to get phpunit and run `phpunit --coverage-html test/coverage`.

