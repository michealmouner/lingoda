Ligoda coding challenge
========================
This is test assignment for Lingoda 

### Requirements:
Create an API point using Symfony for a contact form. The contact form should accept the  
following fields:  
* E-mail (required, valid)
* Message (required, max length 1000)
The data should be validated and persisted in a database  
Setup environment: VM with PHP 7.x, Symfony standard edition.  

### Installation 
- clone project then go to project directory in terminal
- run `docker-compose up -d` 
- run `docker-compose start` 
- run `docker-compose images` ==> to get php-fpm container name
- run `docker exec -it PHP_FPM_CONTAINER_NAME composer install` 
- you will be prompeted to add some params needed for the project `leave defaults (recommended)`
- run `docker exec -it PHP_FPM_CONTAINER_NAME bin/console doctrine:migrations:migrate -n --env prod`
- run `docker exec -it PHP_FPM_CONTAINER_NAME bin/console doctrine:migrations:migrate -n --env test`
- run `docker exec -it PHP_FPM_CONTAINER_NAME chmod 777 -R ./var/cache ./var/logs ./var/sessions`
- run `docker exec -it PHP_FPM_CONTAINER_NAME composer test`

### Installetion Notes:
PHP_FPM_CONTAINER_NAME normally lingoda-php-fpm  
but in linux some times adding random number beside the container name to be like that lingoda-php-fpm_1_e8433324d772

## Routing
Method|URL|Description
------|---------|-----------
GET|/api/doc|documentation for the REST APIs
POST|/api/contact|create new contact us
GET|/api/contact|return list of contact to test my work
GET|/|redirect to api documentation for now

### My work
- I use phpdocker.io to generate my docker
- Added to this docker another image, mysql_test to be the testing database
- Created some basics handling for the API errors in general
- Create 10 Test cases 42 asserions 
- Adding translation to API form validations and error handling.. all you need to send Accept-Language en|de in headers
- Create migrations for the database
- For contactus api I create FormType and submit and validate data throw Symfony form
- I created contactus list to just see the last 10 records to make sure everything working


