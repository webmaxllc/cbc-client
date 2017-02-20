#!/usr/bin/env bash

if type apigen > /dev/null; then
    apigen generate --source src --destination docs/api --title "VidVerify API Documentation" --todo --tree --download
    exit 0
fi

exit -1
