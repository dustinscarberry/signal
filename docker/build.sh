#!/bin/sh

# run build script from root of project as root

aws ecr get-login-password --region us-east-1 | docker login --username AWS --password-stdin 222621546520.dkr.ecr.us-east-1.amazonaws.com

# build and push app image
docker build -t 222621546520.dkr.ecr.us-east-1.amazonaws.com/signal:latest -t 222621546520.dkr.ecr.us-east-1.amazonaws.com/signal:$1 -f Dockerfile .
docker push 222621546520.dkr.ecr.us-east-1.amazonaws.com/signal:latest
docker push 222621546520.dkr.ecr.us-east-1.amazonaws.com/signal:$1