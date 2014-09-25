<?php
return [
    'api/<module:\w+>/<controller:\w+>'=>['<module>/<controller>/REST.GET', 'verb'=>'GET'],
    'api/<module:\w+>/<controller:\w+>/<id:\w*>'=>['<module>/<controller>/REST.GET', 'verb'=>'GET'],
    'api/<module:\w+>/<controller:\w+>/<id:\w*>/<param1:\w*>'=>['<module>/<controller>/REST.GET', 'verb'=>'GET'],
    'api/<module:\w+>/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>'=>['<module>/<controller>/REST.GET', 'verb'=>'GET'],

    'api/<controller:\w+>'=>['<controller>/REST.GET', 'verb'=>'GET'],
    'api/<controller:\w+>/<id:\w*>'=>['<controller>/REST.GET', 'verb'=>'GET'],
    'api/<controller:\w+>/<id:\w*>/<param1:\w*>'=>['<controller>/REST.GET', 'verb'=>'GET'],
    'api/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>'=>['<controller>/REST.GET', 'verb'=>'GET'],

    ['<module>/<controller>/REST.PUT', 'pattern'=>'api/<module:\w+>/<controller:\w+>/<id:\w*>', 'verb'=>'PUT'],
    ['<module>/<controller>/REST.PUT', 'pattern'=>'api/<module:\w+>/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb'=>'PUT'],
    ['<module>/<controller>/REST.PUT', 'pattern'=>'api/<module:\w+>/<controller:\w*>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb'=>'PUT'],   

    ['<module>/<controller>/REST.DELETE', 'pattern'=>'api/<module:\w+>/<controller:\w+>/<id:\w*>', 'verb'=>'DELETE'],
    ['<module>/<controller>/REST.DELETE', 'pattern'=>'api/<module:\w+>/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb'=>'DELETE'],
    ['<module>/<controller>/REST.DELETE', 'pattern'=>'api/<module:\w+>/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb'=>'DELETE'],

    ['<module>/<controller>/REST.POST', 'pattern'=>'api/<module:\w+>/<controller:\w+>', 'verb'=>'POST'],
    ['<module>/<controller>/REST.POST', 'pattern'=>'api/<module:\w+>/<controller:\w+>/<id:\w+>', 'verb'=>'POST'],
    ['<module>/<controller>/REST.POST', 'pattern'=>'api/<module:\w+>/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb'=>'POST'],
    ['<module>/<controller>/REST.POST', 'pattern'=>'api/<module:\w+>/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb'=>'POST'],

    ['<module>/<controller>/REST.OPTIONS', 'pattern'=>'api/<module:\w+>/<controller:\w+>', 'verb'=>'OPTIONS'],
    ['<module>/<controller>/REST.OPTIONS', 'pattern'=>'api/<module:\w+>/<controller:\w+>/<id:\w+>', 'verb'=>'OPTIONS'],
    ['<module>/<controller>/REST.OPTIONS', 'pattern'=>'api/<module:\w+>/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb'=>'OPTIONS'],
    ['<module>/<controller>/REST.OPTIONS', 'pattern'=>'api/<module:\w+>/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb'=>'OPTIONS'],

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

	'<module:\w+>/<controller:\w+>/<id:\d+>'=>'<module>/<controller>/view',
	'<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<module>/<controller>/<action>',
    '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',

	'<controller:\w+>/<id:\d+>'=>'<controller>/view',
	'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
    '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
];
