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

namespace {

use \Psr\Log\NullLogger;

class Log extends NullLogger {}

}

namespace WSIServices\SmartyStreetsAPI\Support\Laravel {

function config_path($file) {
 return $file;
}

}

namespace WSIServices\SmartyStreetsAPI\Tests\Support\Laravel {

use \WSIServices\SmartyStreetsAPI\Tests\BaseTestCase,
	\WSIServices\SmartyStreetsAPI\Support\Laravel\ServiceProvider,
	\WSIServices\SmartyStreetsAPI\Configuration,
	\WSIServices\SmartyStreetsAPI\Factory,
	\stdClass,
	\Closure,
	\Psr\Log\NullLogger as Log;

class ServiceProviderTest extends BaseTestCase {

	// public function setUp() {
	// 	parent::setUp();
	// }

	// public function tearDown() {
	// 	parent::tearDown();
	// }

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Support\Laravel\ServiceProvider::boot()
	 */
	public function testBoot() {
		$configPath = realpath(__DIR__.'/../../../..').'/source/Support/Laravel/config/smartystreetsapi.php';

		$config = $this->getMockery(stdClass::class);

		$config->shouldReceive('get')
			->once()
			->with('smartystreetsapi', [])
			->andReturn([]);

		$config->shouldReceive('set')
			->once()
			->with('smartystreetsapi', require $configPath);

		$provider = new ServiceProvider([
			'config' => $config
		]);

		$provider->boot();
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Support\Laravel\ServiceProvider::register()
	 */
	public function testRegister() {
		$app = $this->getMockery(stdClass::class);

		$app->shouldReceive('singleton')
			->once();

		$provider = new ServiceProvider($app);

		$provider->register();
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Support\Laravel\ServiceProvider::register()
	 */
	public function testRegisterSingleton() {
		$app = $this->getMockery(stdClass::class);

		$singleton = '';

		$app->shouldReceive('singleton')
			->andReturnUsing(
				function($class, \Closure $closure) use(&$singleton) {
					$singleton = $closure;

					return $class === Factory::class;
				}
			);

		$provider = new ServiceProvider($app);

		$provider->register();

		$app = [
			'config' => [
				'smartystreetsapi' => [
					'test-string' => 'abc',
					'test-array' => [
						'test-int' => 123,
					],
				],
			],
		];

		$factory = $singleton($app);

		$this->assertInstanceOf(
			Factory::class,
			$factory,
			'Factory not created correctly'
		);

		$configuration = $factory->getConfiguration();

		$this->assertInstanceOf(
			Configuration::class,
			$configuration,
			'Configuration not created correctly'
		);

		$settings = $configuration->getSettings();

		$this->assertEquals(
			$app['config']['smartystreetsapi'],
			$settings,
			'Settings not set correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Support\Laravel\ServiceProvider::provides()
	 */
	public function testProvides() {
		$provider = new ServiceProvider([]);

		$this->assertEquals(
			['smartystreetsapi'],
			$provider->provides(),
			'Provides should have returned the correct services.'
		);
	}

}

}