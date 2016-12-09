# Utility Library
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
#
# Include file:
# source path/to/utility.lib.sh
#
# This file can safely be sourced multiple times without causing any errors

declare -f -F "source_path" > /dev/null

if [[ $? -eq 1 ]]; then

	# Iteratively source a list of files
	function source_path {
		for FILE in $@; do
			source $FILE
		done
	}

	# Display a header message
	function display_header {
		local MESSAGE="$1"
		local PREFIX="${2:-$DISPLAY_HEADER_PREFIX}"
		local POSTFIX="${3:-$DISPLAY_HEADER_POSTFIX}"

		if [[ -n "$MESSAGE" ]]; then
			echo -e "${PREFIX}${MESSAGE}${POSTFIX}"
		fi
	}
	DISPLAY_HEADER_PREFIX="\n## "
	DISPLAY_HEADER_POSTFIX="\n"

	# Display a line of output and optional default value and return response
	function dialog_line_input {
		local DESCRIPTION="$1"
		local DEFAULT="$2"
		local ANSWER=""

		echo "$DESCRIPTION" >&2
		[[ -n "$DEFAULT" ]] &&
			[[ ! "$DEFAULT" == " " ]] &&
				echo -n "($DEFAULT)" >&2
		echo -n ": " >&2

		while read ANSWER; do
			if [[ -n "$ANSWER" ]]; then
				echo "$ANSWER"
				break
			elif [[ "$DEFAULT" == " " ]]; then
				break
			elif [[ -n "$DEFAULT" ]]; then
				echo "$DEFAULT"
				break
			else
				echo -en "\033[1A\033[K: " >&2
			fi
		done
	}

	# Add package source
	function source_install {
		local NAME="$1"
		local SOURCE="$2"
		local KEY="$3"

		display_header "Add repository '$NAME'..."

		if [[ -f "/etc/apt/sources.list.d/$NAME.list" ]]; then
			display_header "Repository '$NAME' already added."
		else
			echo "$SOURCE" > /etc/apt/sources.list.d/$NAME.list

			display_header "Adding '$NAME' keys..."
			apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv $KEY

			apt-get update

			display_header "Repository '$NAME' added."
		fi
	}

	# Install system package
	function package_install {
		local PACKAGE="$1"

		display_header "Install package '$PACKAGE'..."

		if [[ -n "$(dpkg-query -l | awk '{print $2}' | grep " $PACKAGE ")" ]]; then
			display_header "Package '$PACKAGE' already installed."
		else
			apt-get -y install $PACKAGE

			display_header "Package '$PACKAGE' installed."
		fi
	}

	function php_module_enable {
		local MODULE="$1"
		local PRIORITY="${2:-20}"
		local FILENAME="/etc/php5/mods-available/$MODULE.ini"

		display_header "Enabling '$MODULE' PHP module..."

		if [[ -f "$FILENAME" ]]; then
			display_header "PHP module '$MODULE' already enabled."
		else
			echo "; configuration for php $MODULE module" > $FILENAME
			echo "; priority=$PRIORITY" >> $FILENAME
			echo "extension=$MODULE.so" >> $FILENAME

			php5enmod $MODULE

			display_header "PHP module '$MODULE' enabled."
		fi
	}

	# Install and enable PECL modules
	function pecl_install {
		local EXTENSION="$1"
		local VERSION="$2"
		local SEEDER="${3:-\n}"

		display_header "Install PECL extension '$EXTENSION'..."

		if [[ -n "$(pecl list | awk '{print $1}' | grep "$EXTENSION")" ]]; then
			display_header "PECL extension '$EXTENSION' already installed."
		else
			if [[ -n "$VERSION" ]]; then
				EXTENSION="$EXTENSION-$VERSION"
			fi

			printf "$SEEDER" | pecl install -f $EXTENSION

			display_header "PECL extension '$EXTENSION' installed."
		fi
	}

	# Find location of system library
	function system_find_library {
		local LIBRARY="$1"

		echo "$(ldconfig -p | grep "$LIBRARY.so" | awk '{print $4}' | head -n1)"
	}

	# Compare Versions
	function version_compare {
		local VER1="$1"
		local CMP="$2"
		local VER2="$3"

		php -r "exit(version_compare('$VER1', '$VER2', strtolower('$CMP')) ? 0 : 1);"
	}

	## Temporary resources

	# Determine the path to the systems temporary directory
	if [[ ! -d "$TMPDIR" ]]; then
		if [[ -d "$TMP" ]]; then
			TMPDIR="$TMP"
		elif [[ -d /var/tmp ]]; then
			TMPDIR=/var/tmp
		elif [[ -d /tmp ]]; then
			TMPDIR=/tmp
		else
			TMPDIR="$(pwd)"
		fi
	fi

	# Generate a temporary directory to work in
	TMPDIR="$(mktemp -d --tmpdir="$TMPDIR" "utility-library.XXXXXXXX")"

	# Create a temporary file in the utility temporary directory
	function mktemp_file {
		local PATERN="${1:-XXXXXXXX}"

		echo "$(mktemp --tmpdir="$TMPDIR" "$PATERN")"
	}

	# Register trap cleanup process
	trap "rm -fr -- \"$TMPDIR\"" INT TERM HUP EXIT

fi
