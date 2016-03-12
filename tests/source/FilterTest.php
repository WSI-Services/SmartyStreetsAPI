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
	\WSIServices\SmartyStreetsAPI\Filters;

class FiltersTest extends BaseTestCase {

	protected $filters;
	protected $stringOptions = [
		'min_length' => 1,
		'max_length' => 5
	];
	protected $integerOptions = [
		'min_range' => 1,
		'max_range' => 5
	];
	protected $booleanOptions = [
		'alternate' => true
	];

	public function setUp() {
		parent::setUp();

		$this->filters = $this->getMockery(
			Filters::class
		)->makePartial();
	}

	public function tearDown() {
		parent::tearDown();

		$this->filters = null;
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateField()
	 */
	public function testValidateFieldWithInvalidType() {
		$this->setExpectedException(
			'InvalidArgumentException',
			'Provided validation type `bad_type` unknown'
		);

		$this->filters->validateField('test', '123', 'bad_type', []);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateField()
	 */
	public function testValidateFieldWithStringNotString() {
		$this->setExpectedException(
			'UnexpectedValueException',
			'Field `test` is not a string'
		);

		$this->filters->validateField('test', 123, 'string', $this->stringOptions);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateField()
	 */
	public function testValidateFieldWithStringMinLength() {
		$this->setExpectedException(
			'LengthException',
			'Field `test` must be '.$this->stringOptions['min_length'].' characters or more'
		);

		$this->filters->validateField('test', '', 'string', $this->stringOptions);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateField()
	 */
	public function testValidateFieldWithStringMaxLength() {
		$this->setExpectedException(
			'LengthException',
			'Field `test` must be '.$this->stringOptions['max_length'].' characters or less'
		);

		$this->filters->validateField('test', '123456789', 'string', $this->stringOptions);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateField()
	 */
	public function testValidateFieldWithString() {
		$this->filters->validateField('test', '1', 'string', $this->stringOptions);
		$this->filters->validateField('test', '12345', 'string', $this->stringOptions);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateField()
	 */
	public function testValidateFieldWithIntegerNotInteger() {
		$this->setExpectedException(
			'UnexpectedValueException',
			'Field `test` is not a number'
		);

		$this->filters->validateField('test', 'abc', 'integer', $this->integerOptions);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateField()
	 */
	public function testValidateFieldWithIntegerMinRange() {
		$this->setExpectedException(
			'RangeException',
			'Field `test` must be '.$this->integerOptions['min_range'].' or higher'
		);

		$this->filters->validateField('test', 0, 'integer', $this->integerOptions);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateField()
	 */
	public function testValidateFieldWithIntegerMaxRange() {
		$this->setExpectedException(
			'RangeException',
			'Field `test` must be '.$this->integerOptions['max_range'].' or lower'
		);

		$this->filters->validateField('test', 6, 'integer', $this->integerOptions);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateField()
	 */
	public function testValidateFieldWithInteger() {
		$this->filters->validateField('test', 1, 'integer', $this->integerOptions);
		$this->filters->validateField('test', 5, 'integer', $this->integerOptions);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateField()
	 */
	public function testValidateFieldWithBooleanNotBoolean() {
		$this->setExpectedException(
			'UnexpectedValueException',
			'Field `test` is not boolean'
		);

		$this->filters->validateField('test', 'abc', 'boolean', $this->booleanOptions);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateField()
	 */
	public function testValidateFieldWithBooleanNotBooleanNoAlternateOption() {
		$this->setExpectedException(
			'UnexpectedValueException',
			'Field `test` is not boolean'
		);

		unset($this->booleanOptions['alternate']);

		$this->filters->validateField('test', 'true', 'boolean', $this->booleanOptions);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateField()
	 */
	public function testValidateFieldWithBoolean() {
		$this->filters->validateField('test', false, 'boolean', $this->booleanOptions);
		$this->filters->validateField('test', true, 'boolean', $this->booleanOptions);
		$this->filters->validateField('test', 0, 'boolean', $this->booleanOptions);
		$this->filters->validateField('test', 1, 'boolean', $this->booleanOptions);
		$this->filters->validateField('test', 'false', 'boolean', $this->booleanOptions);
		$this->filters->validateField('test', 'true', 'boolean', $this->booleanOptions);
		$this->filters->validateField('test', 'off', 'boolean', $this->booleanOptions);
		$this->filters->validateField('test', 'on', 'boolean', $this->booleanOptions);
		$this->filters->validateField('test', 'no', 'boolean', $this->booleanOptions);
		$this->filters->validateField('test', 'yes', 'boolean', $this->booleanOptions);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateFields()
	 */
	public function testValidateFieldsWithUnexpectedField() {
		$this->setExpectedException(
			'UnexpectedValueException',
			'Field `test` is not expected'
		);

		$fields = [];
		$values = [
			'test' => 'abc123'
		];

		$this->filters->validateFields($fields, $values);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateFields()
	 */
	public function testValidateFieldsWithMissingOptions() {
		$fields = [
			'test' => [
				'type' => 'string_size'
			]
		];
		$values = [
			'test' => 'abc123'
		];

		$this->filters->shouldReceive('validateField')
			->with('test', 'abc123', $fields['test']['type'], []);

		$this->filters->validateFields($fields, $values);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Filters::validateFields()
	 */
	public function testValidateFields() {
		$fields = [
			'test' => [
				'type' => 'string_size',
				'options' => [
					'min_length' => 3,
					'max_length' => 6
				],
			],
		];
		$values = [
			'test' => 'abc123'
		];

		$this->filters->shouldReceive('validateField')
			->with('test', 'abc123', $fields['test']['type'], $fields['test']['options']);

		$this->filters->validateFields($fields, $values);
	}

}