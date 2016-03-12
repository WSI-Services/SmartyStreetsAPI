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
	\WSIServices\SmartyStreetsAPI\Configuration;

class ConfigurationTest extends BaseTestCase {

	protected $className = Configuration::class;

	protected $settings;
	protected $configuration;

	public function setUp() {
		parent::setUp();

		$this->settings = [
			'test-null' => null,
			'test-int' => 123,
			'test-string' => 'abc',
			'test-array' => [
				'test-null' => null,
				'test-int' => 123,
				'test-string' => 'abc',
			],
		];

		$this->configuration = $this->getMockery(
			Configuration::class
		)->makePartial();

		$this->setProtectedProperty(
			$this->configuration,
			'settings',
			$this->settings
		);
	}

	public function tearDown() {
		parent::tearDown();

		$this->configuration = null;
		$this->settings = null;
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::__construct()
	 */
	public function testConstruct() {
		$this->configuration->__construct($this->settings);

		$this->assertSame(
			$this->settings,
			$this->getProtectedProperty(
				$this->configuration,
				'settings'
			),
			'Settings not set properly.'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::getSettings()
	 */
	public function testGetSettings() {
		$this->assertSame(
			$this->settings,
			$this->configuration->getSettings(),
			'Settings not set properly.'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::has()
	 */
	public function testHasWithMissingSetting() {
		$this->assertFalse(
			$this->configuration->has('test'),
			'Setting did returned as existing'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::has()
	 */
	public function testHasWithExistingSetting() {
		$this->assertTrue(
			$this->configuration->has('test-null'),
			'Setting returned as missing'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::getRaw()
	 */
	public function testGetRawWithExistingSetting() {
		$this->assertSame(
			$this->settings['test-array'],
			$this->configuration->getRaw('test-array'),
			'Setting not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::getRaw()
	 */
	public function testGetRawWithMissingSetting() {
		$this->assertNull(
			$this->configuration->getRaw('test'),
			'Value not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::getRaw()
	 */
	public function testGetRawWithMissingSettingAndDefault() {
		$this->assertSame(
			'testing',
			$this->configuration->getRaw('test', 'testing'),
			'Default value not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::get()
	 */
	public function testGetWithExistingSetting() {
		$this->assertEquals(
			$this->settings['test-string'],
			$this->configuration->get('test-string'),
			'Setting not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::get()
	 */
	public function testGetWithExistingArraySetting() {
		$configuration = new Configuration($this->settings['test-array']);

		$this->assertEquals(
			$configuration,
			$this->configuration->get('test-array'),
			'Setting not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::get()
	 */
	public function testGetWithMissingSetting() {
		$this->assertNull(
			$this->configuration->get('test'),
			'Value not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::get()
	 */
	public function testGetWithMissingSettingAndDefault() {
		$this->assertSame(
			'testing',
			$this->configuration->get('test', 'testing'),
			'Default value not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::set()
	 */
	public function testSetWithArray() {
		$value = ['abc', 123];

		$this->configuration->set('test', $value);

		$settings = $this->getProtectedProperty(
			$this->configuration,
			'settings'
		);

		$this->assertArrayHasKey(
			'test',
			$settings,
			'Configuration key was no set'
		);

		$this->assertSame(
			$value,
			$settings['test'],
			'Configuration value was not set properly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::set()
	 */
	public function testSetWithConfiguration() {
		$value = ['abc', 123];
		$configuration = new Configuration($value);

		$this->assertSame(
			$this->configuration,
			$this->configuration->set('test', $configuration),
			'Set did not return self'
		);

		$settings = $this->getProtectedProperty(
			$this->configuration,
			'settings'
		);

		$this->assertArrayHasKey(
			'test',
			$settings,
			'Configuration key was not set'
		);

		$this->assertSame(
			$value,
			$settings['test'],
			'Configuration value was not set properly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::remove()
	 */
	public function testRemoveWithExistingKey() {
		$value = ['test' => ['abc', 123]];

		$this->setProtectedProperty(
			$this->configuration,
			'settings',
			$value
		);

		$this->assertSame(
			$this->configuration,
			$this->configuration->remove('test'),
			'Remove did not return self'
		);

		$this->assertArrayNotHasKey(
			'test',
			$this->getProtectedProperty(
				$this->configuration,
				'settings'
			),
			'Configuration value was not removed'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Configuration::remove()
	 */
	public function testRemoveWithMissingKey() {
		$this->setExpectedException(
			'UnexpectedValueException',
			'Configuration `test` is not set'
		);

		$this->configuration->remove('test');
	}

}