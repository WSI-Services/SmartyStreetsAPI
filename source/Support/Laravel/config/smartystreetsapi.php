<?php

use \WSIServices\SmartyStreetsAPI;

return [

	/*
	|--------------------------------------------------------------------------
	| REST & Javascript API Authentication Secret Keys
	|--------------------------------------------------------------------------
	|
	| Key pairs must be kept secret, so they should not be used on client-side
	| HTML or in client-side applications.  Use secret key pairs on server-side
	| code that connects directly to the SmartyStreets API servers.  A secret
	| key pair is not limited to any particular hostname like website keys are.
	| A key pair consists of an ID to identify your account and a token which
	| is like a password.
	|
	*/

	'authentication' => [
		'secret' => [
			'auth-id'		=> '',
			'auth-token'	=> ''
		],
		'website' => [
			'auth-id'		=> ''
		]
	],

	/*
	|--------------------------------------------------------------------------
	| Request Class
	|--------------------------------------------------------------------------
	|
	| Class to be provided as a request connection
	|
	*/

	'request-class' => SmartyStreetsAPI\Request\cURL::class,

	/*
	|--------------------------------------------------------------------------
	| Filters Class
	|--------------------------------------------------------------------------
	|
	| Class to be provided field validation
	|
	*/

	'filters-class' => SmartyStreetsAPI\Filters::class,

	/*
	|--------------------------------------------------------------------------
	| Service Hostname Endpoints
	|--------------------------------------------------------------------------
	|
	| Endpoints provide specific services at specific hostnames.  DO NOT
	| hard-code IP addresses in your configuration.  SmartyStreets services are
	| geo-distributed and the IP addresses of their servers will change
	| regularly (and without notice) according to changing and unpredictable
	| network traffic patterns.  If you require static IPs due to regulatory
	| compliance, they have an enterprise plan available.
	|
	| Most HTTP requests are defined using the GET method, SmartyStrees uses
	| both GET and POST with their endpoints.  GET is used for single requests
	| while POST is used for multi-line requests and multiple address requests.
	|
	| Some endpoints have multiple service paths for separation of components,
	| as seen with the download endpoint.
	|
	*/

	'endpoints' => [
		'us-street-address' => [
			'model'		=> SmartyStreetsAPI\Service\UsStreetAddress::class,
			'hostname'	=> 'api.smartystreets.com',
			'paths'		=> [ '/street-address' ]
		],
		'us-zip-code' => [
			'model'		=> SmartyStreetsAPI\Service\UsZipCode::class,
			'hostname'	=> 'api.smartystreets.com',
			'paths'		=> [ '/zipcode' ]
		],
		'us-autocomplete' => [
			'model'		=> SmartyStreetsAPI\Service\UsAutocomplete::class,
			'hostname'	=> 'autocomplete-api.smartystreets.com',
			'paths'		=> [ '/suggest' ]
		],
		'us-extract' => [
			'model'		=> SmartyStreetsAPI\Service\UsExtract::class,
			'hostname'	=> 'extract-beta.api.smartystreets.com',
			'paths'		=> [ '/' ]
		],
		'international-addresses' => [
			'model'		=> SmartyStreetsAPI\Service\InternationalAddresses::class,
			'hostname'	=> 'international-street.api.smartystreets.com',
			'paths'		=> [ '/verify' ]
		],
		'download' => [
			'model'		=> SmartyStreetsAPI\Service\Download::class,
			'hostname'	=> 'download.api.smartystreets.com',
			'paths'		=> [
				/** Download Endpoints Package Paths */
				'us-street-api'			=> '/us-street-api/linux-amd64/latest.tar.gz',
				'us-street-data'		=> '/us-street-api/data/latest.tar.gz',
				'us-zipcode-api'		=> '/us-zipcode-api/linux-amd64/latest.tar.gz',
				'us-zipcode-data'		=> '/us-zipcode-api/data/latest.tar.gz',
				'us-autocomplete-api'	=> '/us-autocomplete-api/linux-amd64/latest.tar.gz',
				'us-autocomplete-data'	=> '/us-autocomplete-api/data/latest.tar.gz'
			]
		]
	]
];