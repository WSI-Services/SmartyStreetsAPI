#!/usr/bin/env bash
#
# Author: Sam Likins <sam.likins@wsi-services.com>
# Copyright: Copyright (c) 2015-2016, WSI-Services
# 
# License: http://opensource.org/licenses/gpl-3.0.html
# 
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>.

source "$(dirname "$(readlink -f "${BASH_SOURCE[0]}")")/utility.lib.sh"
DISPLAY_HEADER_PREFIX="\n * "

display_header "Install/Update vendors with Composer..."

PROJECT_DIR="$(dirname "$(dirname "$(readlink -f "${BASH_SOURCE[0]}")")")"
CURRENT_DIR="$(pwd)"

if [ "$1" != "--development" ]; then
	COMPOSER_COMMAND=" --no-dev --optimize-autoloader"
fi

if [ -f "$PROJECT_DIR/composer.lock" ]; then
	COMPOSER_COMMAND=" update$COMPOSER_COMMAND"
else
	COMPOSER_COMMAND" install$COMPOSER_COMMAND"
fi

cd $PROJECT_DIR

composer $COMPOSER_COMMAND

cd $CURRENT_DIR
