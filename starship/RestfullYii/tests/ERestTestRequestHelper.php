<?php
Yii::import('RestfullYii.tests.MockObjs.controllers.*');
Yii::import('RestfullYii.events.ERestEvent');
/**
 * ERestTestRequestHelper
 *
 * Helper for making end to end requests from unit tests
 *
 * This is a quick and dirty tests class that should only be used for UnitTest cases
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestTestRequestHelper implements arrayaccess
{
	private $container = [];
	public $stream;

	public function __construct()
	{
		$this->stream = fopen("php://temp", 'wb');
		$stream = $this->stream;
		$this->container['events'] = [
			[
				ERestEvent::REQ_DATA_READ => function($notused=null) use ($stream) {
					$reader = new ERestRequestReader($stream);
					return CJSON::decode($reader->getContents());
				}
			]
		];
		$this->container['config'] = [
			'url'			=> null,
			'type'		=> null,
			'data'		=> null,
			'headers' => [],
		];
  }
	public function offsetSet($offset, $value) 
	{
		if (is_null($offset)) {
			$this->container[] = $value;
		} else {
			$this->container[$offset] = $value;
		}
	}

	public function offsetExists($offset)
	{
		return isset($this->container[$offset]);
	}

	public function offsetUnset($offset)
	{
		unset($this->container[$offset]);
	}

	public function offsetGet($offset)
	{
		return isset($this->container[$offset]) ? $this->container[$offset] : null;
	}

	public function addEvent($event, Callable $callback)
	{
		$this->container['events'][] = [$event => $callback];
	}

	public function send()
	{
		//parse url
		if(!is_string($this->url)) {
			throw new CHttpException(500, 'You must set the url (string)');
		}
		$url_info = parse_url($this->url);


		//Set the http type
		if(strtolower($url_info['scheme']) == 'https') {
			$_SERVER['HTTPS'] = 'on';
		}

		//parse query string and set get variables
		if(isset($url_info['query'])) {
			parse_str($url_info['query'], $get_vars);
			foreach($get_vars as $get_var=>$value)
			{
				$_GET[$get_var] = $value;
			}
		}

		//instance the controller indicated by the url and apply events
		$path_parts = explode('/', ltrim($url_info['path'], '/'));
		$controller_name = ucfirst($path_parts[0]) . 'Controller';
		$controller = new $controller_name(ucfirst($path_parts[0]));
		$this->applyEvents($controller);


		//set the params to be passed to the controllers action
		$id = isset($path_parts[1])? $path_parts[1]: null;
		$param1 = isset($path_parts[2])? $path_parts[2]: null;
		$param2 = isset($path_parts[3])? $path_parts[3]: null;

		//set the action to run based on the config request type
		switch (strtolower($this->type)) {
			case 'get':
				$action = 'REST.GET';
				break;
			case 'post':
				$action = 'REST.POST';
				break;
			case 'put':
				$action = 'REST.PUT';
				break;
			case 'delete':
				$action = 'REST.DELETE';
				break;
			case 'options':
				$action = 'REST.OPTIONS';
				break;
			default:
				$action = 'REST.GET';
				break;
		}

		$action = $controller->createAction($action);

		//write data set in the request config
		$this->writeRequestData($this->data);

		//set header vars set in the request config
		foreach($this->headers as $header=>$value) {
			$_SERVER['HTTP_' . $header] = $value;
			//echo $header . ":" . $value . "\n";
		}

		//Run the controller action and return the response
		return $this->sendRequest($controller, $action, $id, $param1, $param2);
	}

	public function sendRequest($controller, $action, $id, $param1, $param2)
	{
		$_GET['id'] = $id;
		$_GET['param1'] = $param1;
		$_GET['param2'] = $param2;

		ob_start();
		try {
			$controller->runActionWithFilters($action, $controller->filters());
			$output =  ob_get_contents();
		} catch (Exception $e) {
			$output = $e;
		}
		if (ob_get_length() > 0 ) { @ob_end_clean(); }
		
		return $output;
	}

	public function writeRequestData($data)
	{
		if(!is_null($data)) {
			fputs($this->stream, $data);
		}
	}

	public function applyEvents(&$controller)
	{
		foreach($this->container['events'] as $event) {
			list($event_name, $callback) = each($event);
			call_user_func_array([$controller, 'injectEvents'], [$event_name, $callback]);
		}
	}

	public function __get($name)
	{
		if(array_key_exists($name, $this->container)) {
			return $this->container[$name];
		}
		if(array_key_exists($name, $this->container['config'])) {
			return $this->container['config'][$name];
		}
		if(array_key_exists($name, $this->container['events'])) {
			return $this->container['events'][$name];
		}
	}
}
