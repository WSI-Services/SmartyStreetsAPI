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

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.

echo "## Vagrant post install script..."

UTILITIES_DIR="/home/vagrant/SmartyStreetsAPI/utilities"

source $UTILITIES_DIR/utility.lib.sh
DISPLAY_HEADER_PREFIX="\n * "

$UTILITIES_DIR/install-system-packages.sh

$UTILITIES_DIR/install-pecl-modules.sh

sleep 1

display_header "Restarting service 'nginx'..."
service nginx restart

display_header "Restarting service 'php5-fpm'..."
service php5-fpm restart

display_header "Restarting service 'mysql'..."
service mysql restart

$UTILITIES_DIR/composer-vendors.sh --development

display_header "Project initialized."
