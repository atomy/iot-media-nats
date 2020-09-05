#!/usr/bin/env bash

set -e

if [ -z "${DEPLOY_LOGIN}" ] ; then
  echo "ENV: DEPLOY_LOGIN is missing!"
  exit 1
fi

if [ -z "${ECR_PREFIX}" ] ; then
  echo "ENV: ECR_PREFIX is missing!"
  exit 1
fi

rm -f scripts/deploy.sh
rm -f scripts/build.sh
rm -f scripts/push.sh
rm -f docker-compose.yml

cp docker-compose.yml.dist docker-compose.yml
sed -i "s|xxx.dkr.ecr.eu-central-1.amazonaws.com|${ECR_PREFIX}|" docker-compose.yml

cp scripts/deploy.sh.dist scripts/deploy.sh
sed -i "s|app@1.1.1.1|${DEPLOY_LOGIN}|" scripts/deploy.sh

cp scripts/build.sh.dist scripts/build.sh
sed -i "s|xxx.dkr.ecr.eu-central-1.amazonaws.com|${ECR_PREFIX}|" scripts/build.sh

cp scripts/push.sh.dist scripts/push.sh
sed -i "s|xxx.dkr.ecr.eu-central-1.amazonaws.com|${ECR_PREFIX}|" scripts/push.sh
