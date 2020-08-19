#!/usr/bin/env bash

if [ -z "${DEPLOY_LOGIN}" ] ; then
  echo "ENV: DEPLOY_LOGIN is missing!"
  exit 1
fi

if [ -z "${DEPLOY_HOST}" ] ; then
  echo "ENV: DEPLOY_HOST is missing!"
  exit 1
fi

if [ -z "${ECR_PREFIX}" ] ; then
  echo "ENV: ECR_PREFIX is missing!"
  exit 1
fi

if [ -z "${NATS_HOST}" ] ; then
  echo "ENV: NATS_HOST is missing!"
  exit 1
fi

if [ -z "${SSH_PUB}" ] ; then
  echo "FILE: SSH_PUB is missing!"
  exit 1
fi

if [ -z "${SSH_PRIV}" ] ; then
  echo "FILE: SSH_PRIV is missing!"
  exit 1
fi

cp scripts/deploy.sh.dist scripts/deploy.sh
sed -i "s|app@1.1.1.1|${DEPLOY_LOGIN}|" scripts/deploy.sh
sed -i "s|stuff.prod.google.com|${DEPLOY_HOST}|" scripts/deploy.sh

cp scripts/build.sh.dist scripts/build.sh
sed -i "s|xxxx.dkr.ecr.eu-central-1.amazonaws.com|${ECR_PREFIX}|" scripts/build.sh

cp scripts/push.sh.dist scripts/push.sh
sed -i "s|xxxx.dkr.ecr.eu-central-1.amazonaws.com|${ECR_PREFIX}|" scripts/push.sh

cp docker-compose.prod.dist docker-compose.prod.yml
sed -i "s|xxxx.dkr.ecr.eu-central-1.amazonaws.com|${ECR_PREFIX}|" docker-compose.prod.yml
sed -i "s|stuff.prod.google.com|${DEPLOY_HOST}|" docker-compose.prod.yml
sed -i "s|nats.prod.google.com|${NATS_HOST}|" docker-compose.prod.yml

cp docker-compose.yml.dist docker-compose.yml
sed -i "s|xxxx.dkr.ecr.eu-central-1.amazonaws.com|${ECR_PREFIX}|" docker-compose.yml

cp ${SSH_PRIV} ./scripts/docker/php/id_rsa
cp ${SSH_PUB} ./scripts/docker/php/id_rsa.pub