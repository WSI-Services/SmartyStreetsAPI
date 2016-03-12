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

namespace WSIServices\SmartyStreetsAPI\Tests\Support\Laravel;

use \WSIServices\SmartyStreetsAPI\Tests\BaseTestCase,
	\WSIServices\SmartyStreetsAPI\Support\Laravel\Facade,
	\ReflectionMethod;

class FacadeTest extends BaseTestCase {

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Support\Laravel\Facade::getFacadeAccessor()
	 */
	public function testGetFacadeAccessor() {
		$reflectionMethod = new ReflectionMethod(Facade::class, 'getFacadeAccessor');
		$reflectionMethod->setAccessible(true);

		$facade = new Facade();

		$this->assertEquals(
			'smartystreetsapi',
			$reflectionMethod->invoke($facade),
			'Method should return correct facade name.'
		);
	}

}