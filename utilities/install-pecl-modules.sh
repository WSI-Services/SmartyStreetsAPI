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

display_header "Install PECL bindings..."

display_header "Update PECL protocols..."
pecl channel-update pecl.php.net

pecl_install "raphf"
php_module_enable "raphf"

pecl_install "propro"
php_module_enable "propro" "30"

PATH_ZLIB="$(system_find_library libz)"
PATH_LIBCURL="$(system_find_library libcurl)"
PATH_LIBEVENT="$(system_find_library libevent.*)"
PATH_LIBIDN="$(system_find_library libidn)"

pecl_install "pecl_http" "$PATH_ZLIB\n$PATH_LIBCURL\n$PATH_LIBEVENT\n$PATH_LIBIDN\n"
php_module_enable "http" "40"


