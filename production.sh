#!/bin/bash

docker-compose -f docker-compose.yml -f docker-compose.prod.yml build
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
docker-machine ssh 1dv610-assignment-2 mkdir -p /var/www/html
docker-machine scp -r ./src 1dv610-assignment-2:/var/www/html