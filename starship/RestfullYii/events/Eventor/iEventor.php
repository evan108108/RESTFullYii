<?php
/**
 * Interface for Eventor
 *
 * Interface for Eventor a generic event emitter class
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Event
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.0.0
 */
interface iEventor
{
	function __construct($bound_obj, $exclusive_event_emmit=false);

	public function setBoundObj($obj);

	public function getBoundObj();

	public function setExclusiveEventEmit($exclusive_event_emmit);

	public function getEventRegister();

	public function setEventRegister($event_register);

	public function clearEventRegister();

	public function on($event, Callable $listener);

	public function emit($event, $params=array());

	public function removeListener($event_signature);

	public function removeEvent($event);
}
