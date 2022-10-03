#Elasticsearch setup instruction

1) Make sure you're on `feature/30657974-elastic-search` git branch and that the value of APP_ENV in your .env file is `local`
2) Turn off your docker-compose via this command `docker-compose down -v`
3) Open docker-compose.yml file. 
   There, you have at least 2 services, one for php, and second for mysql. 
   Your file should look like this
   ~~~
   version: '3'
   services:
        php-laravel:
            ...
        mysql:
            ...
   ~~~
   Notice, that your services can have different names.  
   So, you need to add another service, here is the code
   ~~~
    es:
        image: docker.elastic.co/elasticsearch/elasticsearch:7.10.1
        container_name: es
        ports:
            - "9200:9200"
            - "9300:9300"
        environment:
            - discovery.type=single-node
        links:
            - mysql
   ~~~
4) Start your docker-compose via this command `docker-compose up -d`
5) Open php container bash `docker exec -it {containerName} "/bin/bash"`
6) Run `composer update`
7) Run `php artisan config:cache`
8) Run `php artisan route:cache`
9) Run `php artisan elasticsearch:index`  
This command go through all things that should be indexed via elasticsearch. 
   Unfortunately, for now elasticsearch data will be cleared after each docker restart, 
   I still can't find solution to safe data, so if anyone can help with - appreceated!
   Alos, I think platform.sh handle this issue, and there will be no need to run this command with each deploy, cause it takes some time
   
   
   