#!/usr/bin/env bash

ENV_FILE_PATH="$(dirname "$0")/../.env"
DOCKER_COMPOSE_PATH="$(dirname "$0")/docker-compose.yml"

source "$ENV_FILE_PATH"
source "$(dirname "$0")/../bin/_helper.sh"

stopPostgres() {
    if containerExists "$PG_CONTAINER_NAME" ; then
        infoMessage "[postgres] Stopping DB containers..."
        docker compose --env-file "$ENV_FILE_PATH" -f "$DOCKER_COMPOSE_PATH" --profile=postgres down || true >/dev/null
    else
        infoMessage "[postgres] DB container is not running"
    fi
}

startPostgres() {
    if ! isContainerRunning "$PG_CONTAINER_NAME" ; then
        if ! isContainerRunning "$DEV_CONTAINER_NAME"; then
            exitWithErrorMessage "Dev container must be up and running"
        fi
        infoMessage "[postgres] Starting DB container..."
        COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker compose --env-file "$ENV_FILE_PATH" -f "$DOCKER_COMPOSE_PATH" --profile=postgres up -d
    else
        infoMessage "[postgres] DB container is already up and running"
    fi
}

shift $(($OPTIND - 1))
COMMAND=$1

if [ -z "$COMMAND" ]; then
    COMMAND="help"
fi

case $COMMAND in
    up | start)
        startPostgres
        ;;

    restart)
        $0 down && $0 up
        ;;

    down | stop)
        stopPostgres
        ;;

    flush)
       flushDatabase
       ;;

    help)
        echo "Usage: make dev postgres [command] ...
Commands:
    up, start       Start Postgres container
    down, stop      Stop Postgres container
    restart         Restart Postgres container
"
        ;;

    *)
        exitWithErrorMessage "Invalid command. Valid values are up|start, down|stop, restart, flush, help"
        ;;
esac