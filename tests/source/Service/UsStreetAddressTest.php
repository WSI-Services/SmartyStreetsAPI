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

namespace WSIServices\SmartyStreetsAPI\Tests\Service;

use \WSIServices\SmartyStreetsAPI\Tests\BaseTestCase,
	\WSIServices\SmartyStreetsAPI\Service\UsStreetAddress;

class UsStreetAddressTest extends BaseTestCase {

	protected $usStreetAddress;

	public function setUp() {
		parent::setUp();

		$this->usStreetAddress = $this->getMockery(
			UsStreetAddress::class
		)->makePartial();
	}

	public function tearDown() {
		parent::tearDown();

		$this->usStreetAddress = null;
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Service\UsStreetAddress::XStandardizeOnly()
	 */
	public function testXStandardizeOnly() {
		$this->assertFalse(
			$this->getProtectedProperty(
				$this->usStreetAddress,
				'xStandardizeOnly'
			),
			'X-Standardize-Only started out true'
		);

		$this->assertSame(
			$this->usStreetAddress,
			$this->usStreetAddress->XStandardizeOnly(),
			'Did not return self'
		);

		$this->assertTrue(
			$this->getProtectedProperty(
				$this->usStreetAddress,
				'xStandardizeOnly'
			),
			'X-Standardize-Only did not get set correctly'
		);

		$this->assertSame(
			$this->usStreetAddress,
			$this->usStreetAddress->XStandardizeOnly(false),
			'Did not return self'
		);

		$this->assertFalse(
			$this->getProtectedProperty(
				$this->usStreetAddress,
				'xStandardizeOnly'
			),
			'X-Standardize-Only did not get set correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Service\UsStreetAddress::XIncludeInvalid()
	 */
	public function testXIncludeInvalid() {
		$this->assertFalse(
			$this->getProtectedProperty(
				$this->usStreetAddress,
				'xIncludeInvalid'
			),
			'X-Include-Invalid started out true'
		);

		$this->assertSame(
			$this->usStreetAddress,
			$this->usStreetAddress->XIncludeInvalid(),
			'Did not return self'
		);

		$this->assertTrue(
			$this->getProtectedProperty(
				$this->usStreetAddress,
				'xIncludeInvalid'
			),
			'X-Include-Invalid did not get set correctly'
		);

		$this->assertSame(
			$this->usStreetAddress,
			$this->usStreetAddress->XIncludeInvalid(false),
			'Did not return self'
		);

		$this->assertFalse(
			$this->getProtectedProperty(
				$this->usStreetAddress,
				'xIncludeInvalid'
			),
			'X-Include-Invalid did not get set correctly'
		);
	}

}