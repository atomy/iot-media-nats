#!/usr/bin/env bash

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
NEEDS_TAG=`git describe --contains $GIT_COMMIT 2>/dev/null || true`

# only tag if no tag already
if [ -z "$NEEDS_TAG" ]; then
    echo git tag $NEW_TAG
    echo "[auto-tag] Tagged with $NEW_TAG"
    git remote set-url origin git@github.com:atomy/iot-media-nats.git
    echo git push --tags
else
    echo "[auto-tag] Already a tag on this commit"
fi