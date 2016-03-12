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

namespace WSIServices\SmartyStreetsAPI\Service;

use \WSIServices\SmartyStreetsAPI\Service;

/**
* 
*/
class UsZipCode extends Service {

	protected $fields = [
		'input_id'				=> [
			'filter'			=> 'string',
			'options'			=> [
				'max_length'	=> 16
			]
		],
		'city'					=> [
			'filter'			=> 'string',
			'options'			=> [
				'max_length'	=> 64
			]
		],
		'state'					=> [
			'filter'			=> 'string',
			'options'			=> [
				'max_length'	=> 32
			]
		],
		'zipcode'				=> [
			'filter'			=> 'string',
			'options'			=> [
				'max_length'	=> 16
			]
		]
	];

	public function getRequest(array $options) {
	}

}