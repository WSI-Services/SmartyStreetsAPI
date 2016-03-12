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

use \ReflectionClass,
	\WSIServices\SmartyStreetsAPI\Configuration,
	\WSIServices\SmartyStreetsAPI\Exception\ServiceException,
	\WSIServices\SmartyStreetsAPI\Factory,
	\WSIServices\SmartyStreetsAPI\Filters,
	\WSIServices\SmartyStreetsAPI\Request\FileGetContents,
	\WSIServices\SmartyStreetsAPI\Service,
	\WSIServices\SmartyStreetsAPI\Tests\BaseTestCase;

class ServiceTest extends BaseTestCase {

	protected $service;
	protected $factory;
	protected $filters;

	public function setUp() {
		parent::setUp();

		$this->service = $this->getMockery(
			Service::class
		)->makePartial();

		$this->factory = $this->getMockery(
			Factory::class
		)->makePartial();

		$this->setProtectedProperty(
			$this->service,
			'factory',
			$this->factory
		);

		$this->filters = $this->getMockery(
			Filters::class
		)->makePartial();

		$this->setProtectedProperty(
			$this->service,
			'filters',
			$this->filters
		);

	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Service::__construct()
	 */
	public function testConstructWithNoFiltersClass() {
		$factory = $this->getMockery(Factory::class);

		$configuration = $this->getMockery(Configuration::class);

		$factory->shouldReceive('getConfiguration')
			->once()
			->andReturn($configuration);

		$configuration->shouldReceive('has')
			->once()
			->with('filters-class')
			->andReturn(false);

		$this->service->__construct($factory);

		$this->assertSame(
			$factory,
			$this->getProtectedProperty(
				$this->service,
				'factory'
			),
			'Factory not reset properly.'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Service::__construct()
	 */
	public function testConstructWithFiltersClass() {
		$factory = $this->getMockery(Factory::class);

		$configuration = $this->getMockery(Configuration::class);

		$factory->shouldReceive('getConfiguration')
			->once()
			->andReturn($configuration);

		$configuration->shouldReceive('has')
			->once()
			->with('filters-class')
			->andReturn(true);

		$configuration->shouldReceive('get')
			->once()
			->with('filters-class')
			->andReturn(Filters::class);

		$this->service->__construct($factory);

		$this->assertSame(
			$factory,
			$this->getProtectedProperty(
				$this->service,
				'factory'
			),
			'Factory not reset properly.'
		);

		$this->assertInstanceOf(
			Filters::class,
			$this->getProtectedProperty(
				$this->service,
				'filters'
			),
			'Filters not set correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Service::process()
	 */
	public function testProcessWithAllowMultiRequestFalse() {
		$fields = [
			'test' => 'abc'
		];
		$values = [
			'test' => '123'
		];

		$this->filters->shouldReceive('validateFields')
			->with($fields, $values)
			->once();

		$this->setProtectedProperty(
			$this->service,
			'fields',
			$fields
		);

		$this->assertSame(
			$this->service,
			$this->service->process($values),
			'Service did not return self'
		);

		$this->assertSame(
			[$values],
			$this->getProtectedProperty(
				$this->service,
				'processes'
			),
			'Process did not get set properly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Service::process()
	 */
	public function testProcessWithAllowMultiRequestTrue() {
		$fields = [
			'test' => 'abc'
		];
		$values = [
			'test' => '123'
		];

		$this->filters->shouldReceive('validateFields')
			->with($fields, $values)
			->once();

		$this->setProtectedProperty(
			$this->service,
			'fields',
			$fields
		);

		$this->setProtectedProperty(
			$this->service,
			'allowMultiRequest',
			true
		);

		$this->assertSame(
			$this->service,
			$this->service->process($values),
			'Service did not return self'
		);

		$this->assertSame(
			[$values],
			$this->getProtectedProperty(
				$this->service,
				'processes'
			),
			'Process did not get set properly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Service::process()
	 * @todo check code coverage on test, remove if not unique 
	 */
	public function testProcessWithFailedFilter() {
		$this->setExpectedException('InvalidArgumentException');

		$fields = [
			'test' => 'abc'
		];
		$values = [
			'test' => '123'
		];

		$this->setProtectedProperty(
			$this->service,
			'fields',
			$fields
		);

		$this->filters->shouldReceive('validateFields')
			->with($fields, $values)
			->andThrow('InvalidArgumentException', 'Provided validation type `type` unknown');

		$this->service->process($values);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Service::getRequest()
	 */
	public function testGetRequestWithNoRequestClassConfiguration() {
		$this->setExpectedException(
			ServiceException::class,
			'SmartyStreetsAPI: [Service] Configuration does not specify Request class'
		);

		$configuration = $this->getMockery(Configuration::class);

		$this->factory->shouldReceive('getConfiguration')
			->andReturn($configuration);

		$configuration->shouldReceive('has')
			->once()
			->with('request-class')
			->andReturn(false);

		$this->service->getRequest();
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Service::getRequest()
	 */
	public function testGetRequest() {
		$configuration = $this->getMockery(Configuration::class);

		$this->factory->shouldReceive('getConfiguration')
			->andReturn($configuration);

		$configuration->shouldReceive('has')
			->once()
			->with('request-class')
			->andReturn(true);

		$fileGetContents = $this->getMockery('overload:'.FileGetContents::class);

		$configuration->shouldReceive('get')
			->once()
			->with('request-class')
			->andReturn($fileGetContents);

		$hostName = 'api.smartystreets.com';

		$configuration->shouldReceive('get')
			->once()
			->with('endpoints')
			->andReturn($configuration);

		$configuration->shouldReceive('get')
			->once()
			->with(null)
			->andReturn($configuration);

		$configuration->shouldReceive('get')
			->once()
			->with('hostname')
			->andReturn($hostName);

		$fileGetContents->shouldReceive('setUrl')
			->once()
			->with('https://'.$hostName);

		$queryData = [
			'auth-id'		=> '',
			'auth-token'	=> ''
		];

		$configuration->shouldReceive('getRaw')
			->once()
			->with('authentication')
			->andReturn($queryData);

		$fileGetContents->shouldReceive('setQueryData')
			->once()
			->with($queryData);

		$this->service->getRequest();
	}

}