<?php
  return array(
        'api/<controller:\w+>'=>array('<controller>/restList', 'verb'=>'GET'),
        'api/<controller:\w+>/<id:\w+>'=>array('<controller>/restView', 'verb'=>'GET'),
        'api/<controller:\w+>/<id:\w+>/<var:\w*>'=>array('<controller>/restView', 'verb'=>'GET'),
        'api/<controller:\w+>/<id:\w+>/<var:\w*>/<var2:\w*>'=>array('<controller>/restView', 'verb'=>'GET'),
        
        array('<controller>/restUpdate', 'pattern'=>'api/<controller:\w+>/<id:\d+>', 'verb'=>'PUT'),
        array('<controller>/restUpdate', 'pattern'=>'api/<controller:\w+>/<var:\w+>/<id:\d+>', 'verb'=>'PUT'),
        array('<controller>/restDelete', 'pattern'=>'api/<controller:\w+>/<id:\d+>', 'verb'=>'DELETE'),
        array('<controller>/restCreate', 'pattern'=>'api/<controller:\w+>', 'verb'=>'POST'),
        array('<controller>/restCreate', 'pattern'=>'api/<controller:\w+>/<id:\w+>', 'verb'=>'POST'),
        
		'<controller:\w+>/<id:\d+>'=>'<controller>/view',
		'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
        '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',  
      );
