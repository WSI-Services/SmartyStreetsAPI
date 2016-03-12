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

use	\WSIServices\SmartyStreetsAPI\Configuration,
	\Psr\Log\LoggerAwareInterface,
	\Psr\Log\LoggerAwareTrait,
	\Psr\Log\LoggerInterface,
	\InvalidArgumentException,
	\OutOfBoundsException,
	\UnexpectedValueException;

/**
 * 
 */
class Factory implements LoggerAwareInterface{

	CONST VERSION = '0.1.0';

	use LoggerAwareTrait;

	/**
	 * Configuration settings
	 * @var \WSIServices\SmartyStreetsAPI\Configuration
	 */
	protected $configuration;

	public function __construct(LoggerInterface $logger = null) {
		$this->setLogger($logger);
	}

	public function setConfiguration(Configuration $configuration) {
		$this->configuration = $configuration;

		return $this;
	}

	public function getConfiguration() {
		return $this->configuration;
	}

	protected function checkConfiguration() {
		if(!$this->configuration) {
			throw new UnexpectedValueException('Configuration is not set');
		} elseif(!$this->configuration->has('endpoints')) {
			throw new OutOfBoundsException('Service endpoints have not been configured');
		}
	}

	public function hasService($endpoint) {
		$this->checkConfiguration();

		if(!is_string($endpoint)) {
			throw new InvalidArgumentException('Service endpoint name must be a string');
		}

		return $this->configuration
			->get('endpoints')
			->has($endpoint);
	}

	public function getService($endpoint) {
		$this->checkConfiguration();

		if(!is_string($endpoint)) {
			throw new InvalidArgumentException('Service endpoint name must be a string');
		}

		$endpoints = $this->configuration->get('endpoints');

		if(!$endpoints->has($endpoint)) {
			throw new UnexpectedValueException('Service endpoint `'.$endpoint.'` is not registered');
		}

		$serviceModel = $endpoints->get($endpoint)
			->get('model');

		$serviceModel = new $serviceModel($this);

		return $serviceModel;
	}

}