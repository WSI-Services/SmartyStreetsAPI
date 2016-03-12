#!/usr/bin/env bash

REPO_DIR="$(readlink -f "$(dirname "${BASH_SOURCE[0]}")/..")"

"$REPO_DIR/vendor/bin/phploc" --progress --count-tests --exclude="vendor" $@ "$REPO_DIR/"