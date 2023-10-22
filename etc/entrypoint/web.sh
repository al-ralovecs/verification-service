#!/bin/sh
set -e

TARGET_UID=$(stat -c "%u" $APP_DIR)
TARGET_GID=$(stat -c "%g" $APP_DIR)

if [ $TARGET_UID != 0 ] || [ $TARGET_GID != 0 ]; then
    printf '%s\n' "[$(date +"%T")] Working around permission errors locally by making sure that \"${APP_USER_NAME}\" uses the same uid and gid as the host volume..." >&1

    if [ $TARGET_UID != 0 ]; then
        printf '%s\n' "[$(date +"%T")] Setting ${APP_USER_NAME} user to use uid $TARGET_UID..." >&1
        usermod -o -u "$TARGET_UID" "${APP_USER_NAME}" || true
    fi

    if [ $TARGET_GID != 0 ]; then
        printf '%s\n' "[$(date +"%T")] Setting ${APP_USER_NAME} group to use gid $TARGET_GID..." >&1
        groupmod -o -g "$TARGET_GID" "${APP_USER_NAME}" || true
    fi

    printf '%s\n' "[$(date +"%T")] Setting up vendors directory" >&1
    [ ! -d "${APP_DIR}/vendor" ] && mkdir -p "${APP_DIR}/vendor"

    # Change ownership of delegated folders
    printf '%s\n' "[$(date +"%T")] Changing ownership of delegated folders..." >&1
    chown $TARGET_UID:$TARGET_GID public vendor || true

    # Change ownership of delegated files
    printf '%s\n' "[$(date +"%T")] Changing ownership of delegated files..." >&1
    chown $TARGET_UID:$TARGET_GID composer.lock || true
fi

printf '%s\n' "[$(date +"%T")] Setting up log directory" >&1
[ ! -d "${APP_DIR}/var/log" ] && mkdir -p "${APP_DIR}/var/log"
chmod -R 0777 "${APP_DIR}/var/log"

printf '%s\n' "[$(date +"%T")] Setting up cache directory" >&1
[ ! -d "${APP_DIR}/var/cache/dev" ] && mkdir -p "${APP_DIR}/var/cache/dev"
chmod -R 0777 "${APP_DIR}/var/cache/dev"

printf '%s\n' "[$(date +"%T")] Building project" >&1
su-exec ${APP_USER_NAME} ./bin/build.sh

printf '%s\n' "[$(date +"%T")] Starting $@" >&1
exec "$@"
