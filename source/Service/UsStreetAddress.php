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
class UsStreetAddress extends Service {

	protected $endpointName = 'us-street-address';

	protected $fields = [
		'input_id'				=> [
			'filter'			=> 'string',
			'options'			=> [
				'max_length'	=> 16
			]
		],
		'street'				=> [
			'filter'			=> 'string',
			'options'			=> [
				'max_length'	=> 64
			]
		],
		'street2'				=> [
			'filter'			=> 'string',
			'options'			=> [
				'max_length'	=> 64
			]
		],
		'secondary'				=> [
			'filter'			=> 'string',
			'options'			=> [
				'max_length'	=> 32
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
		],
		'lastline'				=> [
			'filter'			=> 'string',
			'options'			=> [
				'max_length'	=> 64
			]
		],
		'addressee'				=> [
			'filter'			=> 'string',
			'options'			=> [
				'max_length'	=> 64
			]
		],
		'urbanization'			=> [
			'filter'			=> 'string',
			'options'			=> [
				'max_length'	=> 64
			]
		],
		'candidates'			=> [
			'filter'			=> 'integer',
			'options'			=> [
				'min_range'		=> 1,
				'max_range'		=> 10
			]
		]
	];

	protected $allowMultiRequest = true;

	protected $xStandardizeOnly = false;

	protected $xIncludeInvalid = false;

	public function XStandardizeOnly($set = true) {
		$this->xStandardizeOnly = $set;

		return $this;
	}

	public function XIncludeInvalid($set = true) {
		$this->xIncludeInvalid = $set;

		return $this;
	}

	public function getRequest(array $options) {
		$configuration = $this->factory->getConfiguration();

		$path = $configuration->get('endpoints')
			->get('us-street-address')
			->getRaw('path');

		$request = parent::getRequest();

		$request->setUrl($request->getUrl().$path[0]);

		if(count($this->processes) > 1) {
			$request->setMethod($request::METHOD_POST);

			$request->setContentType('Content-Type: application/json');

			$request->setBody(json_encode($this->processes));
		} else {
			foreach ($this->processes[0] as $key => $value) {
				$request->addQueryData([urlencode($key) => urlencode($value)]);
			}

			$request->setMethod($request::METHOD_GET);
		}

		if($this->xStandardizeOnly) {
			$request->addHeaders(['X-Standardize-Only' => 'true']);
		}

		if($this->xIncludeInvalid) {
			$request->addHeaders(['X-Include-Invalid' => 'true']);
		}

		return $request;
	}

}