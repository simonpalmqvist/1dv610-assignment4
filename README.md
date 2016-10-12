# Login_1DV608
Interface repository for 1DV608 assignment 2 and 4

## Only use the Auth library
TBA

## Setup default development environment
* Install docker
* cp config.php.default to config.php and change credentials
* docker-machine create --driver virtualbox default
* run `eval $(docker-machine env default)`
* run `./dev.sh`
* execute setup file `docker-compose run --rm --no-deps php php createAndPopulateTables.php`

## Default Production environment

### Setup
* copy config.php.default to config.php and change credentials
* `export DOTOKEN=digital-ocean-api-token`
* `docker-machine create --digitalocean-region lon1 --driver digitalocean --digitalocean-access-token $DOTOKEN 1dv610-assignment-2`
* 
```
docker-machine ssh 1dv610-assignment-2 "apt-get update"
docker-machine ssh 1dv610-assignment-2 "ufw default deny"
docker-machine ssh 1dv610-assignment-2 "ufw allow ssh"
docker-machine ssh 1dv610-assignment-2 "ufw allow http"
docker-machine ssh 1dv610-assignment-2 "ufw allow 2376"
docker-machine ssh 1dv610-assignment-2 "ufw allow 8080"
docker-machine ssh 1dv610-assignment-2 "ufw --force enable"
```
* run `eval $(docker-machine env 1dv610-assignment-2)`
* run `./production.sh`
* update credentials in phpmyadmin to match config.php
* execute setup file `docker-compose run --rm --no-deps php php createAndPopulateTables.php`
