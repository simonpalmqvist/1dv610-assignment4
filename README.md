# Login_1DV608
Interface repository for 1DV608 assignment 2 and 4

## Setup development environment
* Install docker
* docker-machine create --driver virtualbox default
* run `eval $(docker-machine env default)`
* run `./dev.sh`
* execute setup file `docker-compose run --rm --no-deps php php createAndPopulateTables.php`

## Production environment

### Setup
* `export DOTOKEN=digital-ocean-api-token`
* `docker-machine create --driver digitalocean --digitalocean-access-token $DOTOKEN 1dv610-assignment2`
* 
```
docker-machine ssh 1dv610-assignment2 "apt-get update"
docker-machine ssh 1dv610-assignment2 "apt-get -y install fail2ban"
docker-machine ssh 1dv610-assignment2 "ufw default deny"
docker-machine ssh 1dv610-assignment2 "ufw allow ssh"
docker-machine ssh 1dv610-assignment2 "ufw allow http"
docker-machine ssh 1dv610-assignment2 "ufw allow 2376"
docker-machine ssh 1dv610-assignment2 "ufw allow 8080"
docker-machine ssh 1dv610-assignment2 "ufw --force enable"
```
* run `eval $(docker-machine env 1dv610-assignment2)`
* run `./production.sh`
* execute setup file `docker-compose run --rm --no-deps php php createAndPopulateTables.php`