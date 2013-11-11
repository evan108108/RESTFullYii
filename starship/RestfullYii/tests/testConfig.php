<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
//echo realpath(__DIR__ . '/../vendor'); exit();
return array(
	'basePath'=>dirname(__FILE__).'/../../../..',
	'name'=>'RestfullYii Testing App',

	// preloading 'log' component
	'preload'=>array('log'),

	'aliases' => array(
        'app' => 'application',
        'RestfullYii' =>realpath(__DIR__ . '/../')
	),
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		//'vendor.starship.scalar.src.Starship.Scalar.*'
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Password1',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>require(dirname(__FILE__).'/../config/routes.php'),
		),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=restfullyii_test',
			'emulatePrepare' => true,
			'username' => 'restfulyiiuser',
			'password' => '',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, trace',
				),
				// uncomment the following to show log messages on web pages
				array(
					'class'=>'CWebLogRoute',
				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);

