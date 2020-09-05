#!/usr/bin/env bash

set -e

NEW_VERSION=`git describe --abbrev=0 --tags`

if [ -z "${DISCORD_WEBHOOK_URL}" ] ; then
  echo "ENV: DISCORD_WEBHOOK_URL is missing!"
  exit 1
fi

if [ -z "${APP_NAME}" ] ; then
  echo "ENV: APP_NAME is missing!"
  exit 1
fi

CURRENT_VERSION=`git describe --abbrev=0 --tags ${NEW_VERSION}^`

CHANGES=`git log --pretty=format:%B ${CURRENT_VERSION}..${NEW_VERSION} | sort | uniq`
CHANGES=`echo ${CHANGES} | sed ':a;N;$!ba;s/\n/\\\n/g'`

curl -X POST \
  -H "Content-Type: application/json" \
  -d "{\"username\": \"Jenkins-Release\", \"content\": \"Released **${APP_NAME}** -- **${CURRENT_VERSION}** -> **${NEW_VERSION}**\n${CHANGES}\"}" \
  ${DISCORD_WEBHOOK_URL}
