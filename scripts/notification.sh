#!/usr/bin/env bash

set -e
set -x

NEW_VERSION=`git describe --abbrev=0 --tags`

if [ -z "${DISCORD_WEBHOOK_URL}" ] ; then
  echo "ENV: DISCORD_WEBHOOK_URL is missing!"
  exit 1
fi

if [ -z "${APP_NAME}" ] ; then
  echo "ENV: APP_NAME is missing!"
  exit 1
fi

CURRENT_VERSION=`cat current_live_version`

if [[ "" == "${CURRENT_VERSION}" ]]
then
  CURRENT_VERSION="?"

  echo curl -X POST \
    -H "Content-Type: application/json" \
    -d "{\"username\": \"Jenkins-Release\", \"content\": \"Released **${APP_NAME}** -- **${CURRENT_VERSION}** -> **${NEW_VERSION}**\nunable to determine changes\"}" \
    ${DISCORD_WEBHOOK_URL}
else
  CHANGES=`git log ${CURRENT_VERSION}..${NEW_VERSION} | sort | uniq`
  CHANGES=`echo ${CHANGES} | sed 's/\n/\n/'`

  echo curl -X POST \
    -H "Content-Type: application/json" \
    -d "{\"username\": \"Jenkins-Release\", \"content\": \"Released **${APP_NAME}** -- **${CURRENT_VERSION}** -> **${NEW_VERSION}**\n${CHANGES}\"}" \
    ${DISCORD_WEBHOOK_URL}
fi
