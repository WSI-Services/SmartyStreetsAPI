#!/usr/bin/env bash

REPO_DIR="$(readlink -f "$(dirname "${BASH_SOURCE[0]}")/..")"

"$REPO_DIR/vendor/bin/phpunit" -c "$REPO_DIR/phpunit.xml" $@