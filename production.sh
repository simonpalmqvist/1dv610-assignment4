#!/bin/bash

eval "$(docker-machine env 1dv610-assignment2)"
docker-compose -f docker-compose.yml -f production.yml build
docker-compose -f docker-compose.yml -f production.yml up -d