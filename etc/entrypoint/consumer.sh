#!/bin/sh
set -e

APP_AVAILABLE_TRANSPORTS="verification_created notification_dispatch send_email_message send_mobile_message"
CONSUMER_TEMPLATE_PATH="/etc/supervisor/conf.d/consumer.conf.template"
CONSUMER_FAILED_TEMPLATE_PATH="/etc/supervisor/conf.d/consumer-failed.conf.template"
CONSUMER_MANAGER_TEMPLATE_PATH="/etc/supervisor/conf.d/consumer-manager.conf.template"

setupConsumer() {
    local _transport=$1
    local _outputPath="/etc/supervisor/conf.d/${_transport}-consumer.conf"
    if [ -f "$CONSUMER_TEMPLATE_PATH" ]; then
        printf '%s\n' "[$(date +"%T")] Setting up ${APP_CONSUMER_COUNT:-1} consumer(-s) for \"$_transport\" transport"
        APP_CONSUMER_TRANSPORT="$_transport" \
        APP_CONSUMER_COUNT=${APP_CONSUMER_COUNT:-1} \
        APP_CONSUMER_TIME_LIMIT=${APP_CONSUMER_TIME_LIMIT:-3600} \
        APP_CONSUMER_MEMORY_LIMIT=${APP_CONSUMER_MEMORY_LIMIT:-128M} \
        envsubst '$$APP_CONSUMER_TRANSPORT $$APP_CONSUMER_COUNT $$APP_PARALLEL_PROCESS_COUNT $$APP_CONSUMER_TIME_LIMIT $$APP_CONSUMER_MEMORY_LIMIT' < "$CONSUMER_TEMPLATE_PATH" > "$_outputPath"
        printf '%s\n' "[$(date +"%T")] Supervisor for ${_transport} consumer config:" >&1
        printf '%s\n' "$(cat "$_outputPath")" >&1
    else
        printf '%s\n' "[$(date +"%T")] [Warning] Probably consumer for \"$_transport\" transport has already been set up"
    fi
}

setupFailedConsumer() {
    local _transport=$1
    local _outputPath="/etc/supervisor/conf.d/${_transport}-consumer-failed.conf"
    if [ -f "$CONSUMER_FAILED_TEMPLATE_PATH" ]; then
        printf '%s\n' "[$(date +"%T")] Setting up ${APP_CONSUMER_FAILURE_PROCESS_COUNT:-1} consumer(-s) for \"$_transport\" transport"
        APP_CONSUMER_FAILURE_TRANSPORT="$_transport" \
        APP_CONSUMER_FAILURE_PROCESS_COUNT=${APP_CONSUMER_FAILURE_PROCESS_COUNT:-1} \
        APP_CONSUMER_FAILURE_TIME_LIMIT=${APP_CONSUMER_FAILURE_TIME_LIMIT:-60} \
        APP_CONSUMER_FAILURE_MEMORY_LIMIT=${APP_CONSUMER_FAILURE_MEMORY_LIMIT:-128M} \
        APP_CONSUMER_FAILURE_INTERVAL=${APP_CONSUMER_FAILURE_INTERVAL:-4m} \
        envsubst '$$APP_CONSUMER_FAILURE_TRANSPORT $$APP_CONSUMER_FAILURE_PROCESS_COUNT $$APP_CONSUMER_FAILURE_TIME_LIMIT $$APP_CONSUMER_FAILURE_MEMORY_LIMIT $$APP_CONSUMER_FAILURE_INTERVAL' < "$CONSUMER_FAILED_TEMPLATE_PATH" > "$_outputPath"
        printf '%s\n' "[$(date +"%T")] Supervisor for ${_transport} consumer config:" >&1
        printf '%s\n' "$(cat "$_outputPath")" >&1
    else
        printf '%s\n' "[$(date +"%T")] [Warning] Probably consumer for \"$_transport\" transport has already been set up"
    fi
}

setupConsumerManager() {
    local _transport=$1
    local _outputPath="/etc/supervisor/conf.d/${_transport}-consumer-manager.conf"
    if [ -f "$CONSUMER_MANAGER_TEMPLATE_PATH" ]; then
        printf '%s\n' "[$(date +"%T")] Setting up ${APP_CONSUMER_MANAGER_COUNT:-1} consumer manager(-s) for \"$_transport\" transport"
        APP_CONSUMER_TRANSPORT="$_transport" \
        APP_CONSUMER_MANAGER_TIME_LIMIT=${APP_CONSUMER_MANAGER_TIME_LIMIT:-3600} \
        APP_CONSUMER_MANAGER_MEMORY_LIMIT=${APP_CONSUMER_MANAGER_MEMORY_LIMIT:-128} \
        APP_CONSUMERS_PER_MANAGER=${APP_CONSUMERS_PER_MANAGER:-10} \
        APP_CONSUMER_MANAGER_COUNT=${APP_CONSUMER_MANAGER_COUNT:-1} \
        envsubst '$$APP_CONSUMER_TRANSPORT $$APP_CONSUMER_MANAGER_TIME_LIMIT $$APP_CONSUMER_MANAGER_MEMORY_LIMIT $$APP_CONSUMERS_PER_MANAGER $$APP_CONSUMER_MANAGER_COUNT' < "$CONSUMER_MANAGER_TEMPLATE_PATH" > "$_outputPath"
        printf '%s\n' "[$(date +"%T")] Supervisor for ${_transport} consumer manager config:" >&1
        printf '%s\n' "$(cat "$_outputPath")" >&1
    else
        printf '%s\n' "[$(date +"%T")] [Warning] Probably consumer manager for \"$_transport\" transport has already been set up"
    fi
}

cleanup() {
    rm -rf "$CONSUMER_TEMPLATE_PATH"
    rm -rf "$CONSUMER_FAILED_TEMPLATE_PATH"
    rm -rf "$CONSUMER_MANAGER_TEMPLATE_PATH"
}

{
    if [ -z "$APP_CONSUMER_TRANSPORT" ] && [ -z "$APP_CONSUMER_FAILURE_TRANSPORT" ] && [ -z "$APP_CONSUMER_TRANSPORT_MANAGER" ]; then
        # Include all transports to be consumed by symfony messenger
        for transport in $(echo "$APP_AVAILABLE_TRANSPORTS" | sed 's/ /\n/g' | sort | uniq); do
            setupConsumer "$transport"
        done
    elif [ -n "$APP_CONSUMER_TRANSPORT" ]; then
        # Include specific transports to be consumed by symfony messenger
        for transport in $(echo "$APP_AVAILABLE_TRANSPORTS" "$APP_CONSUMER_TRANSPORT" | sed 's/ /\n/g' | sort | uniq -d); do
            setupConsumer "$transport"
        done
    fi

    if [ -n "$APP_CONSUMER_FAILURE_TRANSPORT" ]; then
        # Setup failure transport consumer
        for transport in $(echo "$APP_AVAILABLE_TRANSPORTS" "$APP_CONSUMER_FAILURE_TRANSPORT" | sed 's/ /\n/g' | sort | uniq -d); do
            setupFailedConsumer "${transport}_failures"
        done
    fi

    if [ -n "$APP_CONSUMER_TRANSPORT_MANAGER" ]; then
        # Setup transport consumer manager
        for transport in $(echo "$APP_AVAILABLE_TRANSPORTS" "$APP_CONSUMER_TRANSPORT_MANAGER" | sed 's/ /\n/g' | sort | uniq -d); do
            setupConsumerManager "$transport"
        done
    fi

    cleanup
} || {
    printf '%s\n' "[$(date +"%T")] [ERROR] Failed to setup messenger consumer"
    exit 1
}

printf '%s\n' "[$(date +"%T")] Setting up log directory" >&1
[ ! -d "${APP_DIR}/var/log" ] && mkdir -p "${APP_DIR}/var/log"
chmod -R 0777 "${APP_DIR}/var/log"

printf '%s\n' "[$(date +"%T")] Starting $@"
exec "$@"
