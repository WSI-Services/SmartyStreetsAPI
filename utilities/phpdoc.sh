#!/usr/bin/env bash

REPO_DIR="$(readlink -f "$(dirname "${BASH_SOURCE[0]}")/..")"

"$REPO_DIR/vendor/bin/phpdoc" -c "$REPO_DIR/phpdoc.dist.xml" $@