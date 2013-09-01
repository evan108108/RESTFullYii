<?php
Yii::import('ext.starship.RestfullYii.events.Eventor.*');
Yii::import('ext.starship.RestfullYii.events.*');
Yii::import('ext.starship.RestfullYii.ARBehaviors.*');
Yii::import('ext.starship.RestfullYii.components.*');

/**
 * ERestBehavior
 * Extends CBehavior
 *
 *
 * Acts as the glue for RestfullYii and sets up basic functionality
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/behaviors
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 *
 * @property Object		$event
 * @property Object		$listeners
 * @property Object		$http_status
 * @property Object		$resource_helper
 * @property Object		$subresource_helper
 */
class ERestBehavior extends CBehavior
{
	private $event;
	private $listeners;
	private $http_status;
	private $resource_helper;
	private $subresource_helper;

	/**
	 * ERestInit
	 *
	 * Initializes the basic RestfullYii functionality
	 */
	public final function ERestInit()
	{
		$this->event = New Eventor($this->owner, true);
		$this->listeners = New ERestEventListenerRegistry([$this, 'onRest']);
		$this->http_status = New EHttpStatus();
		$this->resource_helper = new ERestResourceHelper([$this, 'emitRest']);
		$this->subresource_helper = new ERestSubrecourceHelper([$this, 'emitRest']);

		Yii::app()->clientScript->reset(); //Remove any scripts registered by Controller Class
		Yii::app()->onException = array($this, 'onException');

		$this->registerEventListeners();
	}

	/**
	 * registerEventListeners
	 *
	 * register all event handlers/listeners
	 */
	private function registerEventListeners()
	{
		//Load default listeners
		$this->listeners->run();

		//Load global listeners
		if( isset(Yii::app()->params['RestfullYii']) ) {
			foreach(Yii::app()->params['RestfullYii'] as $event=>$listener) {
				$this->onRest($event, $listener);
			}
		}

		//Load local listeners
		if( method_exists($this->owner, 'restEvents') ) {
			$this->owner->restEvents();
		}
	}

	/**
	 * onRest
	 *
	 * provides interface for event handlers
	 *
	 * @param (String) (event) the name of the event
	 * @param (Callable) (listener) the event listener
	 *
	 * @return (Object) (event) returns the event object
	 */
	public function onRest($event, $listener)
	{
		return $this->event->on($event, $listener);
	}

	/**
	 * emitRest
	 *
	 * Provides interface for triggering events
	 *
	 * @param (String) (event) the name of the event
	 * @param (Mixed/Array) params to pass to event listener
	 */
	public function emitRest($event, $params=[])
	{
		$this->event->emit(ERestEvent::REQ_EVENT_LOGGER, ["pre.filter.$event"]);

		if($this->eventExists("pre.filter.$event")) {
			$params = $this->event->emit("pre.filter.$event", $params);
		}

		$this->event->emit(ERestEvent::REQ_EVENT_LOGGER, [$event]);
		$event_response = $this->event->emit($event, $params);

		$this->event->emit(ERestEvent::REQ_EVENT_LOGGER, ["post.filter.$event"]);
		if($this->eventExists("post.filter.$event")) {
			return $this->event->emit("post.filter.$event", [$event_response]);
		}

		return $event_response;
	}

	/**
	 * eventExists
	 *
	 * Checks if an event exists
	 *
	 * @param (String) (event) the name of the event
	 *
	 * @return (Bool) true if event exists, false if not
	 */
	public function eventExists($event)
	{
		return $this->event->eventExists($event);
	}

	/**
	 * getHttpStatus
	 *
	 * Returns the http_status object
	 *
	 * @return (Object) (EHttpStatus) an instance of EHttpStatus
	 */
	public function getHttpStatus()
	{
		return $this->http_status;
	}

	/**
	 * setHttpStatus
	 *
	 * Set the http status of the current request
	 *
	 * @param (Int) (code) the http response code
	 * @param (String) (message) the http message to attach to request
	 */
	public function setHttpStatus($code, $message=null)
	{
		return $this->http_status->set($code, $message);
	}

	/**
	 * getResourceHelper
	 *
	 * returns an instance of ERestResourceHelper
	 *
	 * @return (Object) (ERestResourceHelper) instance of ERestResourceHelper
	 */
	public function getResourceHelper()
	{
		return $this->resource_helper;
	}

	/**
	 * getSubresourceHelper
	 *
	 * returns an instance of ERestSubresourceHelper
	 *
	 * @return (Object) (ERestSubresourceHelper) instance of ERestSubresourceHelper
	 */
	public function getSubresourceHelper()
	{
		return $this->subresource_helper;
	}

	/**
	 * onException
	 *
	 * Exception event handler
	 *
	 * @param (Object) (event) instance of Exception
	 */
	public function onException($event)
	{
		$message = null;

		if($this->emitRest(ERestEvent::CONFIG_DEV_FLAG)) {
			if(CJSON::decode($message)) {
				$message = CJSON::decode($message);
			} else {
				$message = $event->exception->getMessage();
			}
		}
		$errorCode = !isset($event->exception->statusCode)? 500: $event->exception->statusCode;
		$this->setHttpStatus($errorCode, $message);
		$this->emitRest(ERestEvent::REQ_EXCEPTION, [$errorCode, $message]);
		$event->handled = true;
	}

	/**
	 * renderJSON
	 *
	 * helper function for rendering json
	 *
	 * @param (Array) (params) list of params to send to the render
	 */
	public function renderJSON($params)
	{
		if(isset($this->owner)) {
			$controller = $this->owner;
		} else {
			$controller = $this;
		}
		$controller->layout = 'ext.starship.restfullyii.views.layouts.json';
		$controller->render('ext.starship.restfullyii.views.api.output', $params);
	}

}
