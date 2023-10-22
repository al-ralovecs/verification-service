#!/usr/bin/env bash

source "$(dirname "$0")/_helper.sh"

shift $(($OPTIND - 1))
REQUIRED_DOCKER_VERSION=$1

if [ -z "$REQUIRED_DOCKER_VERSION" ]; then
    REQUIRED_DOCKER_VERSION="20.10"
fi

REQUIRED_DOCKER_VERSION_MAJOR=$(echo "$REQUIRED_DOCKER_VERSION"| cut -d'.' -f 1)
REQUIRED_DOCKER_VERSION_MINOR=$(echo "$REQUIRED_DOCKER_VERSION"| cut -d'.' -f 2)
DOCKER_VERSION=$(docker version -f "{{.Server.Version}}")
DOCKER_VERSION_MAJOR=$(echo "$DOCKER_VERSION"| cut -d'.' -f 1)
DOCKER_VERSION_MINOR=$(echo "$DOCKER_VERSION"| cut -d'.' -f 2)

if [[ "${DOCKER_VERSION_MAJOR}" -lt "${REQUIRED_DOCKER_VERSION_MAJOR}" || ("${DOCKER_VERSION_MAJOR}" -eq "${REQUIRED_DOCKER_VERSION_MAJOR}" && "$((10#$DOCKER_VERSION_MINOR))" -lt "$((10#$REQUIRED_DOCKER_VERSION_MINOR))") ]]; then
    exitWithErrorMessage "[ERROR] Docker version less than ${REQUIRED_DOCKER_VERSION}. Current version is ${DOCKER_VERSION}"
else
    successMessage "Current Docker version $DOCKER_VERSION meets requirements"
fi