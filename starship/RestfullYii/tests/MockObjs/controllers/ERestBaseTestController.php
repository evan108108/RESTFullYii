<?php
class ERestBaseTestController extends Controller
{
/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	private $_rest_events = array();

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			 array('RestfullYii.filters.ERestFilter + REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'),
		);
	}

	public function actions()
	{
		return array(
			'REST.'=>'RestfullYii.actions.ERestActionProvider',
		);
	}	

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', 
				'actions'=>array('REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function injectEvents($name, Callable $event)
	{
		$this->_rest_events[$name] = $event;
	}

	public function getInjectEvents()
	{
		return $this->_rest_events;
	}

	public function restEvents()
	{
		foreach($this->getInjectEvents() as $name=>$listener) {
			$this->onRest($name, $listener);
		}
	}

}
