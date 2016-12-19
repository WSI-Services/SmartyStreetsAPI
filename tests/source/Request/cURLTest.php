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

namespace WSIServices\SmartyStreetsAPI\Tests\Request;

use \WSIServices\SmartyStreetsAPI\Factory,
	\WSIServices\SmartyStreetsAPI\Request\cURL,
	\WSIServices\SmartyStreetsAPI\Tests\BaseTestCase,
	\stdClass;

class cURLTest extends BaseTestCase {

	protected $curl;

	public function setUp() {
		parent::setUp();

		$this->curl = $this->getMockery(
			cURL::class
		)->makePartial();
	}

	public function tearDown() {
		parent::tearDown();

		$this->curl = null;
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\cURL::getHeaders()
	 */
	public function testGetHeaders() {
		$startHeaders = [
			'test1' => 'abc',
			'test2' => 123
		];

		$this->setProtectedProperty(
			$this->curl,
			'headers',
			$startHeaders
		);

		$endHeaders = [
			'test1: abc',
			'test2: 123'
		];

		$this->assertSame(
			$endHeaders,
			$this->curl->getHeaders(),
			'Headers not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\cURL::send()
	 */
	public function testSendWithMethodGetAndNoHeadersAndNoQuery() {
		$requestData = [
			'body' => null,
			'method' => 'GET',
			'url' => 'api.smartystreets.com',
			'headers' => [
			],
			'query' => [
			]
		];

		$this->curl->shouldReceive('getMethod')
			->once()
			->andReturn($requestData['method']);

		$this->curl->shouldReceive('getHeaders')
			->once()
			->andReturn($requestData['headers']);

		$this->curl->shouldReceive('getUrl')
			->once()
			->andReturn($requestData['url']);

		$this->curl->shouldReceive('getQuery')
			->once()
			->andReturn($requestData['query']);

		$this->curl->shouldReceive('__curl')
			->once()
			->with(
				[
					CURLOPT_RETURNTRANSFER	=> true,
					CURLOPT_HEADER			=> false,
					CURLOPT_VERBOSE			=> 0,
					CURLOPT_URL				=> 'https://'.$requestData['url']
				]
			)
			->andReturn([
				'body' => 'body',
				'code' => 200
			]);

		$this->curl->send();

		$this->assertEquals(
			[
				'body' => 'body',
				'code' => 200
			],
			$this->getProtectedProperty(
				$this->curl,
				'response'
			),
			'cURL response not set correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\cURL::send()
	 */
	public function testSendWithHeadersAndBodyAndQuery() {
		$requestData = [
			'body' => '{test:true}',
			'method' => 'POST',
			'url' => 'api.smartystreets.com',
			'headers' => [
				'User-Agent' => 'SmartyStreetsAPI/'.Factory::VERSION
			],
			'query' => [
				'q' => 'SmartyStreetsAPI'
			]
		];

		$this->curl->shouldReceive('getMethod')
			->once()
			->andReturn($requestData['method']);

		$this->curl->shouldReceive('getBody')
			->once()
			->andReturn($requestData['body']);

		$this->curl->shouldReceive('getHeaders')
			->once()
			->andReturn($requestData['headers']);

		$this->curl->shouldReceive('getUrl')
			->once()
			->andReturn($requestData['url']);

		$this->curl->shouldReceive('getQuery')
			->once()
			->andReturn($requestData['query']);

		$query = ($requestData['query'] ? '?'.http_build_query($requestData['query']) : '');

		$this->curl->shouldReceive('__curl')
			->once()
			->with(
				[
					CURLOPT_RETURNTRANSFER	=> true,
					CURLOPT_HEADER			=> false,
					CURLOPT_VERBOSE			=> 0,
					CURLOPT_POST			=> true,
					CURLOPT_POSTFIELDS		=> $requestData['body'],
					CURLOPT_HTTPHEADER		=> $requestData['headers'],
					CURLOPT_URL				=> 'https://'.$requestData['url'].$query
				]
			)
			->andReturn([
				'body' => 'body',
				'code' => 200
			]);

		$this->curl->send();

		$this->assertEquals(
			[
				'body' => 'body',
				'code' => 200
			],
			$this->getProtectedProperty(
				$this->curl,
				'response'
			),
			'cURL response not set correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\cURL::getResponseCode()
	 */
	public function testGetResponseCode() {
		$responseCode = 200;

		$this->setProtectedProperty(
			$this->curl,
			'response',
			[
				'code' => $responseCode
			]
		);

		$this->assertEquals(
			$responseCode,
			$this->curl->getResponseCode(),
			'Response code not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\cURL::getResponseBody()
	 */
	public function testGetResponseBody() {
		$responseBody = 'body';

		$this->setProtectedProperty(
			$this->curl,
			'response',
			[
				'body' => $responseBody
			]
		);

		$this->assertEquals(
			$responseBody,
			$this->curl->getResponseBody(),
			'Response body not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\cURL::__curl()
	 */
	public function testCurlWithMethodGet() {
		$requestData = [
			'url' => 'api.smartystreets.com',
			'headers' => [
				'User-Agent' => 'SmartyStreetsAPI/'.Factory::VERSION
			],
			'query' => [
				'q' => 'SmartyStreetsAPI'
			]
		];

		$query = ($requestData['query'] ? '?'.http_build_query($requestData['query']) : '');

		$options = [
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER			=> false,
			CURLOPT_VERBOSE			=> 0,
			CURLOPT_HTTPHEADER		=> $requestData['headers'],
			CURLOPT_URL				=> 'https://'.$requestData['url'].$query
		];

		$return = $this->curl->__curl($options);

		$this->assertEquals(
			200,
			$return['code'],
			'Return code not correct'
		);

		$this->assertContains(
			'OK',
			$return['body'],
			'Return body not correct'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\cURL::__curl()
	 */
	public function testCurlWithMethodPost() {
		$requestData = [
			'body' => '{test:true}',
			'method' => 'POST',
			'url' => 'us-zipcode.api.smartystreets.com/lookup',
			'headers' => [
				'User-Agent' => 'SmartyStreetsAPI/'.Factory::VERSION
			]
		];

		$options = [
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER			=> false,
			CURLOPT_VERBOSE			=> 0,
			CURLOPT_POST			=> true,
			CURLOPT_POSTFIELDS		=> $requestData['body'],
			CURLOPT_HTTPHEADER		=> $requestData['headers'],
			CURLOPT_URL				=> 'https://'.$requestData['url']
		];

		$return = $this->curl->__curl($options);

		$this->assertEquals(
			401,
			$return['code'],
			'Return code not correct'
		);

		$this->assertEquals(
			'Unauthorized'.PHP_EOL,
			$return['body'],
			'Return body not correct'
		);
	}
}