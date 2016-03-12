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
	\WSIServices\SmartyStreetsAPI\Request\FileGetContents,
	\WSIServices\SmartyStreetsAPI\Tests\BaseTestCase,
	\stdClass;

class FileGetContentsTest extends BaseTestCase {

	protected $fileGetContents;

	public function setUp() {
		parent::setUp();

		$this->fileGetContents = $this->getMockery(
			FileGetContents::class
		)->makePartial();
	}

	public function tearDown() {
		parent::tearDown();

		$this->fileGetContents = null;
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\FileGetContents::send()
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

		$this->fileGetContents->shouldReceive('getMethod')
			->once()
			->andReturn($requestData['method']);

		$this->fileGetContents->shouldReceive('getHeaders')
			->once()
			->andReturn($requestData['headers']);

		$this->fileGetContents->shouldReceive('getUrl')
			->once()
			->andReturn($requestData['url']);

		$this->fileGetContents->shouldReceive('getQuery')
			->once()
			->andReturn($requestData['query']);

		$this->fileGetContents->shouldReceive('__fileGetContents')
			->once()
			->with(
				'https://'.$requestData['url'],
				[
					'method' => 'GET',
				]
			)
			->andReturn([
				'body' => 'body',
				'code' => 200
			]);

		$this->fileGetContents->send();

		$this->assertEquals(
			[
				'body' => 'body',
				'code' => 200
			],
			$this->getProtectedProperty(
				$this->fileGetContents,
				'response'
			),
			'FileGetContents response not set correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\FileGetContents::send()
	 */
	public function testSendWithMethodPostAndBodyAndHeadersAndQuery() {
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

		$this->fileGetContents->shouldReceive('getMethod')
			->once()
			->andReturn($requestData['method']);

		$this->fileGetContents->shouldReceive('getBody')
			->once()
			->andReturn($requestData['body']);

		$this->fileGetContents->shouldReceive('getHeaders')
			->once()
			->andReturn($requestData['headers']);

		$this->fileGetContents->shouldReceive('getUrl')
			->once()
			->andReturn($requestData['url']);

		$this->fileGetContents->shouldReceive('getQuery')
			->once()
			->andReturn($requestData['query']);

		$query = ($requestData['query'] ? '?'.http_build_query($requestData['query']) : '');

		$this->fileGetContents->shouldReceive('__fileGetContents')
			->once()
			->with(
				'https://'.$requestData['url'].$query,
				[
					'method'	=> 'POST',
					'content'	=> $requestData['body'],
					'headers'	=> $requestData['headers'],
				]
			)
			->andReturn([
				'body' => 'body',
				'code' => 200
			]);

		$this->fileGetContents->send();

		$this->assertEquals(
			[
				'body' => 'body',
				'code' => 200
			],
			$this->getProtectedProperty(
				$this->fileGetContents,
				'response'
			),
			'FileGetContants response not set correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\FileGetContents::getResponseCode()
	 */
	public function testGetResponseCode() {
		$responseCode = 200;

		$this->setProtectedProperty(
			$this->fileGetContents,
			'response',
			[
				'code' => $responseCode
			]
		);

		$this->assertEquals(
			$responseCode,
			$this->fileGetContents->getResponseCode(),
			'Response code not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\FileGetContents::getResponseBody()
	 */
	public function testGetResponseBody() {
		$responseBody = 'body';

		$this->setProtectedProperty(
			$this->fileGetContents,
			'response',
			[
				'body' => $responseBody
			]
		);

		$this->assertEquals(
			$responseBody,
			$this->fileGetContents->getResponseBody(),
			'Response body not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\FileGetContents::__fileGetContents()
	 */
	public function testFileGetContentsWithMethodGet() {
		$requestData = [
			'method' => 'GET',
			'url' => 'httpbin.org/get',
			'headers' => [
				'User-Agent' => 'SmartyStreetsAPI/'.Factory::VERSION,
			],
			'query' => [
				'show_env' => 1
			]
		];

		$query = ($requestData['query'] ? '?'.http_build_query($requestData['query']) : '');

		$url = 'https://'.$requestData['url'].$query;

		$options = [
			'method'	=> $requestData['method'],
			'headers'	=> $requestData['headers'],
		];

		$return = $this->fileGetContents->__fileGetContents($url, $options);

		$this->assertEquals(
			200,
			$return['code'],
			'Return code not correct'
		);

		// $this->assertEquals(
		// 	'',
		// 	$return['body'],
		// 	'Return body not correct'
		// );

		// $return['body'] = json_decode($return['body'], true);

		// $this->assertEquals(
		// 	[],
		// 	$return['body']['headers'],
		// 	'Return body not correct'
		// );
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\FileGetContents::__fileGetContents()
	 */
	public function testFileGetContentsWithMethodPost() {
		$requestData = [
			'body' => '{"test":true}',
			'method' => 'POST',
			'url' => 'httpbin.org/post',
			'headers' => [
				'User-Agent' => 'SmartyStreetsAPI/'.Factory::VERSION,
				'Content-Type' => 'application/x-www-form-urlencoded',
			],
			'query' => [
				'show_env' => 1
			]
		];

		$requestData['headers']['Content-Length'] = strlen($requestData['body']);

		$query = ($requestData['query'] ? '?'.http_build_query($requestData['query']) : '');

		$url = 'https://'.$requestData['url'].$query;

		$options = [
			'method'	=> $requestData['method'],
			'content'	=> $requestData['body'],
			'headers'	=> $requestData['headers'],
		];

		$return = $this->fileGetContents->__fileGetContents($url, $options);

		$this->assertEquals(
			200,
			$return['code'],
			'Return code not correct'
		);

		// $this->assertEquals(
		// 	'',
		// 	$return['body'],
		// 	'Return body not correct'
		// );

		// $return['body'] = json_decode($return['body'], true);

		// $this->assertEquals(
		// 	[],
		// 	$return['body']['headers'],
		// 	'Return body not correct'
		// );
	}
}