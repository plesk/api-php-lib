#!/bin/bash
### Copyright 1999-2024. WebPros International GmbH.

COUNTER=1

while : ; do
    curl -ksL https://plesk:8443/ | grep "<title>Plesk" > /dev/null
    [ $? -eq 0 ] && break
    echo "($COUNTER) Waiting for the Plesk initialization..."
    sleep 5
    COUNTER=$((COUNTER + 1))
    if [ $COUNTER -eq 60 ]; then
        echo "Too long, interrupting..."
        break
    fi
done
