Build Status: [![Build Status](https://travis-ci.org/WSI-Services/SmartyStreetsAPI.svg?branch=master)](https://travis-ci.org/WSI-Services/SmartyStreetsAPI)
![Powered by PHP](http://php.net/images/logos/php-power-micro.png)
[![Released under the GPL-3 License](http://www.gnu.org/graphics/gplv3-88x31.png "GPL-3 License")](https://www.tldrlegal.com/l/gpl-3.0)


### Table of Contents
* [WSI-Services SmartyStreetsAPI](#wsiservicessmartystreetsapi)
* [About SmartyStreets](#aboutsmartystreets)
* [Installing SmartyStreetsAPI](#installingsmartystreetsapi)
    * [GIT](#git)
    * [Composer](#composer)
    * [Dependencies](#dependencies)
* [Using SmartyStreetsAPI](#usingsmartystreetsapi)
	* [Framework Support](#frameworksupport)
		* [Laravel](#laravel)
    * [Namespace](#namespace)
    * [Class: Configuration](#classconfiguration)
    * [Class: Factory](#classfactory)
    * [Class: Service](#classservice)
    	* [US Street Address Service](#usstreetaddressservice)
		* [Download Service](#downloadservice)
    * [Class: Request](#classrequest)
* [Utilities](#utilities)
    * [phpUnit](#phpunit)
        * [Code Coverage](#codecoverage)
    * [phploc](#phploc)
    * [phpDocumentor](#phpdocumentor)
* [SmartyStreets Notes](#smartystreetsnotes)
	* [US Street Address API](#usstreetaddressapi)
	* [US ZIP Code API](#uszipcodeapi)
	* [US Autocomplete API](#usautocompleteapi)
	* [US Extract API](#usextractapi)
	* [Verify International Addresses](#verifyinternationaladdresses)
	* [Download API](#downloadapi)

WSI-Services SmartyStreetsAPI
====
PHP library for parsing and validating street addresses against SmartyStreets
API.  An API ID and Token from SmartyStreets is required to utilize this
library.  You can create a [SmartyStreets](https://smartystreets.com) account
& obtain an API key by [creating an account](https://smartystreets.com/signup),
then [generate a Secret key](https://smartystreets.com/account#keys).


About SmartyStreets
====
<img style="float:left; border: 1px solid #d7d7d7; margin-right: 1em; padding: 4px; width: 350px;" src="https://smartystreets.com/resources/images/brand/smartystreets-gloss-inverted.png" alt="SmartyStreets">
SmartyStreets provides address geocode parsing & verification, city, state, zip
matching, address auto-complete, and address extraction.  Free and commercial
accounts are available.

Address validation offers many benefits - less returned mail, better address
data, consolidated duplicate addresses, improved communication with customers,
reduced mailing costs, less time dealing with bad data, and increased delivery
speeds.  SmartyStreets gives you all of that, and then some.


Installing SmartyStreetsAPI
====


GIT
----
Clone the GIT repository locally:

```shell
$ git clone https://github.com/WSI-Services/SmartyStreetsAPI.git
```


Composer
----
Add the library to your Composer dependencies from the command line:

```shell
$ composer require wsiservices/smartystreetsapi
```

**OR**

Add the required section to your `composer.json` file:

```json
"require": {
	"wsiservices/smartystreetsapi": "1.*"
}
```


Dependencies
====

### Install system dependencies:
```shell
apt-get -y install php5-dev libcurl3 libmagic1 zlib1g-dev
```

### Install PECL dependency 'raphf':
```shell
pecl install raphf
```

### Create PECL 'raphf' configuration file: /etc/php5/mods-available/raphf.ini
~~~
; configuration for php raphf module
; priority=20
extension=raphf.so
~~~

### Enable PHP module 'raphf':
```shell
php5enmod raphf
```

### Install PECL dependency 'propro':
```shell
pecl install propro
```

### Create PECL 'propro' configuration file: /etc/php5/mods-available/propro.ini
~~~
; configuration for php propro module
; priority=30
extension=propro.so
~~~

### Enable PHP module 'propro':
```shell
php5enmod propro
```

### To locate the installed library paths, replace {LIBRARY} with _libz_,
_libcurl_, _libevent.*_, & _libidn_:
```shell
ldconfig -p | grep "{LIBRARY}.so" | awk '{print $4}' | head -n1
```

### Install PECL dependency 'pecl_http':
```shell
pecl install pecl_http
```

#### Answer the following questions with the library paths found:
```shell
where to find zlib [/usr] :
where to find libcurl [/usr] :
where to find libevent [/usr] :
where to find libidn [/usr] :
```

### Create PECL 'http' configuration file: /etc/php5/mods-available/http.ini
~~~
; configuration for php http module
; priority=40
extension=http.so
~~~

### Enable PHP module 'http':
```shell
php5enmod http
```

### Initialize composer for development:
```shell
composer install
```

### Initialize composer for production:
```shell
composer install --no-dev --optimize-autoloader
```


Using SmartyStreetsAPI
====


Framework Support
----


### Laravel
SmartyStreetsAPI provides a service provider and facade for Laravel.

Register the service provider in the providers array, located in config/app.php:

```php
'providers' => [
	// ...

	WSIServices\SmartyStreetsAPI\Support\Laravel\Facade::class,
]
```

Add an alias within the aliases array found in `config/app.php`:

```php
'aliases' => [
	// ...

	'SmartyStreetsAPI'	=> WSIServices\SmartyStreetsAPI\Support\Laravel\ServiceProvider::class,
]
```

Publish the configuration file:
```shell
php artisan vendor:publish
```

Edit the configurations, located in `config/smartystreetsapi.php`:
```php
return [

	'authentication' => [
		'secret' => [
			'auth-id'		=> '',
			'auth-token'	=> ''
		],
		'website' => [
			'auth-id'		=> ''
		]
	],

	...

	'request-class' => SmartyStreetsAPI\Request\cURL::class,

	...
];
```

Provide the authentication values from SmartyStreets, and set your chosen
request class.


Namespace
----

```php
use WSIServices\SmartyStreetsAPI;
```


Class: Configuration
----

```php
$configuration = new Configuration($configArray);
```


Class: Factory
----

```php
$factory = new Factory(new Log);

$factory->setConfiguration($configuration);
```


Class: Service
----

```php
$service = $factory->getService('us-street-address');

$service->getRequest($optionsArray);
```

The following service types are provided with the corresponding classes:
<dl>
	<dt>us-street-address</dt><dd>\WSIServices\SmartyStreetsAPI\Service\UsStreetAddress</dd>
	<dt>us-zip-code</dt><dd>\WSIServices\SmartyStreetsAPI\Service\UsZipCode</dd>
	<dt>us-autocomplete</dt><dd>\WSIServices\SmartyStreetsAPI\Service\UsAutocomplete</dd>
	<dt>us-extract</dt><dd>\WSIServices\SmartyStreetsAPI\Service\UsExtract</dd>
	<dt>international-addresses</dt><dd>\WSIServices\SmartyStreetsAPI\Service\InternationalAddresses</dd>
	<dt>download</dt><dd>\WSIServices\SmartyStreetsAPI\Service\Download</dd>
</dl>
** NOTE: Currently only *us-street-address* has been completed.**


### US Street Address Service
This service has two methods for modifying the request:

```php
$service->XStandardizeOnly();

$service->XIncludeInvalid();
```


### Download Service
This service has multiple asset paths, which should be set in the options array:
<dl>
	<dt>us-street-api</dt><dd>/us-street-api/linux-amd64/latest.tar.gz</dd>
	<dt>us-street-data</dt><dd>/us-street-api/data/latest.tar.gz</dd>
	<dt>us-zipcode-api</dt><dd>/us-zipcode-api/linux-amd64/latest.tar.gz</dd>
	<dt>us-zipcode-data</dt><dd>/us-zipcode-api/data/latest.tar.gz</dd>
	<dt>us-autocomplete-api</dt><dd>/us-autocomplete-api/linux-amd64/latest.tar.gz</dd>
	<dt>us-autocomplete-data</dt><dd>/us-autocomplete-api/data/latest.tar.gz</dd>
</dl>


Class: Request
----

Their are four request type classes to choose from.  Two types are native to PHP
(ie: **_file_get_contents_** & **_curl_**), which don't have any additional
dependencies.  The other two types (ie: **_pecl_http2_** & **_GuzzleHttp_**)
require installing either a *PECL* PHP module or *Composer* package.


Utilities
====

This library includes utilities for documentation and testing.


phpUnit
----
PHPUnit is a programmer-oriented testing framework for PHP.  It is an instance
of the xUnit architecture for unit testing frameworks.

From the root of the project, run the following command:

```shell
$ ./utilities/phpunit.sh
```

**Example Output:**

```shell
PHPUnit 4.8.22-1-g1ae3a68 by Sebastian Bergmann and contributors.

................................................................. 65 / 97 ( 67%)
................................

Time: 44.39 seconds, Memory: 8.50Mb

OK (97 tests, 135 assertions)

Generating code coverage report in Clover XML format ... done

Generating code coverage report in HTML format ... done
```


phploc
----
A tool for quickly measuring the size and analyzing the structure of a PHP
project.

From the root of the project, run the following command:

```shell
$ ./utilities/phploc.sh
```

**Example Output:**

```shell
phploc 3.0.0-1-gdf11fc5 by Sebastian Bergmann.

Directories                                          9
Files                                               35

Size
  Lines of Code (LOC)                             4943
  Comment Lines of Code (CLOC)                    1191 (24.09%)
  Non-Comment Lines of Code (NCLOC)               3752 (75.91%)
  Logical Lines of Code (LLOC)                     791 (16.00%)
    Classes                                        216 (27.31%)
      Average Class Length                           6
        Minimum Class Length                         0
        Maximum Class Length                        23
      Average Method Length                          2
        Minimum Method Length                        0
        Maximum Method Length                       12
    Functions                                      487 (61.57%)
      Average Function Length                       97
    Not in classes or functions                     88 (11.13%)

Cyclomatic Complexity
  Average Complexity per LLOC                     0.07
  Average Complexity per Class                    2.57
    Minimum Class Complexity                      1.00
    Maximum Class Complexity                     24.00
  Average Complexity per Method                   1.93
    Minimum Method Complexity                     1.00
    Maximum Method Complexity                    21.00

Dependencies
  Global Accesses                                    0
    Global Constants                                 0 (0.00%)
    Global Variables                                 0 (0.00%)
    Super-Global Variables                           0 (0.00%)
  Attribute Accesses                               400
    Non-Static                                     400 (100.00%)
    Static                                           0 (0.00%)
  Method Calls                                     719
    Non-Static                                     694 (96.52%)
    Static                                          25 (3.48%)

Structure
  Namespaces                                        10
  Interfaces                                         2
  Traits                                             0
  Classes                                           20
    Abstract Classes                                 2 (10.00%)
    Concrete Classes                                18 (90.00%)
  Methods                                           83
    Scope
      Non-Static Methods                            82 (98.80%)
      Static Methods                                 1 (1.20%)
    Visibility
      Public Methods                                81 (97.59%)
      Non-Public Methods                             2 (2.41%)
  Functions                                          5
    Named Functions                                  2 (40.00%)
    Anonymous Functions                              3 (60.00%)
  Constants                                          3
    Global Constants                                 0 (0.00%)
    Class Constants                                  3 (100.00%)

Tests
  Classes                                           13
  Methods                                           96
```


phpDocumentor
----
A tool that makes it possible to generate documentation directly from your PHP
source code.

From the root of the project, run the following command:

```shell
$ ./utilities/phpdoc.sh
```

**Example Output:**

```shell

```

The documentation for this library is generated in the `documentation/api`
directory.


### Code Coverage
Running phpUnit generates logs in directory `tests/logs`.  Below you can see the
files and descriptions of the generated output.

<dl>
	<dt>coverage.txt</dt>
	<dd>The TEXT format for code coverage information logging produced by PHPUnit, loosely based upon the one used by <a href="http://www.atlassian.com/software/clover/">Clover</a>.</dd>
	<dt>coverage.xml</dt>
	<dd>The XML format for code coverage information logging produced by PHPUnit, loosely based upon the one used by <a href="http://www.atlassian.com/software/clover/">Clover</a>.</dd>
	<dt>report (directory)</dt>
	<dd>The HTML format for code coverage information; provides a package overview, namespace &amp; class descriptions, charts, and reports (including errors, markers, and deprecated elements).</dd>
	<dt>testdox.txt</dt>
	<dd>The TEXT format of the PHPUnit TestDox, to generate agile project documentation based on the tests.</dd>
	<dt>testdox.html</dt>
	<dd>The HTML format of the PHPUnit TestDox, to generate agile project documentation based on the tests.</dd>
	<dt>logfile.tap</dt>
	<dd>The Test Anything Protocol (TAP) is Perl's simple text-based interface between testing modules.</dd>
	<dt>logfile.json</dt>
	<dd>The <a href="http://www.json.org/">JavaScript Object Notation (JSON)</a> is a lightweight data-interchange format.</dd>
</dl>

You can find more out about phpUnit logging in their documentation
[Chapter 14. Logging](http://phpunit.de/manual/4.0/en/logging.html) and
[Chapter 15. Other Uses for Tests](http://phpunit.de/manual/4.0/en/other-uses-for-tests.html).


SmartyStreets Notes
====

+ https://smartystreets.com/docs

Status Codes and Results

* Responses will have a status header with a numeric value.
* This value is what you should check for when writing code to parse the response.
* The only response body that should be read and parsed is a 200 response.


US Street Address API
----

* https://api.smartystreets.com/street-address?auth-id=123&auth-token=abc

<dl>
	<dt>Scheme:</dt>
	<dd>https</dd>
	<dd><em>Required; non-secure http requests are not supported.<em></dd>
	<dt>Hostname:</dt>
	<dd>api.smartystreets.com</dd>
	<dt>Path:</dt>
	<dd>/street-address</dd>
	<dt>Query String:</dt>
	<dd>?auth-id=123&auth-token=abc</dd>
	<dd><em>Authentication information, inputs, etc. Additional query string parameters are introduced in the next section.</em></dd>
	<dt>Methods:</dt>
	<dd>GET, POST</dd>
</dl>

Code / Response and Explanation

<dl>
	<dt><strong>200</strong> - <em>OK:</em></dt>
	<dd><strong>Success!</strong> A JSON array containing zero or more address matches for the input provided with the request. If none of the submitted addresses validate, the array will be empty ([]). The structure of the response is the same for both GET and POST requests.</dt>
	<dt><strong>400</strong> - <em>Bad Request:</em></dt>
	<dd>(Malformed Payload) A GET request lacked a street field or the request body of a POST request contained malformed JSON.</dt>
	<dt><strong>401</strong> - <em>Unauthorized:</em></dt>
	<dd>The credentials were provided incorrectly or did not match any existing, active credentials.</dt>
	<dt><strong>402</strong> - <em>Payment Required:</em></dt>
	<dd>There is no active subscription for the account associated with the credentials submitted with the request.</dt>
</dl>


US ZIP Code API
----

* https://api.smartystreets.com/zipcode?auth-id=123&auth-token=abc

<dl>
	<dt>Scheme:</dt>
	<dd>https</dd>
	<dd><em>Required; non-secure http requests are not supported.</em></dd>
	<dt>Hostname:</dt>
	<dd>api.smartystreets.com</dd>
	<dt>Path:</dt>
	<dd>/zipcode</dd>
	<dt>Query String:</dt>
	<dd>?auth-id=123&auth-token=abc</dd>
	<dd><em>Authentication information, inputs, etc. Additional query string parameters are introduced in the next section.</em></dd>
	<dt>Methods:</dt>
	<dd>GET, POST</dd>
</dl>

Code / Response and Explanation

<dl>
	<dt><strong>200</strong> - <em>OK:</em></dt>
	<dd><strong>Success!</strong> The response body will be a JSON array containing zero or more matches for the input provided with the request. The structure of the response is the same for both GET and POST requests.</dt>
	<dt><strong>400</strong> - <em>Bad Request:</em></dt>
	<dd>(Malformed Payload) The request body of a POST request contained no lookups or contained malformed JSON.</dt>
	<dt><strong>401</strong> - <em>Unauthorized:</em></dt>
	<dd>The credentials were provided incorrectly or did not match any existing, active credentials.</dt>
	<dt><strong>402</strong> - <em>Payment Required:</em></dt>
	<dd>There is no active subscription for the account associated with the credentials submitted with the request.</dt>
</dl>


US Autocomplete API
----

* https://autocomplete-api.smartystreets.com/suggest?auth-id=123&auth-token=abc

<dl>
	<dt>Scheme:</dt>
	<dd>https</dd>
	<dd><em>Required; non-secure http requests are not supported.</em></dd>
	<dt>Hostname:</dt>
	<dd>autocomplete-api.smartystreets.com</dd>
	<dt>Path:</dt>
	<dd>/suggest</dd>
	<dt>Query String:</dt>
	<dd>?auth-id=123&auth-token=abc</dd>
	<dd><em>Authentication information, inputs, etc. Additional query string parameters are introduced in the next section.</em></dd>
	<dt>Methods:</dt>
	<dd>GET</dd>
</dl>

Code / Response and Explanation

<dl>
	<dt><strong>200</strong> - <em>OK:</em></dt>
	<dd><strong>Success!</strong> The response body is a JSON object with a suggestions array containing suggestions based on the supplied input parameters.</dt>
	<dt><strong>400</strong> - <em>Bad Request:</em></dt>
	<dd>(Malformed Payload) The request was malformed in some way and could not be parsed.</dt>
	<dt><strong>401</strong> - <em>Unauthorized:</em></dt>
	<dd>The credentials were provided incorrectly or did not match any existing, active credentials.</dt>
	<dt><strong>402</strong> - <em>Payment Required:</em></dt>
	<dd>There is no active subscription for the account associated with the credentials submitted with the request.</dt>
</dl>


US Extract API
----

* https://extract-beta.api.smartystreets.com/?auth-id=123&auth-token=abc

<dl>
	<dt>Scheme:</dt>
	<dd>https</dd>
	<dd><em>(Required; non-secure http requests are not supported.</em></dd>
	<dt>Hostname:</dt>
	<dd>extract-beta.api.smartystreets.com</dd>
	<dt>Path:</dt>
	<dd>/</dd>
	<dt>Query String:</dt>
	<dd>?auth-id=123&auth-token=abc</dd>
	<dd><em>Authentication information, inputs, etc. Additional query string parameters are introduced in the next section.</em></dd>
	<dt>Methods:</dt>
	<dd>POST</dd>
</dl>

Code / Response and Explanation

<dl>
	<dt><strong>200</strong> - <em>OK:</em></dt>
	<dd><strong>Success!</strong> The response body is a JSON object containing meta-data about the results and zero or more extracted addresses from the input provided with the request. See the annotated example below for details.</dt>
	<dt><strong>400</strong> - <em>Bad Request:</em></dt>
	<dd>(Malformed Payload) The request body was blank or otherwise malformed.</dt>
	<dt><strong>401</strong> - <em>Unauthorized:</em></dt>
	<dd>The credentials were provided incorrectly or did not match any existing, active credentials.</dt>
	<dt><strong>402</strong> - <em>Payment Required:</em></dt>
	<dd>There is no active subscription for the account associated with the credentials submitted with the request.</dt>
	<dt><strong>413</strong> - <em>Request Entity Too Large:</em></dt>
	<dd>The request body was larger than 64 Kilobytes.</dt>
</dl>


Verify International Addresses
----

* https://international-street.api.smartystreets.com/verify?auth-id=123&auth-token=abc

<dl>
	<dt>Scheme:</dt>
	<dd>https</dd>
	<dd><em>Required; non-secure http requests are not supported.</em></dd>
	<dt>Hostname:</dt>
	<dd>international-street.api.smartystreets.com</dd>
	<dt>Path:</dt>
	<dd>/verify</dd>
	<dt>Query String:</dt>
	<dd>?auth-id=123&auth-token=abc</dd>
	<dd><em>(Additional query string parameters are required; consult the next section.</em></dd>
	<dt>Methods:</dt>
	<dd>GET</dd>
</dl>

Code / Response and Explanation

<dl>
	<dt><strong>200</strong> - <em>OK:</em></dt>
	<dd><strong>Success!</strong> A JSON array containing zero or more address matches for the input provided with the request. If none of the submitted addresses validate, the array will be empty ([]).</dt>
	<dt><strong>400</strong> - <em>Bad Request:</em></dt>
	<dd>(Malformed Payload) Inputs from the request could not be interpreted.</dt>
	<dt><strong>401</strong> - <em>Unauthorized:</em></dt>
	<dd>The credentials were provided incorrectly or did not match any existing, active credentials.</dt>
	<dt><strong>402</strong> - <em>Payment Required:</em></dt>
	<dd>There is no active subscription for the account associated with the credentials submitted with the request.</dt>
	<dt><strong>403</strong> - <em>Forbidden:</em></dt>
	<dd>Because the international service is currently in a limited release phase, only approved accounts may access the service. Please contact us for your account to be granted access.</dt>
	<dt><strong>422</strong> - <em>Un-processable Entity:</em></dt>
	<dd>A GET request lacked required fields.</dt>
	<dt><strong>429</strong> - <em>Too Many Requests:</em></dt>
	<dd>Too many requests with exactly the same input values were submitted within too short a period. This status code conveys that the input was not processed in order to prevent runaway charges caused by such conditionas as a misbehaving (infinite) loop sending the same record over and over to the API. You're welcome.</dt>
	<dt><strong>504</strong> - <em>Gateway Timeout:</em></dt>
	<dd>Our own upstream data provider did not respond in a timely fashion and the request failed. A serious, yet rare occurence indeed.</dt>
</dl>


Download API
----

* https://download.api.smartystreets.com/path/to/package?auth-id=123&auth-token=abc

<dl>
	<dt>Scheme:</dt>
	<dd>https</dd>
	<dd><em>Required; non-secure http requests are not supported.</em></dd>
	<dt>Hostname:</dt>
	<dd>download.api.smartystreets.com</dd>
	<dt>Path:</dt>
	<dd>/{$pack-path}</dd>
	<dd><em>See the package listing for exact values.</em></dd>
	<dt>Query String:</dt>
	<dd>?auth-id=123&auth-token=abc</dd>
	<dd><em>Authentication information, inputs, etc. Additional query string parameters are introduced in the next section.</em></dd>
	<dt>Methods:</dt>
	<dd>GET</dd>
</dl>

Code / Response and Explanation

<dl>
	<dt><strong>307</strong> - <em>Temporary Redirect:</em></dt>
	<dd><strong>Success!</strong> The Location response header will contain the actual download URL. This link will only last for a few seconds so it will be necessary for you to follow that redirect immediately. Passing the -L flag to the curl command (as shown in the examples on this page) will accomplish this automatically.</dt>
	<dt><strong>401</strong> - <em>Unauthorized:</em></dt>
	<dd>The credentials were provided incorrectly or did not match any existing, active credentials.</dt>
	<dt><strong>402</strong> - <em>Payment Required:</em></dt>
	<dd>There is no active enterprise subscription for the account associated with the credentials submitted with the request.</dt>
	<dt><strong>404</strong> - <em>Not Found:</em></dt>
	<dd>The package you requested does not exist as specified. See the package listing for exact URL path values.</dt>
	<dt><strong>405</strong> - <em>Method Not Allowed:</em></dt>
	<dd>Request method used is not allowed. See allowed request methods.</dt>
</dl>


### Package Listing

Each package will include a text file with installation instructions and documentation.

Package - Token / URL Path (insert in request URL)
<dl>
	<dt>US Street Address API</dt>
	<dd>us-street-api</dd>
	<dd>us-street-api/linux-amd64/latest.tar.gz</dd>
	<dt>US Street Address Data</dt>
	<dd>us-street-data</dd>
	<dd>us-street-api/data/latest.tar.gz</dd>
	<dt>US ZIP Code API</dt>
	<dd>us-zipcode-api</dd>
	<dd>us-zipcode-api/linux-amd64/latest.tar.gz</dd>
	<dt>US ZIP Code Data</dt>
	<dd>us-zipcode-data</dd>
	<dd>us-zipcode-api/data/latest.tar.gz</dd>
	<dt>US Autocomplete API</dt>
	<dd>us-autocomplete-api</dd>
	<dd>us-autocomplete-api/linux-amd64/latest.tar.gz</dd>
	<dt>US Autocomplete Data</dt>
	<dd>us-autocomplete-data</dd>
	<dd>us-autocomplete-api/data/latest.tar.gz</dd>
</dl>
