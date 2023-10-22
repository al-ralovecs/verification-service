#!/usr/bin/env bash

ENV_FILE_PATH="$(dirname "$0")/../.env"
DOCKER_COMPOSE_PATH="$(dirname "$0")/docker-compose.yml"

source "$ENV_FILE_PATH"
source "$(dirname "$0")/../bin/_helper.sh"

stopGotify() {
    if containerExists "$GOTIFY_CONTAINER_NAME" ; then
        infoMessage "[gotify] Stopping container..."
        docker compose --env-file "$ENV_FILE_PATH" -f "$DOCKER_COMPOSE_PATH" --profile=gotify down || true >/dev/null
    else
        infoMessage "[gotify] Container is not running"
    fi
}

startGotify() {
    if ! isContainerRunning "$GOTIFY_CONTAINER_NAME" ; then
        if ! isContainerRunning "$DEV_CONTAINER_NAME"; then
            exitWithErrorMessage "Dev container must be up and running"
        fi
        infoMessage "[gotify] Starting container..."
        COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker compose --env-file "$ENV_FILE_PATH" -f "$DOCKER_COMPOSE_PATH" --profile=gotify up -d
    else
        infoMessage "[gotify] Container is already up and running"
    fi
}

shift $(($OPTIND - 1))
COMMAND=$1

if [ -z "$COMMAND" ]; then
    COMMAND="help"
fi

case $COMMAND in
    up | start)
        startGotify
        ;;

    restart)
        $0 down && $0 up
        ;;

    down | stop)
        stopGotify
        ;;

    help)
        echo "Usage: make dev gotify [command] ...
Commands:
    up, start       Start Gotify container
    down, stop      Stop Gotify container
    restart         Restart Gotify container
"
        ;;

    *)
        exitWithErrorMessage "Invalid command. Valid values are up|start, down|stop, restart, help"
        ;;
esac