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

display_header "Update System Packages..."

apt-get update

display_header "Upgrade System Packages..."

apt-get upgrade

display_header "Install System Packages..."

package_install "php5-dev"

package_install "libcurl4-openssl-dev"

package_install "libmagic1"

package_install "zlib1g-dev"