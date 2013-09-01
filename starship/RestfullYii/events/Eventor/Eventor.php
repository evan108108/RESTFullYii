<?php
/**
 * Generic event emitter class
 *
 * Bind events And event listeners to a given class
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Event
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 *
 * @property Object		$bound_obj
 * @property Bool			$exclusive_event_emit
 * @property Array		$event_register
 */
class Eventor implements iEventor
{
	private $bound_obj;
	private $exclusive_event_emit;
	private $event_register = [];

	/**
	 * construct
	 *
	 * Sets the basic eventor context
	 *
	 * @param (Object) (bound_obj) Object to bind event listener to.
	 * @param (Bool) (exclusive_event_emit) True allows events to be overwritten.
	 */
	function __construct($bound_obj, $exclusive_event_emit=false)
	{
		$this->setBoundObj($bound_obj);
		$this->setExclusiveEventEmit($exclusive_event_emit);
	}

	/**
	 * setBoundObj
	 *
	 * Setter for $this->bound_obj
	 *
	 * @param (Object) (bound_obj) Object to bind event listener to.
	 */
	public function setBoundObj($obj)
	{
		$this->bound_obj = $obj;
	}

	/**
	 * getBoundObj
	 *
	 * Getter for $this->bound_obj
	 *
	 * @return (Object) (bound_obj)
	 */
	public function getBoundObj()
	{
		return $this->bound_obj;
	}

	/**
	 * setExclusiveEventEmit
	 *
	 * Setter for $this->exclusive_event_emit
	 *
	 * @param (Bool) (exclusive_event_emit) True allows events to be overwritten.
	 */
	public function setExclusiveEventEmit($exclusive_event_emit)
	{
		$this->exclusive_event_emit = $exclusive_event_emit;
	}

	/**
	 * getExclusiveEventEmit
	 *
	 * Getter for $this->exclusive_event_emit
	 *
	 * @return (Bool) (exclusive_event_emit)
	 */
	public function getExclusiveEventEmit()
	{
		return $this->exclusive_event_emit;
	}

	/**
	 * setEventRegister
	 *
	 * Setter for $this->event_register
	 *
	 * @param (Array) (event_register) Registry of all events and event listeners.
	 */
	public function setEventRegister($event_register)
	{
		$this->event_register = $event_register;
	}

	/**
	 * getEventRegister
	 *
	 * Getter for $this->event_register
	 *
	 * @return (Array) (event_register)
	 */
	public function getEventRegister()
	{
		return $this->event_register;
	}

	/**
	 * clearEventRegister
	 *
	 * Clears $this->event_register
	 *
	 */
	public function clearEventRegister()
	{
		$this->event_register = array();
	}

	/**
	 * on
	 *
	 * Set on event listener on given event
	 *
	 * @param (String) (event) name of event
	 * @param (Callable) (listener) Callback to be called when event is emitted
	 * @return (String) (listener_signature) The unique ID of the newly created event listener
	 */
	public function on($event, Callable $listener)
	{
		$listener_signature = md5( serialize( array($event, microtime() ) ) );
		$event_register = $this->getEventRegister();
		$listener_container = array(
			'signature'=>$listener_signature,
			'callback'=>$listener,
		);

		if( $this->getExclusiveEventEmit() ) {
			$event_register[$event] = array($listener_container);
		} else {
			if( !isset($event_register[$event]) ) {
				$event_register[$event] = array();
			}
			$event_register[$event][] = $listener_container;
		}
		$this->setEventRegister($event_register);

		return $listener_signature;
	}

	/**
	 * emit
	 *
	 * Emit a given event and pass given params to listener(s)
	 *
	 * @param (String) (event) name of event
	 * @param (Mixed) (params) Optional Array of params to pass to the event
	 */
	public function emit($event, $params=array())
	{
		$event_responses = array();
		if(!is_array($params)) {
			$params = array($params);
		}
		$event_register = $this->getEventRegister();
		if(isset($event_register[$event])) {
			foreach($event_register[$event] as $listener) {
				$callback = $listener['callback']->bindTo( $this->getBoundObj() );
				$event_responses[] = call_user_func_array($callback, $params);
				if($this->exclusive_event_emit) {
					return $event_responses[0];
				}
			}
		}
		return $event_responses;
	}

	/**
	 * removeListener
	 *
	 * Removes an event listener of the given listener_signature
	 *
	 * @param (String) (listener_signature) The unique ID of the event listener you wish to remove
	 */
	public function removeListener($listener_signature)
	{
		$event_register = $this->getEventRegister();
		foreach($this->getEventRegister() as $event=>$listeners) {
			array_walk($listeners, function(&$listener, $key) use (&$event_register, $event, $listener_signature) {
				if($listener['signature'] == $listener_signature) {
					unset($event_register[$event][$key]);
				}
			});
		}
		$this->setEventRegister($event_register);
	}

	/**
	 * removeEvent
	 *
	 * Removes an event
	 *
	 * @param (String) (event) The name of the event you wish to remove
	 */
	public function removeEvent($event)
	{
		$event_register = $this->getEventRegister();
		if(isset($event_register[$event])) {
			unset($event_register[$event]);
		}
		$this->setEventRegister($event_register);
	}

	/**
	 * eventExists
	 *
	 * @param (String) (event) The name of the event you wish to check exists
	 */
	public function eventExists($event)
	{
		$event_register = $this->getEventRegister();
		if(isset($event_register[$event])) {
			return true;
		}
		return false;
	}

}
