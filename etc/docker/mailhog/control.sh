#!/usr/bin/env bash

ENV_FILE_PATH="$(dirname "$0")/../.env"
DOCKER_COMPOSE_PATH="$(dirname "$0")/docker-compose.yml"

source "$ENV_FILE_PATH"
source "$(dirname "$0")/../bin/_helper.sh"

stopMailhog() {
    if containerExists "$MAILHOG_CONTAINER_NAME" ; then
        infoMessage "[mailhog] Stopping container..."
        docker compose --env-file "$ENV_FILE_PATH" -f "$DOCKER_COMPOSE_PATH" --profile=mailhog down || true >/dev/null
    else
        infoMessage "[mailhog] Container is not running"
    fi
}

startMailhog() {
    if ! isContainerRunning "$MAILHOG_CONTAINER_NAME" ; then
        if ! isContainerRunning "$DEV_CONTAINER_NAME"; then
            exitWithErrorMessage "Dev container must be up and running"
        fi
        infoMessage "[mailhog] Starting container..."
        COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker compose --env-file "$ENV_FILE_PATH" -f "$DOCKER_COMPOSE_PATH" --profile=mailhog up -d
    else
        infoMessage "[mailhog] Container is already up and running"
    fi
}

shift $(($OPTIND - 1))
COMMAND=$1

if [ -z "$COMMAND" ]; then
    COMMAND="help"
fi

case $COMMAND in
    up | start)
        startMailhog
        ;;

    restart)
        $0 down && $0 up
        ;;

    down | stop)
        stopMailhog
        ;;

    help)
        echo "Usage: make dev mailhog [command] ...
Commands:
    up, start       Start Mailhog container
    down, stop      Stop Mailhog container
    restart         Restart Mailhog container
"
        ;;

    *)
        exitWithErrorMessage "Invalid command. Valid values are up|start, down|stop, restart, help"
        ;;
esac