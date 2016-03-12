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

namespace WSIServices\SmartyStreetsAPI\Support\Laravel;

use \Illuminate\Support\ServiceProvider as BaseServiceProvider,
	\WSIServices\SmartyStreetsAPI\Configuration,
	\WSIServices\SmartyStreetsAPI\Factory,
	\Log;

class ServiceProvider extends BaseServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot() {
		$config = realpath(__DIR__.'/config/smartystreetsapi.php');

		$this->publishes([
			$config => config_path('smartystreetsapi.php')
		]);

		$this->mergeConfigFrom($config, 'smartystreetsapi');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->singleton(
			Factory::class,
			function($app) {
				$factory = new Factory(new Log);

				$configuration = new Configuration($app['config']['smartystreetsapi']);

				$factory->setConfiguration($configuration);

				return $factory;
			}
		);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return ['smartystreetsapi'];
	}

}