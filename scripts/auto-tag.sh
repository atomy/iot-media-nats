#!/usr/bin/env bash

set -e
set -x

#get highest tag number
VERSION=`git describe --abbrev=0 --tags`

#replace . with space so can split into an array
VERSION_BITS=(${VERSION//./ })

# get number parts and increase last one by 1
VNUM1=${VERSION_BITS[0]}
VNUM2=${VERSION_BITS[1]}
VNUM3=${VERSION_BITS[2]}
VNUM3=$((VNUM3+1))

# create new tag
NEW_TAG="$VNUM1.$VNUM2.$VNUM3"

echo "[auto-tag] Increasing \"$VERSION\" to \"$NEW_TAG\""

# get current hash and see if it already has a tag
GIT_COMMIT=`git rev-parse HEAD`
NEEDS_TAG=`git describe --contains $GIT_COMMIT 2>/dev/null`

# only tag if no tag already
if [ -z "$NEEDS_TAG" ]; then
    git tag $NEW_TAG
    git remote remove tagstream || true
    git remote add tagstream git@github.com:atomy/iot-media-api.git
    echo "[auto-tag] Tagged with $NEW_TAG"
    git push tagstream --tags
else
    echo "[auto-tag] Already a tag on this commit"
fi