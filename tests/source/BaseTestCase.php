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

use \PHPUnit_Framework_TestCase,
    \Mockery,
    \ReflectionClass;

class BaseTestCase extends PHPUnit_Framework_TestCase {

    /**
     * Closes Mockery if active
     */
    public function tearDown() {
        parent::tearDown();

        Mockery::close();
    }

    /**
     * Creates a mock class, registers it with the application, and returns it.
     */
    protected function getMockery() {
        return call_user_func_array([Mockery::class, 'mock'], func_get_args());
    }

    /**
     * Creates a mock class, registers it with the application, and returns it.
     */
    protected function getMockeryNamed() {
        return call_user_func_array([Mockery::class, 'namedMock'], func_get_args());
    }

    protected function getProtectedProperty($class, $property) {
        $reflection = new ReflectionClass(get_class($class));

        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        return $property->getValue($class);
    }

    protected function setProtectedProperty($class, $property, $value) {
        $reflection = new ReflectionClass(get_class($class));

        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($class, $value);
    }

    /**
     * Output a message to the command line during testing
     *
     * @param  string $message Message to display
     *
     * @example
     *
     * $this->outputMessage('Trigger A');
     *   -OR-
     * $this->outputMessage('-', true);
     */
    protected function outputMessage($message, $raw = false) {
        if(!$raw) {
            $message = "\n$message\n";
        }

        fwrite(STDOUT, $message);
    }

    /**
     * Output a time-test message to the command line during testing
     *
     * @param  string  $testName   Name of test
     * @param  integer $iterations Number of iterations
     * @param  float   $startTime  Unix time-stamp with microseconds when test started
     * @param  float   $endTime    Unix time-stamp with microseconds when test ended
     *
     * @example
     *
     * $before = microtime(true);
     * for($iteration = 0; $iteration < 100000; $iteration++) {
     *     ClassName::$functionName();
     * }
     * $after = microtime(true);
     * $this->outputTimeTestMessage($functionName, $iteration, $after - $before);
     */
    protected function outputTimeTestMessage($testName, $iteration, $duration) {
        $instance = $duration / $iterations;
        $this->outputMessage("$testName\t[$duration seconds / $iterations times]\t$instance seconds / iteration");
    }

}
