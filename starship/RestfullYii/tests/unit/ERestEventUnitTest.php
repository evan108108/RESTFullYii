<?php
Yii::import('ext.starship.RestfullYii.events.ERestEvent');

/**
 * ERestEvent
 * 
 * Tests ERestEvent
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestEventUnitTest extends ERestTestCase
{
	/**
	 * testCONFIGAPPLICATIONIDExists
	 *
	 * tests ERestEvent has const::CONFIG_APPLICATION_ID
	 */ 
	public function testHasConstCONFIGAPPLICATIONIDExists()
	{
		$this->assertTrue(defined('ERestEvent::CONFIG_APPLICATION_ID'));
	}

	/**
	 * testCONFIGDEVFLAGExists
	 *
	 * tests ERestEvent has const::CONFIG_DEV_FLAG 
	 */ 
	public function testHasConstCONFIGDEVFLAGExists()
	{
		$this->assertTrue(defined('ERestEvent::CONFIG_DEV_FLAG'));
	}


	/**
	 * testREQEVENTLOGGERRExists
	 *
	 * tests ERestEvent has const::REQ_EVENT_LOGGER
	 */ 
	public function testREQEVENTLOGGERRExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_EVENT_LOGGER'));
	}

	/**
	 * testREQAUTHUSERExists
	 *
	 * tests ERestEvent has const::REQ_AUTH_USER
	 */ 
	public function testHasConstREQAUTHUSERExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_AUTH_USER'));
	}

	/**
	 * testREQAUTHHTTPSONLYExists
	 *
	 * tests ERestEvent has const::REQ_AUTH_HTTPS_ONLY 
	 */ 
	public function testHasConstREQAUTHHTTPSONLYExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_AUTH_HTTPS_ONLY'));
	}

	/**
	 * testREQAUTHUSERNAMEExists
	 *
	 * tests ERestEvent has const::REQ_AUTH_USERNAME
	 */ 
	public function testHasConstREQAUTHUSERNAMEExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_AUTH_USERNAME'));
	}

	/**
	 * testREQAUTHPASSWORDExists
	 *
	 * tests ERestEvent has const::REQ_AUTH_PASSWORD
	 */ 
	public function testHasConstREQAUTHPASSWORDExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_AUTH_PASSWORD'));
	}

	/**
	 * testREQAUTHAJAXUSERExists
	 *
	 * tests ERestEvent has const::REQ_AUTH_AJAX_USER 
	 */ 
	public function testHasConstREQAUTHAJAXUSERExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_AUTH_AJAX_USER'));
	}

	/**
	 * testREQAFTERACTIONExists
	 *
	 * tests ERestEvent has const::REQ_AFTER_ACTION
	 */ 
	public function testHasConstREQAFTERACTIONExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_AFTER_ACTION'));
	}

	/**
	 * testREQPARAMISPKExists
	 *
	 * tests ERestEvent has const::REQ_PARAM_IS_PK 
	 */ 
	public function testHasConstREQPARAMISPKExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_PARAM_IS_PK'));
	}

	/**
	 * testREQDATAREADExists
	 *
	 * tests ERestEvent has const::REQ_DATA_READ
	 */ 
	public function testHasConstREQDATAREADExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_DATA_READ'));
	}

	/**
	 * testREQGETRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::REQ_GET_RESOURCE_RENDER 
	 */ 
	public function testHasConstREQGETRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_GET_RESOURCE_RENDER'));
	}

	/**
	 * testREQGETRESOURCESRENDERExists
	 *
	 * tests ERestEvent has const::REQ_GET_RESOURCES_RENDER
	 */ 
	public function testHasConstREQGETRESOURCESRENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_GET_RESOURCES_RENDER'));
	}

	/**
	 * testREQPUTRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::REQ_PUT_RESOURCE_RENDER 
	 */ 
	public function testHasConstREQPUTRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_PUT_RESOURCE_RENDER'));
	}

	/**
	 * testREQPOSTRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::REQ_POST_RESOURCE_RENDER
	 */ 
	public function testHasConstREQPOSTRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_POST_RESOURCE_RENDER'));
	}

	/**
	 * testREQDELETERESOURCERENDERExists
	 *
	 * tests ERestEvent has const::REQ_DELETE_RESOURCE_RENDER 
	 */ 
	public function testHasConstREQDELETERESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_DELETE_RESOURCE_RENDER'));
	}

	/**
	 * testREQGETSUBRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::REQ_GET_SUBRESOURCE_RENDER 
	 */ 
	public function testHasConstREQGETSUBRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_GET_SUBRESOURCE_RENDER'));
	}

	/**
	 * testREQGETSUBRESOURCESRENDERExists
	 *
	 * tests ERestEvent has const::REQ_GET_SUBRESOURCES_RENDER 
	 */ 
	public function testHasConstREQGETSUBRESOURCESRENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_GET_SUBRESOURCES_RENDER'));
	}

	/**
	 * testREQPUTSUBRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::REQ_PUT_SUBRESOURCE_RENDER 
	 */ 
	public function testHasConstREQPUTSUBRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_PUT_SUBRESOURCE_RENDER'));
	}

	/**
	 * testREQDELETESUBRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::REQ_DELETE_SUBRESOURCE_RENDER
	 */ 
	public function testHasConstREQDELETESUBRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_DELETE_SUBRESOURCE_RENDER'));
	}

	/**
	 * testREQEXCEPTIONExists
	 *
	 * tests ERestEvent has const::REQ_EXCEPTION
	 */ 
	public function testHasConstREQEXCEPTIONExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_EXCEPTION'));
	}

	/**
	 * testREQRENDERJSONExists
	 *
	 * tests ERestEvent has const::REQ_RENDER_JSON 
	 */ 
	public function testHasConstREQRENDERJSONExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_RENDER_JSON'));
	}

	/**
	 * testMODELINSTANCEExists
	 *
	 * tests ERestEvent has const::MODEL_INSTANCE 
	 */ 
	public function testHasConstMODELINSTANCEExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_INSTANCE'));
	}

	/**
	 * testMODELATTACHBEHAVIORSExists
	 *
	 * tests ERestEvent has const::MODEL_ATTACH_BEHAVIORS
	 */ 
	public function testMODELATTACHBEHAVIORSExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_ATTACH_BEHAVIORS'));
	}


	/**
	 * testMODELWITHRELATIONSExists
	 *
	 * tests ERestEvent has const::MODEL_WITH_RELATIONS
	 */ 
	public function testHasConstMODELWITHRELATIONSExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_WITH_RELATIONS'));
	}

	/**
	 * testMODELLAZYLOADRELATIONSExists
	 *
	 * tests ERestEvent has const::MODEL_LAZY_LOAD_RELATIONS
	 */ 
	public function testHasConstMODELLAZYLOADRELATIONSExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_LAZY_LOAD_RELATIONS'));
	}

	/**
	 * testMODELLIMITExists
	 *
	 * tests ERestEvent has const::MODEL_LIMIT 
	 */ 
	public function testHasConstMODELLIMITExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_LIMIT'));
	}

	/**
	 * testMODELOFFSETExists
	 *
	 * tests ERestEvent has const::MODEL_OFFSET
	 */ 
	public function testHasConstMODELOFFSETExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_OFFSET'));
	}

	/**
	 * testMODELSCENARIOExists
	 *
	 * tests ERestEvent has const::MODEL_SCENARIO 
	 */ 
	public function testHasConstMODELSCENARIOExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_SCENARIO'));
	}

	/**
	 * testMODELFILTERExists
	 *
	 * tests ERestEvent has const::MODEL_FILTER
	 */ 
	public function testHasConstMODELFILTERExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_FILTER'));
	}

	/**
	 * testMODELSORTExists
	 *
	 * tests ERestEvent has const::MODEL_SORT 
	 */ 
	public function testHasConstMODELSORTExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_SORT'));
	}

	/**
	 * testMODELFINDExists
	 *
	 * tests ERestEvent has const::MODEL_FIND 
	 */ 
	public function testHasConstMODELFINDExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_FIND'));
	}

	/**
	 * testMODELFINDALLExists
	 *
	 * tests ERestEvent has const::MODEL_FIND_ALL 
	 */ 
	public function testHasConstMODELFINDALLExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_FIND_ALL'));
	}

	/**
	 * testMODELCOUNTExists
	 *
	 * tests ERestEvent has const::MODEL_COUNT 
	 */ 
	public function testHasConstMODELCOUNTExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_COUNT'));
	}

	/**
	 * testMODELSUBRESOURCEFINDExists
	 *
	 * tests ERestEvent has const::MODEL_SUBRESOURCE_FIND 
	 */ 
	public function testHasConstMODELSUBRESOURCEFINDExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_SUBRESOURCE_FIND'));
	}

	/**
	 * testMODELSUBRESOURCESFINDALLExists
	 *
	 * tests ERestEvent has const::MODEL_SUBRESOURCES_FIND_ALL 
	 */ 
	public function testHasConstMODELSUBRESOURCESFINDALLExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_SUBRESOURCES_FIND_ALL'));
	}

	/**
	 * testMODELSUBRESOURCECOUNTExists
	 *
	 * tests ERestEvent has const::MODEL_SUBRESOURCE_COUNT 
	 */ 
	public function testHasConstMODELSUBRESOURCECOUNTExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_SUBRESOURCE_COUNT'));
	}

	/**
	 * testMODELAPPLYPOSTDATAExists
	 *
	 * tests ERestEvent has const::MODEL_APPLY_POST_DATA
	 */ 
	public function testHasConstMODELAPPLYPOSTDATAExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_APPLY_POST_DATA'));
	}

	/**
	 * testMODELAPPLYPUTDATAExists
	 *
	 * tests ERestEvent has const::MODEL_APPLY_PUT_DATA
	 */ 
	public function testHasConstMODELAPPLYPUTDATAExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_APPLY_PUT_DATA'));
	}

	/**
	 * testMODELSAVEExists
	 *
	 * tests ERestEvent has const::MODEL_SAVE 
	 */ 
	public function testHasConstMODELSAVEExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_SAVE'));
	}

	/**
	 * testMODELSUBRESOURCESAVEExists
	 *
	 * tests ERestEvent has const::MODEL_SUBRESOURCE_SAVE 
	 */ 
	public function testHasConstMODELSUBRESOURCESAVEExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_SUBRESOURCE_SAVE'));
	}

	/**
	 * testMODELDELETEExists
	 *
	 * tests ERestEvent has const::MODEL_DELETE
	 */ 
	public function testHasConstMODELDELETEExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_DELETE'));
	}

	/**
	 * testMODELSUBRESOURCEDELETEExists
	 *
	 * tests ERestEvent has const::MODEL_SUBRESOURCE_DELETE
	 */ 
	public function testHasConstMODELSUBRESOURCEDELETEExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_SUBRESOURCE_DELETE'));
	}

	/**
	 * testMODELRESTRICTEDPROPERTIESExists
	 *
	 * tests ERestEvent has const::MODEL_RESTRICTED_PROPERTIES 
	 */ 
	public function testHasConstMODELRESTRICTEDPROPERTIESExists()
	{
		$this->assertTrue(defined('ERestEvent::MODEL_RESTRICTED_PROPERTIES'));
	}
	
	/**
	 * testPREFILTERCONFIGAPPLICATIONIDExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_CONFIG_APPLICATION_ID
	 */ 
	public function testHasConstPREFILTERCONFIGAPPLICATIONIDExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_CONFIG_APPLICATION_ID'));
	}

	/**
	 * testPREFILTERCONFIGDEVFLAGExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_CONFIG_DEV_FLAG 
	 */ 
	public function testHasConstPREFILTERCONFIGDEVFLAGExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_CONFIG_DEV_FLAG'));
	}

	/**
	 * testPREFILTERREQAUTHUSERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AUTH_USER
	 */ 
	public function testHasConstPREFILTERREQAUTHUSERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AUTH_USER'));
	}

	/**
	 * testPREFILTERREQAUTHHTTPSONLYExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AUTH_HTTPS_ONLY 
	 */ 
	public function testHasConstPREFILTERREQAUTHHTTPSONLYExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AUTH_HTTPS_ONLY'));
	}

	/**
	 * testPREFILTERREQAUTHUSERNAMEExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AUTH_USERNAME
	 */ 
	public function testHasConstPREFILTERREQAUTHUSERNAMEExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AUTH_USERNAME'));
	}

	/**
	 * testPREFILTERREQAUTHPASSWORDExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AUTH_PASSWORD
	 */ 
	public function testHasConstPREFILTERREQAUTHPASSWORDExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AUTH_PASSWORD'));
	}

	/**
	 * testPREFILTERREQAUTHAJAXUSERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AUTH_AJAX_USER 
	 */ 
	public function testHasConstPREFILTERREQAUTHAJAXUSERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AUTH_AJAX_USER'));
	}

	/**
	 * testPREFILTERREQAFTERACTIONExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AFTER_ACTION
	 */ 
	public function testHasConstPREFILTERREQAFTERACTIONExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AFTER_ACTION'));
	}

	/**
	 * testPREFILTERREQPARAMISPKExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_PARAM_IS_PK 
	 */ 
	public function testHasConstPREFILTERREQPARAMISPKExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_PARAM_IS_PK'));
	}

	/**
	 * testPREFILTERREQDATAREADExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_DATA_READ
	 */ 
	public function testHasConstPREFILTERREQDATAREADExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_DATA_READ'));
	}

	/**
	 * testPREFILTERREQGETRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_GET_RESOURCE_RENDER 
	 */ 
	public function testHasConstPREFILTERREQGETRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_GET_RESOURCE_RENDER'));
	}

	/**
	 * testPREFILTERREQGETRESOURCESRENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_GET_RESOURCES_RENDER
	 */ 
	public function testHasConstPREFILTERREQGETRESOURCESRENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_GET_RESOURCES_RENDER'));
	}

	/**
	 * testPREFILTERREQPUTRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_PUT_RESOURCE_RENDER 
	 */ 
	public function testHasConstPREFILTERREQPUTRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_PUT_RESOURCE_RENDER'));
	}

	/**
	 * testPREFILTERREQPOSTRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_POST_RESOURCE_RENDER
	 */ 
	public function testHasConstPREFILTERREQPOSTRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_POST_RESOURCE_RENDER'));
	}

	/**
	 * testPREFILTERREQDELETERESOURCERENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_DELETE_RESOURCE_RENDER 
	 */ 
	public function testHasConstPREFILTERREQDELETERESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_DELETE_RESOURCE_RENDER'));
	}

	/**
	 * testPREFILTERREQGETSUBRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_GET_SUBRESOURCE_RENDER 
	 */ 
	public function testHasConstPREFILTERREQGETSUBRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_GET_SUBRESOURCE_RENDER'));
	}

	/**
	 * testPREFILTERREQGETSUBRESOURCESRENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_GET_SUBRESOURCES_RENDER 
	 */ 
	public function testHasConstPREFILTERREQGETSUBRESOURCESRENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_GET_SUBRESOURCES_RENDER'));
	}

	/**
	 * testPREFILTERREQPUTSUBRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_PUT_SUBRESOURCE_RENDER 
	 */ 
	public function testHasConstPREFILTERREQPUTSUBRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_PUT_SUBRESOURCE_RENDER'));
	}

	/**
	 * testPREFILTERREQDELETESUBRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_DELETE_SUBRESOURCE_RENDER
	 */ 
	public function testHasConstPREFILTERREQDELETESUBRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_DELETE_SUBRESOURCE_RENDER'));
	}

	/**
	 * testPREFILTERREQEXCEPTIONExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_EXCEPTION
	 */ 
	public function testHasConstPREFILTERREQEXCEPTIONExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_EXCEPTION'));
	}

	/**
	 * testPREFILTERREQRENDERJSONExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_RENDER_JSON 
	 */ 
	public function testHasConstPREFILTERREQRENDERJSONExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_RENDER_JSON'));
	}

	/**
	 * testPREFILTERMODELINSTANCEExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_INSTANCE 
	 */ 
	public function testHasConstPREFILTERMODELINSTANCEExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_INSTANCE'));
	}

	/**
	 * testPREFILTERMODELATTACHBEHAVIORSExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_ATTACH_BEHAVIORS
	 */ 
	public function testPREFILTERMODELATTACHBEHAVIORSExist()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_ATTACH_BEHAVIORS'));
	}

	/**
	 * testPREFILTERMODELWITHRELATIONSExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_WITH_RELATIONS
	 */ 
	public function testHasConstPREFILTERMODELWITHRELATIONSExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_WITH_RELATIONS'));
	}

	/**
	 * testPREFILTERMODELLAZYLOADRELATIONSExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_LAZY_LOAD_RELATIONS
	 */ 
	public function testHasConstPREFILTERMODELLAZYLOADRELATIONSExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_LAZY_LOAD_RELATIONS'));
	}

	/**
	 * testPREFILTERMODELLIMITExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_LIMIT 
	 */ 
	public function testHasConstPREFILTERMODELLIMITExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_LIMIT'));
	}

	/**
	 * testPREFILTERMODELOFFSETExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_OFFSET
	 */ 
	public function testHasConstPREFILTERMODELOFFSETExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_OFFSET'));
	}

	/**
	 * testPREFILTERMODELSCENARIOExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SCENARIO 
	 */ 
	public function testHasConstPREFILTERMODELSCENARIOExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SCENARIO'));
	}

	/**
	 * testPREFILTERMODELFILTERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_FILTER
	 */ 
	public function testHasConstPREFILTERMODELFILTERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_FILTER'));
	}

	/**
	 * testPREFILTERMODELSORTExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SORT 
	 */ 
	public function testHasConstPREFILTERMODELSORTExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SORT'));
	}

	/**
	 * testPREFILTERMODELFINDExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_FIND 
	 */ 
	public function testHasConstPREFILTERMODELFINDExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_FIND'));
	}

	/**
	 * testPREFILTERMODELFINDALLExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_FIND_ALL 
	 */ 
	public function testHasConstPREFILTERMODELFINDALLExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_FIND_ALL'));
	}

	/**
	 * testPREFILTERMODELCOUNTExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_COUNT 
	 */ 
	public function testHasConstPREFILTERMODELCOUNTExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_COUNT'));
	}

	/**
	 * testPREFILTERMODELSUBRESOURCEFINDExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SUBRESOURCE_FIND 
	 */ 
	public function testHasConstPREFILTERMODELSUBRESOURCEFINDExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SUBRESOURCE_FIND'));
	}

	/**
	 * testPREFILTERMODELSUBRESOURCESFINDALLExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SUBRESOURCES_FIND_ALL 
	 */ 
	public function testHasConstPREFILTERMODELSUBRESOURCESFINDALLExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SUBRESOURCES_FIND_ALL'));
	}

	/**
	 * testPREFILTERMODELSUBRESOURCECOUNTExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SUBRESOURCE_COUNT 
	 */ 
	public function testHasConstPREFILTERMODELSUBRESOURCECOUNTExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SUBRESOURCE_COUNT'));
	}

	/**
	 * testPREFILTERMODELAPPLYPOSTDATAExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_APPLY_POST_DATA
	 */ 
	public function testHasConstPREFILTERMODELAPPLYPOSTDATAExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_APPLY_POST_DATA'));
	}

	/**
	 * testPREFILTERMODELAPPLYPUTDATAExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_APPLY_PUT_DATA
	 */ 
	public function testHasConstPREFILTERMODELAPPLYPUTDATAExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_APPLY_PUT_DATA'));
	}

	/**
	 * testPREFILTERMODELSAVEExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SAVE 
	 */ 
	public function testHasConstPREFILTERMODELSAVEExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SAVE'));
	}

	/**
	 * testPREFILTERMODELSUBRESOURCESAVEExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SUBRESOURCE_SAVE 
	 */ 
	public function testHasConstPREFILTERMODELSUBRESOURCESAVEExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SUBRESOURCE_SAVE'));
	}

	/**
	 * testPREFILTERMODELDELETEExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_DELETE
	 */ 
	public function testHasConstPREFILTERMODELDELETEExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_DELETE'));
	}

	/**
	 * testPREFILTERMODELSUBRESOURCEDELETEExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SUBRESOURCE_DELETE
	 */ 
	public function testHasConstPREFILTERMODELSUBRESOURCEDELETEExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SUBRESOURCE_DELETE'));
	}

	/**
	 * testPREFILTERMODELRESTRICTEDPROPERTIESExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_RESTRICTED_PROPERTIES 
	 */ 
	public function testHasConstPREFILTERMODELRESTRICTEDPROPERTIESExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_RESTRICTED_PROPERTIES'));
	}

		/**
	 * testPOSTFILTERCONFIGAPPLICATIONIDExists
	 *
	 * tests ERestEvent has const::POST_FILTER_CONFIG_APPLICATION_ID
	 */ 
	public function testHasConstPOSTFILTERCONFIGAPPLICATIONIDExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_CONFIG_APPLICATION_ID'));
	}

	/**
	 * testPOSTFILTERCONFIGDEVFLAGExists
	 *
	 * tests ERestEvent has const::POST_FILTER_CONFIG_DEV_FLAG 
	 */ 
	public function testHasConstPOSTFILTERCONFIGDEVFLAGExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_CONFIG_DEV_FLAG'));
	}

	/**
	 * testPOSTFILTERREQAUTHUSERExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_AUTH_USER
	 */ 
	public function testHasConstPOSTFILTERREQAUTHUSERExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AUTH_USER'));
	}

	/**
	 * testPOSTFILTERREQAUTHHTTPSONLYExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_AUTH_HTTPS_ONLY 
	 */ 
	public function testHasConstPOSTFILTERREQAUTHHTTPSONLYExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AUTH_HTTPS_ONLY'));
	}

	/**
	 * testPOSTFILTERREQAUTHUSERNAMEExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_AUTH_USERNAME
	 */ 
	public function testHasConstPOSTFILTERREQAUTHUSERNAMEExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AUTH_USERNAME'));
	}

	/**
	 * testPOSTFILTERREQAUTHPASSWORDExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_AUTH_PASSWORD
	 */ 
	public function testHasConstPOSTFILTERREQAUTHPASSWORDExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AUTH_PASSWORD'));
	}

	/**
	 * testPOSTFILTERREQAUTHAJAXUSERExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_AUTH_AJAX_USER 
	 */ 
	public function testHasConstPOSTFILTERREQAUTHAJAXUSERExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AUTH_AJAX_USER'));
	}

	/**
	 * testPOSTFILTERREQAFTERACTIONExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_AFTER_ACTION
	 */ 
	public function testHasConstPOSTFILTERREQAFTERACTIONExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AFTER_ACTION'));
	}

	/**
	 * testPOSTFILTERREQPARAMISPKExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_PARAM_IS_PK 
	 */ 
	public function testHasConstPOSTFILTERREQPARAMISPKExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_PARAM_IS_PK'));
	}

	/**
	 * testPOSTFILTERREQDATAREADExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_DATA_READ
	 */ 
	public function testHasConstPOSTFILTERREQDATAREADExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_DATA_READ'));
	}

	/**
	 * testPOSTFILTERMODELINSTANCEExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_INSTANCE 
	 */ 
	public function testHasConstPOSTFILTERMODELINSTANCEExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_INSTANCE'));
	}

	/**
	 * testPOSTFILTERMODELATTACHBEHAVIORSExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_ATTACH_BEHAVIORS
	 */ 
	public function testPOSTFILTERMODELATTACHBEHAVIORSExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_ATTACH_BEHAVIORS'));
	}

	/**
	 * testPOSTFILTERMODELWITHRELATIONSExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_WITH_RELATIONS
	 */ 
	public function testHasConstPOSTFILTERMODELWITHRELATIONSExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_WITH_RELATIONS'));
	}

	/**
	 * testPOSTFILTERMODELLAZYLOADRELATIONSExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_LAZY_LOAD_RELATIONS
	 */ 
	public function testHasConstPOSTFILTERMODELLAZYLOADRELATIONSExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_LAZY_LOAD_RELATIONS'));
	}

	/**
	 * testPOSTFILTERMODELLIMITExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_LIMIT 
	 */ 
	public function testHasConstPOSTFILTERMODELLIMITExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_LIMIT'));
	}

	/**
	 * testPOSTFILTERMODELOFFSETExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_OFFSET
	 */ 
	public function testHasConstPOSTFILTERMODELOFFSETExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_OFFSET'));
	}

	/**
	 * testPOSTFILTERMODELSCENARIOExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SCENARIO 
	 */ 
	public function testHasConstPOSTFILTERMODELSCENARIOExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SCENARIO'));
	}

	/**
	 * testPOSTFILTERMODELFILTERExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_FILTER
	 */ 
	public function testHasConstPOSTFILTERMODELFILTERExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_FILTER'));
	}

	/**
	 * testPOSTFILTERMODELSORTExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SORT 
	 */ 
	public function testHasConstPOSTFILTERMODELSORTExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SORT'));
	}

	/**
	 * testPOSTFILTERMODELFINDExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_FIND 
	 */ 
	public function testHasConstPOSTFILTERMODELFINDExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_FIND'));
	}

	/**
	 * testPOSTFILTERMODELFINDALLExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_FIND_ALL 
	 */ 
	public function testHasConstPOSTFILTERMODELFINDALLExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_FIND_ALL'));
	}

	/**
	 * testPOSTFILTERMODELCOUNTExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_COUNT 
	 */ 
	public function testHasConstPOSTFILTERMODELCOUNTExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_COUNT'));
	}

	/**
	 * testPOSTFILTERMODELSUBRESOURCEFINDExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SUBRESOURCE_FIND 
	 */ 
	public function testHasConstPOSTFILTERMODELSUBRESOURCEFINDExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SUBRESOURCE_FIND'));
	}

	/**
	 * testPOSTFILTERMODELSUBRESOURCESFINDALLExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SUBRESOURCES_FIND_ALL 
	 */ 
	public function testHasConstPOSTFILTERMODELSUBRESOURCESFINDALLExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SUBRESOURCES_FIND_ALL'));
	}

	/**
	 * testPOSTFILTERMODELSUBRESOURCECOUNTExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SUBRESOURCE_COUNT 
	 */ 
	public function testHasConstPOSTFILTERMODELSUBRESOURCECOUNTExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SUBRESOURCE_COUNT'));
	}

	/**
	 * testPOSTFILTERMODELAPPLYPOSTDATAExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_APPLY_POST_DATA
	 */ 
	public function testHasConstPOSTFILTERMODELAPPLYPOSTDATAExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_APPLY_POST_DATA'));
	}

	/**
	 * testPOSTFILTERMODELAPPLYPUTDATAExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_APPLY_PUT_DATA
	 */ 
	public function testHasConstPOSTFILTERMODELAPPLYPUTDATAExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_APPLY_PUT_DATA'));
	}

	/**
	 * testPOSTFILTERMODELSAVEExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SAVE 
	 */ 
	public function testHasConstPOSTFILTERMODELSAVEExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SAVE'));
	}

	/**
	 * testPOSTFILTERMODELSUBRESOURCESAVEExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SUBRESOURCE_SAVE 
	 */ 
	public function testHasConstPOSTFILTERMODELSUBRESOURCESAVEExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SUBRESOURCE_SAVE'));
	}

	/**
	 * testPOSTFILTERMODELDELETEExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_DELETE
	 */ 
	public function testHasConstPOSTFILTERMODELDELETEExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_DELETE'));
	}

	/**
	 * testPOSTFILTERMODELSUBRESOURCEDELETEExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SUBRESOURCE_DELETE
	 */ 
	public function testHasConstPOSTFILTERMODELSUBRESOURCEDELETEExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SUBRESOURCE_DELETE'));
	}

	/**
	 * testPOSTFILTERMODELRESTRICTEDPROPERTIESExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_RESTRICTED_PROPERTIES 
	 */ 
	public function testHasConstPOSTFILTERMODELRESTRICTEDPROPERTIESExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_RESTRICTED_PROPERTIES'));
	}

}
