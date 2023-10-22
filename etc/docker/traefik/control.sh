#!/usr/bin/env bash

ENV_FILE_PATH="$(dirname "$0")/../.env"
DOCKER_COMPOSE_PATH="$(dirname "$0")/docker-compose.yml"

source "$ENV_FILE_PATH"
source "$(dirname "$0")/../bin/_helper.sh"

stopTraefik() {
    if containerExists "$TRAEFIK_CONTAINER_NAME" ; then
        infoMessage "[traefik] Stopping container..."
        docker compose --env-file "$ENV_FILE_PATH" -f "$DOCKER_COMPOSE_PATH" --profile=traefik down || true >/dev/null
    else
        infoMessage "[traefik] Container is not running"
    fi
}

startTraefik() {
    if ! isContainerRunning "$TRAEFIK_CONTAINER_NAME" ; then
        if ! isContainerRunning "$DEV_CONTAINER_NAME"; then
            exitWithErrorMessage "Dev container must be up and running"
        fi
        infoMessage "[traefik] Starting container..."
        COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker compose --env-file "$ENV_FILE_PATH" -f "$DOCKER_COMPOSE_PATH" --profile=traefik up -d
    else
        infoMessage "[traefik] Container is already up and running"
    fi
}

shift $(($OPTIND - 1))
COMMAND=$1

if [ -z "$COMMAND" ]; then
    COMMAND="help"
fi

case $COMMAND in
    up | start)
        startTraefik
        ;;

    restart)
        $0 down && $0 up
        ;;

    down | stop)
        stopTraefik
        ;;

    help)
        echo "Usage: make dev traefik [command] ...
Commands:
    up, start       Start Traefik container
    down, stop      Stop Traefik container
    restart         Restart Traefik container
"
        ;;

    *)
        exitWithErrorMessage "Invalid command. Valid values are up|start, down|stop, restart, help"
        ;;
esac