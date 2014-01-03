<?php
return [
	'api/<controller:\w+>'=>['<controller>/REST.GET', 'verb'=>'GET'],
	'api/<controller:\w+>/<id:\w*>'=>['<controller>/REST.GET', 'verb'=>'GET'],
	'api/<controller:\w+>/<id:\w*>/<param1:\w*>'=>['<controller>/REST.GET', 'verb'=>'GET'],
	'api/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>'=>['<controller>/REST.GET', 'verb'=>'GET'],

	['<controller>/REST.PUT', 'pattern'=>'api/<controller:\w+>/<id:\w*>', 'verb'=>'PUT'],
	['<controller>/REST.PUT', 'pattern'=>'api/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb'=>'PUT'],
	['<controller>/REST.PUT', 'pattern'=>'api/<controller:\w*>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb'=>'PUT'],	

	['<controller>/REST.DELETE', 'pattern'=>'api/<controller:\w+>/<id:\w*>', 'verb'=>'DELETE'],
	['<controller>/REST.DELETE', 'pattern'=>'api/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb'=>'DELETE'],
	['<controller>/REST.DELETE', 'pattern'=>'api/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb'=>'DELETE'],

	['<controller>/REST.POST', 'pattern'=>'api/<controller:\w+>', 'verb'=>'POST'],
	['<controller>/REST.POST', 'pattern'=>'api/<controller:\w+>/<id:\w+>', 'verb'=>'POST'],
	['<controller>/REST.POST', 'pattern'=>'api/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb'=>'POST'],
	['<controller>/REST.POST', 'pattern'=>'api/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb'=>'POST'],

	['<controller>/REST.OPTIONS', 'pattern'=>'api/<controller:\w+>', 'verb'=>'OPTIONS'],
	['<controller>/REST.OPTIONS', 'pattern'=>'api/<controller:\w+>/<id:\w+>', 'verb'=>'OPTIONS'],
	['<controller>/REST.OPTIONS', 'pattern'=>'api/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb'=>'OPTIONS'],
	['<controller>/REST.OPTIONS', 'pattern'=>'api/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb'=>'OPTIONS'],

	'<controller:\w+>/<id:\d+>'=>'<controller>/view',
	'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
	'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
];
