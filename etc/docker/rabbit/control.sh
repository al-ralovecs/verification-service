#!/usr/bin/env bash

ENV_FILE_PATH="$(dirname "$0")/../.env"
DOCKER_COMPOSE_PATH="$(dirname "$0")/docker-compose.yml"

source "$ENV_FILE_PATH"
source "$(dirname "$0")/../bin/_helper.sh"

stopRabbit() {
    if containerExists "$RABBIT_CONTAINER_NAME" ; then
        infoMessage "[rabbit] Stopping container..."
        docker compose --env-file "$ENV_FILE_PATH" -f "$DOCKER_COMPOSE_PATH" --profile=rabbit down || true >/dev/null
    else
        infoMessage "[rabbit] Container is not running"
    fi
}

startRabbit() {
    if ! isContainerRunning "$RABBIT_CONTAINER_NAME" ; then
        if ! isContainerRunning "$DEV_CONTAINER_NAME"; then
            exitWithErrorMessage "Dev container must be up and running"
        fi
        infoMessage "[rabbit] Starting container..."
        COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker compose --env-file "$ENV_FILE_PATH" -f "$DOCKER_COMPOSE_PATH" --profile=rabbit up -d
    else
        infoMessage "[rabbit] Container is already up and running"
    fi
}

shift $(($OPTIND - 1))
COMMAND=$1

if [ -z "$COMMAND" ]; then
    COMMAND="help"
fi

case $COMMAND in
    up | start)
        startRabbit
        ;;

    restart)
        $0 down && $0 up
        ;;

    down | stop)
        stopRabbit
        ;;

    help)
        echo "Usage: make dev rabbit [command] ...
Commands:
    up, start       Start Rabbit container
    down, stop      Stop Rabbit container
    restart         Restart Rabbit container
"
        ;;

    *)
        exitWithErrorMessage "Invalid command. Valid values are up|start, down|stop, restart, help"
        ;;
esac