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

namespace WSIServices\SmartyStreetsAPI\Tests;

use \WSIServices\SmartyStreetsAPI\Tests\BaseTestCase,
	\WSIServices\SmartyStreetsAPI\Configuration,
	\WSIServices\SmartyStreetsAPI\Service\UsStreetAddress,
	\WSIServices\SmartyStreetsAPI\Factory,
	\Psr\Log\LoggerInterface;

class FactoryTest extends BaseTestCase {

	protected $className = Factory::class;

	protected $factory;
	protected $configuration;

	public function setUp() {
		parent::setUp();

		$this->factory = $this->getMockery(
			$this->className
		)->makePartial();

		$configuration = include(
			__DIR__.'/../../source/Support/Laravel/config/smartystreetsapi.php'
		);
		$configuration['authentication']['id'] = 'id';
		$configuration['authentication']['token'] = 'token';
		$configuration = [$configuration];

		$this->configuration = $this->getMockery(
			Configuration::class,
			$configuration
		)->makePartial();

		$this->setProtectedProperty(
			$this->factory,
			'configuration',
			$this->configuration
		);
	}

	public function tearDown() {
		parent::tearDown();

		$this->factory = null;
		$this->configuration = null;
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Factory::__construct()
	 */
	public function testConstruct() {
		$logger = $this->getMockery(
			LoggerInterface::class
		);

		$this->factory->shouldReceive('setLogger')
			->once()
			->with($logger);

		$this->factory->__construct($logger);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Factory::setConfiguration()
	 */
	public function testSetConfiguration() {
		$this->factory->setConfiguration($this->configuration);

		$this->assertSame(
			$this->configuration,
			$this->getProtectedProperty(
				$this->factory,
				'configuration'
			),
			'Method should have set configuration'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Factory::getConfiguration()
	 */
	public function testGetConfiguration() {
		$this->assertSame(
			$this->configuration,
			$this->factory->getConfiguration(),
			'Configuration not set properly.'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Factory::checkConfiguration()
	 */
	public function testCheckConfigurationWithMissingConfiguration() {
		$this->setExpectedException(
			'UnexpectedValueException',
			'Configuration is not set'
		);

		$this->setProtectedProperty(
			$this->factory,
			'configuration',
			[]
		);

		$this->factory->hasService('test');
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Factory::checkConfiguration()
	 */
	public function testCheckConfigurationWithMissingEndpoints() {
		$this->setExpectedException(
			'OutOfBoundsException',
			'Service endpoints have not been configured'
		);

		$this->configuration->remove('endpoints');

		$this->factory->hasService('test');
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Factory::checkConfiguration()
	 */
	public function testCheckConfiguration() {
		$this->factory->hasService('test');
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Factory::hasService()
	 */
	public function testHasServiceWithNonString() {
		$this->setExpectedException(
			'InvalidArgumentException',
			'Service endpoint name must be a string'
		);

		$this->factory->hasService(1234);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Factory::hasService()
	 */
	public function testHasServiceWithExistingEndpoint() {
		$this->configuration
			->get('endpoints')
			->set('test', 'abc123');

		$this->assertTrue(
			$this->factory->hasService('test'),
			'Service should not exist'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Factory::hasService()
	 */
	public function testHasServiceWithMissingEndpoint() {
		$this->assertFalse(
			$this->factory->hasService('test'),
			'Service should exist'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Factory::getService()
	 */
	public function testGetServiceWithMissingConfiguration() {
		$this->setExpectedException(
			'UnexpectedValueException',
			'Configuration is not set'
		);

		$this->setProtectedProperty(
			$this->factory,
			'configuration',
			[]
		);

		$this->factory->getService('test');
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Factory::getService()
	 */
	public function testGetServiceWithNonString() {
		$this->setExpectedException(
			'InvalidArgumentException',
			'Service endpoint name must be a string'
		);

		$this->factory->getService(1234);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Factory::getService()
	 */
	public function testGetServiceWithMissingEndpoint() {
		$this->setExpectedException(
			'UnexpectedValueException',
			'is not registered'
		);

		$this->factory->getService('test');
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Factory::getService()
	 */
	public function testGetServiceWithExistingEndpoint() {
		$this->configuration
			->get('endpoints')
			->set('test', ['model' => UsStreetAddress::class]);

		$service = $this->factory->getService('test');

		$this->assertInstanceOf(
			UsStreetAddress::class,
			$service,
			'Service not returned correctly'
		);

		$this->assertSame(
			$this->factory,
			$this->getProtectedProperty($service, 'factory'),
			'Factory not set correctly'
		);
	}

}