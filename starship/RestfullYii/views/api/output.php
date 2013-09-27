<?php
$this->widget('RestfullYii.widgets.ERestJSONOutputWidget', array(
	'type'				=>(isset($type)? $type: 'raw'),
	'success'			=>(isset($success)? $success: true),
	'message'			=>(isset($message)? $message: ""),
	'totalCount'	=>(isset($totalCount)? $totalCount: ""),
	'modelName'		=>(isset($modelName)? $modelName: null),
	'visibleProperties'		=>(isset($visibleProperties)? $visibleProperties: null),
	'hiddenProperties'		=>(isset($hiddenProperties)? $hiddenProperties: null),
	'data'				=>(isset($data)? $data: null),
	'relations'		=>(isset($relations)? $relations: []),
	'errorCode'		=>(isset($errorCode)? $errorCode: null),
));
