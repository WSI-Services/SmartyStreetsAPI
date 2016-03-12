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

use \InvalidArgumentException,
	\UnexpectedValueException,
	\LengthException,
	\RangeException;

/**
* 
*/
class Filters {

	public function validateField($field, $value, $type, $options) {
		switch ($type) {
			case 'string':
				if(!is_string($value)) {
					throw new UnexpectedValueException('Field `'.$field.'` is not a string');
				} elseif(array_key_exists('min_length', $options) && strlen($value) < $options['min_length']) {
					throw new LengthException('Field `'.$field.'` must be '.$options['min_length'].' characters or more');
				} elseif(array_key_exists('max_length', $options) && strlen($value) > $options['max_length']) {
					throw new LengthException('Field `'.$field.'` must be '.$options['max_length'].' characters or less');
				}
				break;

			case 'integer':
				if(!is_numeric($value)) {
					throw new UnexpectedValueException('Field `'.$field.'` is not a number');
				} elseif(array_key_exists('min_range', $options) && $value < $options['min_range']) {
					throw new RangeException('Field `'.$field.'` must be '.$options['min_range'].' or higher');
				} elseif(array_key_exists('max_range', $options) && $value > $options['max_range']) {
					throw new RangeException('Field `'.$field.'` must be '.$options['max_range'].' or lower');
				}
				break;

			case 'boolean':
				if(!(is_bool($value) || (
					array_key_exists('alternate', $options) &&
					$options['alternate'] && (
						(is_integer($value) &&
							in_array($value,
								[0, 1]
							)
						) || (is_string($value) &&
							in_array(strtolower($value),
								['false', 'true', 'off', 'on', 'no', 'yes']
							)
						)
					)
				))) {
					throw new UnexpectedValueException('Field `'.$field.'` is not boolean');
				}
				break;

			default:
				throw new InvalidArgumentException('Provided validation type `'.$type.'` unknown');
				break;
		}
	}

	public function validateFields($fields, $values) {
		foreach($values as $name => $value) {
			if(!array_key_exists($name, $fields)) {
				throw new UnexpectedValueException('Field `'.$name.'` is not expected');
			}

			if(!array_key_exists('options', $fields[$name])) {
				$fields[$name]['options'] = [];
			}

			$this->validateField($name, $value, $fields[$name]['type'], $fields[$name]['options']);
		}
	}

}