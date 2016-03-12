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

use \WSIServices\SmartyStreetsAPI\Tests\BaseTestCase,
	\WSIServices\SmartyStreetsAPI\Request;

class RequestTest extends BaseTestCase {

	protected $request;

	public function setUp() {
		parent::setUp();

		$this->request = $this->getMockery(
			Request::class
		)->makePartial();
	}

	public function tearDown() {
		parent::tearDown();

		$this->request = null;
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::setUrl()
	 */
	public function testSetUrl() {
		$url = 'http://test/url';

		$this->request->setUrl($url);

		$this->assertSame(
			$url,
			$this->getProtectedProperty(
				$this->request,
				'url'
			),
			'URL not set correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::getUrl()
	 */
	public function testGetUrl() {
		$url = 'http://test/url';

		$this->setProtectedProperty(
			$this->request,
			'url',
			$url
		);

		$this->assertSame(
			$url,
			$this->request->getUrl(),
			'URL not set correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::addQuery()
	 */
	public function testAddQueryWithNoneSet() {
		$query = [
			'test1' => 'a b c &',
			'test2' => 123
		];

		$this->request->addQuery($query);

		$this->assertSame(
			$query,
			$this->getProtectedProperty(
				$this->request,
				'query'
			),
			'Query not added correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::addQuery()
	 */
	public function testAddQueryWithPreviousSetAndNoMatch() {
		$previousQuery = [
			'test0' => 'test'
		];

		$this->setProtectedProperty(
			$this->request,
			'query',
			$previousQuery
		);

		$query = [
			'test1' => 'a b c &',
			'test2' => 123
		];

		$this->request->addQuery($query);

		$this->assertSame(
			[
				'test0' => $previousQuery['test0'],
				'test1' => $query['test1'],
				'test2' => $query['test2'],
			],
			$this->getProtectedProperty(
				$this->request,
				'query'
			),
			'Query not added correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::addQuery()
	 */
	public function testAddQueryWithPreviousSetAndMatch() {
		$previousQuery = [
			'test1' => 'test'
		];

		$this->setProtectedProperty(
			$this->request,
			'query',
			$previousQuery
		);

		$query = [
			'test1' => 'a b c &',
			'test2' => 123
		];

		$this->request->addQuery($query);

		$this->assertSame(
			$query,
			$this->getProtectedProperty(
				$this->request,
				'query'
			),
			'Query not added correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::setQuery()
	 */
	public function testSetQuery() {
		$previousQuery = [
			'test0' => 'test'
		];

		$this->setProtectedProperty(
			$this->request,
			'query',
			$previousQuery
		);

		$query = [
			'test1' => 'a b c &',
			'test2' => 123
		];

		$this->request->setQuery($query);

		$this->assertSame(
			$query,
			$this->getProtectedProperty(
				$this->request,
				'query'
			),
			'Query not set correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::getQuery()
	 */
	public function testGetQuery() {
		$query = [
			'test1' => 'a b c &',
			'test2' => 123
		];

		$this->setProtectedProperty(
			$this->request,
			'query',
			$query
		);

		$this->assertSame(
			$query,
			$this->request->getQuery(),
			'Query not returned correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::addHeaders()
	 */
	public function testAddHeadersWithNoneSet() {
		$headers = [
			'test1' => 'abc',
			'test2' => 123
		];

		$this->setProtectedProperty(
			$this->request,
			'headers',
			[]
		);

		$this->request->addHeaders($headers);

		$this->assertSame(
			$headers,
			$this->getProtectedProperty(
				$this->request,
				'headers'
			),
			'Headers not added correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::addHeaders()
	 */
	public function testAddHeadersWithPreviousSetAndNoMatch() {
		$previousHeaders = [
			'test0' => 'test'
		];

		$this->setProtectedProperty(
			$this->request,
			'headers',
			$previousHeaders
		);

		$headers = [
			'test1' => 'abc',
			'test2' => 123
		];

		$this->request->addHeaders($headers);

		$this->assertSame(
			[
				'test0' => $previousHeaders['test0'],
				'test1' => $headers['test1'],
				'test2' => $headers['test2'],
			],
			$this->getProtectedProperty(
				$this->request,
				'headers'
			),
			'Headers not added correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::addHeaders()
	 */
	public function testAddHeadersWithPreviousSetAndMatch() {
		$previousHeaders = [
			'test1' => 'test'
		];

		$this->setProtectedProperty(
			$this->request,
			'headers',
			$previousHeaders
		);

		$headers = [
			'test1' => 'a b c &',
			'test2' => 123
		];

		$this->request->addHeaders($headers);

		$this->assertSame(
			$headers,
			$this->getProtectedProperty(
				$this->request,
				'headers'
			),
			'Headers not added correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::setHeaders()
	 */
	public function testSetHeaders() {
		$previousHeaders = [
			'test0' => 'test'
		];

		$this->setProtectedProperty(
			$this->request,
			'headers',
			$previousHeaders
		);

		$headers = [
			'test1' => 'abc',
			'test2' => 123
		];

		$this->request->setHeaders($headers);

		$this->assertSame(
			$headers,
			$this->getProtectedProperty(
				$this->request,
				'headers'
			),
			'Headers not set correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::getHeaders()
	 */
	public function testGetHeaders() {
		$headers = [
			'test1' => 'abc',
			'test2' => 123
		];

		$this->setProtectedProperty(
			$this->request,
			'headers',
			$headers
		);

		$this->assertSame(
			$headers,
			$this->request->getHeaders(),
			'Headers not returned correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::setContentType()
	 */
	public function testSetContentType() {
		$contentType = 'test';

		$this->request->setContentType($contentType);

		$this->assertSame(
			$contentType,
			$this->getProtectedProperty(
				$this->request,
				'contentType'
			),
			'Content Type not set correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::getContentType()
	 */
	public function testGetContentType() {
		$contentType = 'test';

		$this->setProtectedProperty(
			$this->request,
			'contentType',
			$contentType
		);

		$this->assertSame(
			$contentType,
			$this->request->getContentType(),
			'Content Type not returned correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::setMethod()
	 */
	public function testSetMethod() {
		$method = 'test';

		$this->request->setMethod($method);

		$this->assertSame(
			$method,
			$this->getProtectedProperty(
				$this->request,
				'method'
			),
			'Method not set correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::getMethod()
	 */
	public function testGetMethod() {
		$method = 'test';

		$this->setProtectedProperty(
			$this->request,
			'method',
			$method
		);

		$this->assertSame(
			$method,
			$this->request->getMethod(),
			'Method not returned correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::setBody()
	 */
	public function testSetBody() {
		$body = 'test';

		$this->request->setBody($body);

		$this->assertSame(
			$body,
			$this->getProtectedProperty(
				$this->request,
				'body'
			),
			'Body not set correctly'
		);
	}

	/**
	 * @covers \WSIServices\SmartyStreetsAPI\Request::getBody()
	 */
	public function testGetBody() {
		$body = 'test';

		$this->setProtectedProperty(
			$this->request,
			'body',
			$body
		);

		$this->assertSame(
			$body,
			$this->request->getBody(),
			'Body not returned correctly'
		);
	}
}