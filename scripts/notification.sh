#!/usr/bin/env bash

set -e

VERSION=`git describe --abbrev=0 --tags`

if [ -z "${DISCORD_WEBHOOK_URL}" ] ; then
  echo "ENV: DISCORD_WEBHOOK_URL is missing!"
  exit 1
fi

if [ -z "${APP_NAME}" ] ; then
  echo "ENV: APP_NAME is missing!"
  exit 1
fi

curl -X POST \
  -H "Content-Type: application/json" \
  -d "{\"username\": \"Jenkins-Release\", \"content\": \"**${APP_NAME}** -- **?** -> **${VERSION}**\n- awesome changes\"}" \
  ${DISCORD_WEBHOOK_URL}