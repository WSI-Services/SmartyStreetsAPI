<?php
/**
 * Author: Sam Likins <sam.likins@wsi-services.com>
 * Copyright: Copyright (c) 2015-2016, WSI-Services
 * 
 * License: http://opensource.org/licenses/gpl-3.0.html
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace WSIServices\SmartyStreetsAPI;

use \ArrayObject,
	\UnexpectedValueException;

/**
* 
*/
class Configuration extends ArrayObject {

	/**
	 * Stored settings
	 *
	 * @var array
	 */
	protected $settings = [];

	/**
	 * Constructor
	 *
	 * @param array $settings
	 */
	public function __construct(array &$settings) {
		$this->settings =& $settings;
	}

	public function &getSettings() {
		return $this->settings;
	}

	/**
	 * Check if an configuration setting exists by key
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function has($key) {
		return array_key_exists($key, $this->settings);
	}

	/**
	 * Get a configuration setting
	 *
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed Configuration setting or default when not found
	 */
	public function getRaw($key, $default = null) {
		if(!array_key_exists($key, $this->settings)) {
			return $default;
		}

		return $this->settings[$key];
	}

	/**
	 * Get a configuration setting
	 *
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed Configuration setting or default when not found, if value is an array wrap in class
	 */
	public function get($key, $default = null) {
		if(!array_key_exists($key, $this->settings)) {
			return $default;
		} elseif(is_array($this->settings[$key])) {
			return new self($this->settings[$key]);
		}

		return $this->settings[$key];
	}

	/**
	 * Set a configuration setting.
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return $this
	 */
	public function set($key, $value) {
		if($value instanceOf self) {
			$this->settings[$key] =& $value->getSettings();
		} else {
			$this->settings[$key] = $value;
		}

		return $this;
	}

	/**
	 * Remove a configuration setting.
	 *
	 * @param string $key
	 *
	 * @return $this
	 */
	public function remove($key) {
		if(!array_key_exists($key, $this->settings)) {
			throw new UnexpectedValueException('Configuration `'.$key.'` is not set');
		}

		unset($this->settings[$key]);

		return $this;
	}

}