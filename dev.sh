#!/bin/bash

eval "$(docker-machine env default)"
docker-compose build
docker-compose up