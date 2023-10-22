#!/usr/bin/env bash

COLOR_RESET="\x1b[0m"
FG_BLACK="\x1b[30m"
FG_RED="\x1b[31m"
FG_GREEN="\x1b[32m"
FG_YELLOW="\x1b[33m"
BG_RED="\x1b[41m"
BG_GREEN="\x1b[42m"
BG_YELLOW="\x1b[43m"

infoMessage() {
    printf "${FG_YELLOW}%s${COLOR_RESET} %s\n" "[$(date +"%T")]" "$1" >&1
}

debugMessage() {
    infoMessage "[Debug] $1"
}

warningMessage() {
    local _message="[Warning] $1"
    local _placeholder="$(echo "$_message" | sed 's/./ /g' )"

    printf "\n${BG_YELLOW}  %s  ${COLOR_RESET}\n" "${_placeholder}" >&1
    printf "${BG_YELLOW}${FG_BLACK}  %s  ${COLOR_RESET}\n" "${_message}" >&1
    printf "${BG_YELLOW}  %s  ${COLOR_RESET}\n\n" "${_placeholder}" >&1
}

successMessage() {
    local _message="[OK] $1"
    local _placeholder="$(echo "$_message" | sed 's/./ /g' )"

    printf "\n${BG_GREEN}  %s  ${COLOR_RESET}\n" "${_placeholder}" >&1
    printf "${BG_GREEN}${FG_BLACK}  %s  ${COLOR_RESET}\n" "${_message}" >&1
    printf "${BG_GREEN}  %s  ${COLOR_RESET}\n\n" "${_placeholder}" >&1
}

errorMessage() {
    local _message="[Error] $1"
    local _placeholder="$(echo "$_message" | sed 's/./ /g' )"

    printf "\n${BG_RED}  %s  ${COLOR_RESET}\n" "${_placeholder}" >&2
    printf "${BG_RED}  %s  ${COLOR_RESET}\n" "${_message}" >&2
    printf "${BG_RED}  %s  ${COLOR_RESET}\n\n" "${_placeholder}" >&2
}

exitWithErrorMessage() {
    errorMessage "$1"
    exit 1
}

containerId() {
    local _container_name=$1
    local _container_id

    echo "$(docker ps -aqf "name=$_container_name")"
}

containerExists() {
    local _container_name=$1
    local _container_id
    _container_id=$(containerId "$_container_name")

    if [ -z "$_container_id" ]; then
        false
    else
        true
    fi
}

dependencyExists() {
    local _dependency=$1

    if echo ",$INFRASTRUCTURE_COMPONENTS," | grep -q ",$_dependency,"; then
        true
    else
        false
    fi
}

isContainerRunning() {
    local _container_name=$1
    local _container_id
    _container_id=$(containerId "$_container_name")

    if [ -z "$_container_id" ]; then
        false
    else
         local _container_status
         _container_status=$(docker inspect --format="{{ .State.Running }}" "$_container_id" 2>/dev/null)
         if [ $? -ne 1 ]; then
             if [ "$_container_status" == "true" ]; then
                 true
             else
                 false
             fi
         else
             false
         fi
    fi
}