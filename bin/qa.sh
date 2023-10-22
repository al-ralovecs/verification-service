#!/bin/bash

BUILD_NUMBER=${BUILD_NUMBER:-$(date +%s)}
ENV_FILE_PATH="$(dirname "$0")/../etc/docker/.env.ci"
DOCKER_COMPOSE_PATH="$(dirname "$0")/../etc/docker/docker-compose.ci.yml"

source "$(dirname "$0")/../etc/docker/bin/_helper.sh"

source "$ENV_FILE_PATH"

cleanUp() {
    local _prefix=$1

    infoMessage "Cleaning up..."
    docker ps -a | grep "$_prefix" | awk '{print $1}' | xargs docker stop >/dev/null || true
    docker ps -a | grep "$_prefix" | awk '{print $1}' | xargs docker rm -v >/dev/null || true
    docker image ls | grep "$_prefix" | awk '{print $1}' | xargs docker rmi >/dev/null || true
    docker network ls | grep "$_prefix" | awk '{print $1}' | xargs docker network rm >/dev/null || true
}

runCi() {
    local _buildNumber=$1
    local _phpVersion=$2

    {
        infoMessage "Running CI for PHP ${_phpVersion}"
        COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 BUILD_NUMBER="$_buildNumber" \
            docker compose --env-file "$ENV_FILE_PATH" -f "$DOCKER_COMPOSE_PATH" build --build-arg PHP_VERSION="$_phpVersion" ci \
            && BUILD_NUMBER="$_buildNumber" docker compose --env-file "$ENV_FILE_PATH" -f "$DOCKER_COMPOSE_PATH" run --rm --name "${CI_CONTAINER_NAME}" ci;

        if [ $? -eq 0 ]; then
            successMessage "CI for PHP ${_phpVersion} passed"
            return 0
        else
            errorMessage "CI for PHP ${_phpVersion} failed"
            return 1
        fi
    } || {
        errorMessage "CI for PHP ${_phpVersion} failed"
        return 1
    }
}

shift $(($OPTIND - 1))
COMMAND=$1

if [ -z "$COMMAND" ]; then
    COMMAND="test"
fi

PHP_VERSIONS=$(echo "${2:-$APPLICABLE_PHP_VERSIONS}" "$APPLICABLE_PHP_VERSIONS" | sed 's/ /\n/g' | sort | uniq -d)
if [ -z "$PHP_VERSIONS" ]; then
    exitWithErrorMessage "${2} is not supported PHP version"
fi

case $COMMAND in
    ci | test)
        for phpVersion in $PHP_VERSIONS; do
            runCi "${BUILD_NUMBER}" "$phpVersion"
            EXIT_CODE=$?
            cleanUp "${COMPOSE_PROJECT_NAME}"
            if [ $EXIT_CODE -ne 0 ]; then
                exit "$EXIT_CODE"
            fi
        done
        exit 0
        ;;

    cleanup | clean)
        cleanUp "${2-$COMPOSE_PROJECT_NAME}"
        ;;

    *)
        exitWithErrorMessage "Invalid command. Valid values are test, clean"
        ;;
esac