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

use \GuzzleHttp\Client,
	\GuzzleHttp\Message\Request,
	\GuzzleHttp\Message\Response,
	\WSIServices\SmartyStreetsAPI\Factory,
	\WSIServices\SmartyStreetsAPI\Request\GuzzleHttp,
	\WSIServices\SmartyStreetsAPI\Tests\BaseTestCase;

class GuzzleHttpTest extends BaseTestCase {

	protected $guzzleHttp;

	public function setUp() {
		parent::setUp();

		$this->guzzleHttp = $this->getMockery(
			GuzzleHttp::class
		)->makePartial();
	}

	public function tearDown() {
		parent::tearDown();

		$this->guzzleHttp = null;
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\GuzzleHttp::send()
	 */
	public function testSendWithNoHeadersAndNullBodyAndNoQuery() {
		$requestData = [
			'body' => null,
			'method' => 'GET',
			'url' => 'api.smartystreets.com',
			'headers' => [
			],
			'query' => [
			],
		];

		$this->guzzleHttp->shouldReceive('getHeaders')
			->once()
			->andReturn($requestData['headers']);

		$this->guzzleHttp->shouldReceive('getBody')
			->once()
			->andReturn($requestData['body']);

		$this->guzzleHttp->shouldReceive('getQuery')
			->once()
			->andReturn($requestData['query']);

		$client = $this->getMockery(Client::class)
			->makePartial();

		$this->guzzleHttp->shouldReceive('__newClient')
			->once()
			->andReturn($client);

		$request = $this->getMockery(Request::class)
			->makePartial();

		$client->shouldReceive('createRequest')
			->once()
			->with(
				$requestData['method'],
				'https://'.$requestData['url'],
				[
					'timeout' => 2.0
				]
			)
			->andReturn($request);

		$this->guzzleHttp->shouldReceive('getMethod')
			->once()
			->andReturn($requestData['method']);

		$this->guzzleHttp->shouldReceive('getUrl')
			->once()
			->andReturn($requestData['url']);

		$response = $this->getMockery(Response::class)
			->makePartial();

		$client->shouldReceive('send')
			->once()
			->with($request)
			->andReturn($response);

		$this->guzzleHttp->send();

		$this->assertSame(
			$response,
			$this->getProtectedProperty(
				$this->guzzleHttp,
				'response'
			),
			'GuzzleHttp Client not set correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\GuzzleHttp::send()
	 */
	public function testSendWithHeadersAndBodyAndQuery() {
		$requestData = [
			'body' => '{test:true}',
			'method' => 'GET',
			'url' => 'api.smartystreets.com',
			'headers' => [
				'User-Agent' => 'SmartyStreetsAPI/'.Factory::VERSION,
			],
			'query' => [
				'q' => 'SmartyStreetsAPI'
			],
		];

		$this->guzzleHttp->shouldReceive('getHeaders')
			->twice()
			->andReturn($requestData['headers']);

		$this->guzzleHttp->shouldReceive('getBody')
			->twice()
			->andReturn($requestData['body']);

		$this->guzzleHttp->shouldReceive('getQuery')
			->twice()
			->andReturn($requestData['query']);

		$client = $this->getMockery(Client::class)
			->makePartial();

		$this->guzzleHttp->shouldReceive('__newClient')
			->once()
			->andReturn($client);

		$request = $this->getMockery(Request::class)
			->makePartial();

		$client->shouldReceive('createRequest')
			->once()
			->with(
				$requestData['method'],
				'https://'.$requestData['url'],
				[
					'timeout' => 2.0,
					'headers' => $requestData['headers'],
					'body' => $requestData['body'],
					'query' => $requestData['query'],
				]
			)
			->andReturn($request);

		$this->guzzleHttp->shouldReceive('getMethod')
			->once()
			->andReturn($requestData['method']);

		$this->guzzleHttp->shouldReceive('getUrl')
			->once()
			->andReturn($requestData['url']);

		$response = $this->getMockery(Response::class)
			->makePartial();

		$client->shouldReceive('send')
			->once()
			->with($request)
			->andReturn($response);

		$this->guzzleHttp->send();

		$this->assertSame(
			$response,
			$this->getProtectedProperty(
				$this->guzzleHttp,
				'response'
			),
			'GuzzleHttp Client not set correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\GuzzleHttp::getResponseCode()
	 */
	public function testGetResponseCode() {
		$responseCode = 200;

		$response = $this->getMockery(Response::class)
			->makePartial();

		$response->shouldReceive('getStatusCode')
			->once()
			->andReturn($responseCode);

		$this->setProtectedProperty(
			$this->guzzleHttp,
			'response',
			$response
		);

		$this->assertEquals(
			$responseCode,
			$this->guzzleHttp->getResponseCode(),
			'Response code not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\GuzzleHttp::getResponseBody()
	 */
	public function testGetResponseBody() {
		$responseBody = 'body';

		$response = $this->getMockery(Response::class)
			->makePartial();

		$response->shouldReceive('getBody->getContents')
			->once()
			->andReturn($responseBody);

		$this->setProtectedProperty(
			$this->guzzleHttp,
			'response',
			$response
		);

		$this->assertEquals(
			$responseBody,
			$this->guzzleHttp->getResponseBody(),
			'Response body not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\GuzzleHttp::__newClient()
	 */
	public function testNewClient() {
		$this->assertInstanceOf(
			Client::class,
			$this->guzzleHttp->__newClient(),
			'Returned incorrect instance for GuzzleHttp Client'
		);
	}
}