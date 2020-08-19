#!/usr/bin/env bash

set -e
set -x

if [ -z "${APP_META_URL}" ] ; then
  echo "ENV: APP_META_URL is missing!"
  exit 1
fi

CURRENT_VERSION_PLAIN=`curl -s ${APP_META_URL} | grep -Eo '"version":"([0-9]+\.[0-9]+\.[0-9]+)"' | cut -d: -f2`
CURRENT_VERSION="${CURRENT_VERSION_PLAIN//\"}"

echo ${CURRENT_VERSION} > current_live_version