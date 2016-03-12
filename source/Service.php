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

use \WSIServices\SmartyStreetsAPI\Exception\ServiceException;

/**
* 
*/
abstract class Service implements ServiceInterface {

	protected $endpointName;
	protected $factory;
	protected $filters;
	protected $fields;
	protected $allowMultiRequest;
	protected $processes = [];

	public function __construct(Factory $factory) {
		$this->factory = $factory;

		$configuration = $this->factory->getConfiguration();

		if($configuration->has('filters-class')) {
			$filters = $configuration->get('filters-class');
			$this->filters = new $filters();
		}
	}

	public function process(array $values) {
		$this->filters->validateFields($this->fields, $values);

		if($this->allowMultiRequest) {
			$this->processes[] = $values;
		} else {
			$this->processes = [$values];
		}

		return $this;
	}

	public function getRequest(array $options = null) {
		$configuration = $this->factory->getConfiguration();

		if(!$configuration->has('request-class')) {
			throw new ServiceException('Configuration does not specify Request class');
		}

		$request = $configuration->get('request-class');
		$request = new $request();

		$request->setUrl(
			'https://'.
			$configuration->get('endpoints')
				->get($this->endpointName)
				->get('hostname')
		);

		$authentication = $configuration->getRaw('authentication');

		$request->setQueryData($authentication['secret']);

		return $request;
	}
}