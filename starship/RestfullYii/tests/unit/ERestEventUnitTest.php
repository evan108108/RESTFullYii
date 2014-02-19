<?php
Yii::import('RestfullYii.events.ERestEvent');

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
	 * testREQAUTHTYPExists
	 *
	 * tests ERestEvent has const::REQ_AUTH_TYPE
	 */ 
	public function testREQAUTHTYPExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_AUTH_TYPE'));
	}

	/**
	 * testREQISSUBRESOURCEExists
	 *
	 * tests ERestEvent has const::REQ_IS_SUBRESOURCE
	 */ 
	public function testREQISSUBRESOURCEExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_OPTIONS_RENDER'));
	}

	/**
	 * testPREFILTERREQISSUBRESOURCEExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_IS_SUBRESOURCE
	 */ 
	public function testPREFILTERREQISSUBRESOURCEExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_OPTIONS_RENDER'));
	}


	/**
	 * testPOSTFILTERREQISSUBRESOURCEExists
	 *
	 * tests ERestEvent has const::REQ_IS_SUBRESOURCE
	 */ 
	public function testPOSTFILTERREQISSUBRESOURCEExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_OPTIONS_RENDER'));
	}

	/**
	 * testREQOPTIONSRENDERExists
	 *
	 * tests ERestEvent has const::REQ_OPTIONS_RENDER
	 */ 
	public function testREQOPTIONSRENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_OPTIONS_RENDER'));
	}

	/**
	 * testPREFILTERREQOPTIONSRENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_OPTIONS_RENDER
	 */ 
	public function testPREFILTERREQOPTIONSRENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_OPTIONS_RENDER'));
	}

	/**
	 * testPOSTFILTERREQOPTIONSRENDERExists
	 *
	 * tests ERestEvent has const::POST_FILTERREQ_OPTIONS_RENDER
	 */ 
	public function testPOSTFILTERREQOPTIONSRENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_OPTIONS_RENDER'));
	}

	/**
	 * testREQCORSACCESSCONTROLALLOWORIGINExists
	 *
	 * tests ERestEvent has const::REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN
	 */ 
	public function testREQCORSACCESSCONTROLALLOWORIGINExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN'));
	}

	
	/**
	 * testPREFILTERREQCORSACCESSCONTROLALLOWORIGINExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN
	 */ 
	public function testPREFILTERREQCORSACCESSCONTROLALLOWORIGINExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN'));
	}

	/**
	 * testPOSTFILTERREQCORSACCESSCONTROLALLOWORIGINExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN
	 */ 
	public function testPOSTFILTERREQCORSACCESSCONTROLALLOWORIGINExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN'));
	}

	/**
	 * testREQCORSACCESSCONTROLALLOWMETHODSExists
	 *
	 * tests ERestEvent has const::REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN
	 */ 
	public function testREQCORSACCESSCONTROLALLOWMETHODSExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_CORS_ACCESS_CONTROL_ALLOW_METHODS'));
	}

	
	/**
	 * testPREFILTERREQCORSACCESSCONTROLALLOWMETHODSExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN
	 */ 
	public function testPREFILTERREQCORSACCESSCONTROLALLOWMETHODSExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_METHODS'));
	}

	/**
	 * testPOSTFILTERREQCORSACCESSCONTROLALLOWMETHODSExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_METHODS
	 */ 
	public function testPOSTFILTERREQCORSACCESSCONTROLALLOWMETHODSExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_METHODS'));
	}

	/**
	 * testREQCORSACCESSCONTROLALLOWHEADERSExists
	 *
	 * tests ERestEvent has const::REQ_CORS_ACCESS_CONTROL_ALLOW_HEADERS
	 */ 
	public function testREQCORSACCESSCONTROLALLOWHEADERSExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_CORS_ACCESS_CONTROL_ALLOW_HEADERS'));
	}

	
	/**
	 * testPREFILTERREQCORSACCESSCONTROLALLOWMETHODSExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_HEADERS
	 */ 
	public function testPREFILTERREQCORSACCESSCONTROLALLOWHEADERSExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_HEADERS'));
	}

	/**
	 * testPOSTFILTERREQCORSACCESSCONTROLALLOWHEADERSExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_HEADERS
	 */ 
	public function testPOSTFILTERREQCORSACCESSCONTROLALLOWHEADERSExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_HEADERS'));
	}

	/**
	 * testREQCORSACCESSCONTROLMAXAGEExists
	 *
	 * tests ERestEvent has const::REQ_CORS_ACCESS_CONTROL_MAX_AGE
	 */ 
	public function testREQCORSACCESSCONTROLMAXAGEExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_CORS_ACCESS_CONTROL_MAX_AGE'));
	}

	
	/**
	 * testPREFILTERREQCORSACCESSCONTROLMAXAGEExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_CORS_ACCESS_CONTROL_MAX_AGE
	 */ 
	public function testPREFILTERREQCORSACCESSCONTROLMAXAGEExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_CORS_ACCESS_CONTROL_MAX_AGE'));
	}

	/**
	 * testPOSTFILTERREQCORSACCESSCONTROLMAXAGEExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_CORS_ACCESS_CONTROL_MAX_AGE
	 */ 
	public function testPOSTFILTERREQCORSACCESSCONTROLMAXAGEExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_CORS_ACCESS_CONTROL_MAX_AGE'));
	}

	
	/**
	 * testREQDISABLECWEBLOGROUTE
	 *
	 * tests ERestEvent has const::REQ_DISABLE_CWEBLOGROUTE
	 */
	public function testREQDISABLECWEBLOGROUTE()
	{
		$this->assertTrue(defined('ERestEvent::REQ_DISABLE_CWEBLOGROUTE'));
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
	 * testREQAUTCORSHExists
	 *
	 * tests ERestEvent has const::REQ_AUTH_CORS
	 */ 
	public function testHasConstREQAUTHCORSExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_AUTH_CORS'));
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
	 * testREQAUTHURI
	 *
	 * tests ERestEvent has const::REQ_AUTH_URI 
	 */ 
	public function testHasConstREQAUTHURIExists()
	{
		$this->assertTrue(defined('ERestEvent::REQ_AUTH_URI'));
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
	 * testHasConstPREFILTERCONFIGAPPLICATIONIDExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_CONFIG_APPLICATION_ID
	 */ 
	public function testHasConstPREFILTERCONFIGAPPLICATIONIDExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_CONFIG_APPLICATION_ID'));
	}

	/**
	 * testHasConstPREFILTERCONFIGDEVFLAGExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_CONFIG_DEV_FLAG 
	 */ 
	public function testHasConstPREFILTERCONFIGDEVFLAGExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_CONFIG_DEV_FLAG'));
	}

	/**
	 * testHasConstPREFILTERREQEVENTLOGGERRExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_EVENT_LOGGER
	 */ 
	public function testHasConstPREFILTERREQEVENTLOGGERRExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_EVENT_LOGGER'));
	}

	/**
	 * testHasConstPREFILTERREQAUTHTYPExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AUTH_TYPE
	 */ 
	public function testHasConstPREFILTERREQAUTHTYPExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AUTH_TYPE'));
	}

	/**
	 * testHasConstPREFILTERREQDISABLECWEBLOGROUTE
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_DISABLE_CWEBLOGROUTE
	 */
	public function testHasConstPREFILTERRREQDISABLECWEBLOGROUTE()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_DISABLE_CWEBLOGROUTE'));
	}


	/**
	 * testHasConstPREFILTERREQAUTHUSERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AUTH_USER
	 */ 
	public function testHasConstPREFILTERREQAUTHUSERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AUTH_USER'));
	}

	/**
	 * testPREFILTERREQAUTCORSHExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AUTH_CORS
	 */ 
	public function testHasConstPREFILTERREQAUTHCORSExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AUTH_CORS'));
	}

	/**
	 * testHasConstPREFILTERREQAUTHHTTPSONLYExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AUTH_HTTPS_ONLY 
	 */ 
	public function testHasConstPREFILTERREQAUTHHTTPSONLYExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AUTH_HTTPS_ONLY'));
	}

	/**
	 * testHasConstPREFILTERREQAUTHUSERNAMEExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AUTH_USERNAME
	 */ 
	public function testHasConstPREFILTERREQAUTHUSERNAMEExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AUTH_USERNAME'));
	}

	/**
	 * testHasConstPREFILTERREQAUTHPASSWORDExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AUTH_PASSWORD
	 */ 
	public function testHasConstPREFILTERREQAUTHPASSWORDExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AUTH_PASSWORD'));
	}

	/**
	 * testHasConstPREFILTERREQAUTHAJAXUSERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AUTH_AJAX_USER 
	 */ 
	public function testHasConstPREFILTERREQAUTHAJAXUSERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AUTH_AJAX_USER'));
    }

    /**
	 * testHasConstPREFILTERREQAUTHURI
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AUTH_URI 
	 */ 
	public function testHasConstPREFILTERREQAUTHURIExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AUTH_URI'));
	}

	/**
	 * testHasConstPREFILTERREQAFTERACTIONExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_AFTER_ACTION
	 */ 
	public function testHasConstPREFILTERREQAFTERACTIONExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_AFTER_ACTION'));
	}

	/**
	 * testHasConstPREFILTERREQPARAMISPKExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_PARAM_IS_PK 
	 */ 
	public function testHasConstPREFILTERREQPARAMISPKExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_PARAM_IS_PK'));
	}

	/**
	 * testHasConstPREFILTERREQDATAREADExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_DATA_READ
	 */ 
	public function testHasConstPREFILTERREQDATAREADExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_DATA_READ'));
	}

	/**
	 * testHasConstPREFILTERREQGETRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_GET_RESOURCE_RENDER 
	 */ 
	public function testHasConstPREFILTERREQGETRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_GET_RESOURCE_RENDER'));
	}

	/**
	 * testHasConstPREFILTERREQGETRESOURCESRENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_GET_RESOURCES_RENDER
	 */ 
	public function testHasConstPREFILTERREQGETRESOURCESRENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_GET_RESOURCES_RENDER'));
	}

	/**
	 * testHasConstPREFILTERREQPUTRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_PUT_RESOURCE_RENDER 
	 */ 
	public function testHasConstPREFILTERREQPUTRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_PUT_RESOURCE_RENDER'));
	}

	/**
	 * testHasConstPREFILTERREQPOSTRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_POST_RESOURCE_RENDER
	 */ 
	public function testHasConstPREFILTERREQPOSTRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_POST_RESOURCE_RENDER'));
	}

	/**
	 * testHasConstPREFILTERREQDELETERESOURCERENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_DELETE_RESOURCE_RENDER 
	 */ 
	public function testHasConstPREFILTERREQDELETERESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_DELETE_RESOURCE_RENDER'));
	}

	/**
	 * testHasConstPREFILTERREQGETSUBRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_GET_SUBRESOURCE_RENDER 
	 */ 
	public function testHasConstPREFILTERREQGETSUBRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_GET_SUBRESOURCE_RENDER'));
	}

	/**
	 * testHasConstPREFILTERREQGETSUBRESOURCESRENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_GET_SUBRESOURCES_RENDER 
	 */ 
	public function testHasConstPREFILTERREQGETSUBRESOURCESRENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_GET_SUBRESOURCES_RENDER'));
	}

	/**
	 * testHasConstPREFILTERREQPUTSUBRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_PUT_SUBRESOURCE_RENDER 
	 */ 
	public function testHasConstPREFILTERREQPUTSUBRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_PUT_SUBRESOURCE_RENDER'));
	}

	/**
	 * testHasConstPREFILTERREQDELETESUBRESOURCERENDERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_DELETE_SUBRESOURCE_RENDER
	 */ 
	public function testHasConstPREFILTERREQDELETESUBRESOURCERENDERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_DELETE_SUBRESOURCE_RENDER'));
	}

	/**
	 * testHasConstPREFILTERREQEXCEPTIONExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_EXCEPTION
	 */ 
	public function testHasConstPREFILTERREQEXCEPTIONExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_EXCEPTION'));
	}

	/**
	 * testHasConstPREFILTERREQRENDERJSONExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_REQ_RENDER_JSON 
	 */ 
	public function testHasConstPREFILTERREQRENDERJSONExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_REQ_RENDER_JSON'));
	}

	/**
	 * testHasConstPREFILTERMODELINSTANCEExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_INSTANCE 
	 */ 
	public function testHasConstPREFILTERMODELINSTANCEExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_INSTANCE'));
	}

	/**
	 * testHasConstPREFILTERMODELATTACHBEHAVIORSExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_ATTACH_BEHAVIORS
	 */ 
	public function testHasConstPREFILTERMODELATTACHBEHAVIORSExist()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_ATTACH_BEHAVIORS'));
	}

	/**
	 * testHasConstPREFILTERMODELWITHRELATIONSExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_WITH_RELATIONS
	 */ 
	public function testHasConstPREFILTERMODELWITHRELATIONSExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_WITH_RELATIONS'));
	}

	/**
	 * testHasConstPREFILTERMODELLAZYLOADRELATIONSExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_LAZY_LOAD_RELATIONS
	 */ 
	public function testHasConstPREFILTERMODELLAZYLOADRELATIONSExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_LAZY_LOAD_RELATIONS'));
	}

	/**
	 * testHasConstPREFILTERMODELLIMITExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_LIMIT 
	 */ 
	public function testHasConstPREFILTERMODELLIMITExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_LIMIT'));
	}

	/**
	 * testHasConstPREFILTERMODELOFFSETExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_OFFSET
	 */ 
	public function testHasConstPREFILTERMODELOFFSETExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_OFFSET'));
	}

	/**
	 * testHasConstPREFILTERMODELSCENARIOExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SCENARIO 
	 */ 
	public function testHasConstPREFILTERMODELSCENARIOExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SCENARIO'));
	}

	/**
	 * testHasConstPREFILTERMODELFILTERExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_FILTER
	 */ 
	public function testHasConstPREFILTERMODELFILTERExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_FILTER'));
	}

	/**
	 * testHasConstPREFILTERMODELSORTExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SORT 
	 */ 
	public function testHasConstPREFILTERMODELSORTExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SORT'));
	}

	/**
	 * testHasConstPREFILTERMODELFINDExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_FIND 
	 */ 
	public function testHasConstPREFILTERMODELFINDExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_FIND'));
	}

	/**
	 * testHasConstPREFILTERMODELFINDALLExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_FIND_ALL 
	 */ 
	public function testHasConstPREFILTERMODELFINDALLExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_FIND_ALL'));
	}

	/**
	 * testHasConstPREFILTERMODELCOUNTExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_COUNT 
	 */ 
	public function testHasConstPREFILTERMODELCOUNTExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_COUNT'));
	}

	/**
	 * testHasConstPREFILTERMODELSUBRESOURCEFINDExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SUBRESOURCE_FIND 
	 */ 
	public function testHasConstPREFILTERMODELSUBRESOURCEFINDExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SUBRESOURCE_FIND'));
	}

	/**
	 * testHasConstPREFILTERMODELSUBRESOURCESFINDALLExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SUBRESOURCES_FIND_ALL 
	 */ 
	public function testHasConstPREFILTERMODELSUBRESOURCESFINDALLExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SUBRESOURCES_FIND_ALL'));
	}

	/**
	 * testHasConstPREFILTERMODELSUBRESOURCECOUNTExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SUBRESOURCE_COUNT 
	 */ 
	public function testHasConstPREFILTERMODELSUBRESOURCECOUNTExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SUBRESOURCE_COUNT'));
	}

	/**
	 * testHasConstPREFILTERMODELAPPLYPOSTDATAExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_APPLY_POST_DATA
	 */ 
	public function testHasConstPREFILTERMODELAPPLYPOSTDATAExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_APPLY_POST_DATA'));
	}

	/**
	 * testHasConstPREFILTERMODELAPPLYPUTDATAExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_APPLY_PUT_DATA
	 */ 
	public function testHasConstPREFILTERMODELAPPLYPUTDATAExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_APPLY_PUT_DATA'));
	}

	/**
	 * testHasConstPREFILTERMODELSAVEExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SAVE 
	 */ 
	public function testHasConstPREFILTERMODELSAVEExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SAVE'));
	}

	/**
	 * testHasConstPREFILTERMODELSUBRESOURCESAVEExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SUBRESOURCE_SAVE 
	 */ 
	public function testHasConstPREFILTERMODELSUBRESOURCESAVEExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SUBRESOURCE_SAVE'));
	}

	/**
	 * testHasConstPREFILTERMODELDELETEExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_DELETE
	 */ 
	public function testHasConstPREFILTERMODELDELETEExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_DELETE'));
	}

	/**
	 * testHasConstPREFILTERMODELSUBRESOURCEDELETEExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_SUBRESOURCE_DELETE
	 */ 
	public function testHasConstPREFILTERMODELSUBRESOURCEDELETEExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_SUBRESOURCE_DELETE'));
	}

	/**
	 * testHasConstPREFILTERMODELRESTRICTEDPROPERTIESExists
	 *
	 * tests ERestEvent has const::PRE_FILTER_MODEL_RESTRICTED_PROPERTIES 
	 */ 
	public function testHasConstPREFILTERMODELRESTRICTEDPROPERTIESExists()
	{
		$this->assertTrue(defined('ERestEvent::PRE_FILTER_MODEL_RESTRICTED_PROPERTIES'));
	}

	/**
	 * testHasConstPOSTFILTERCONFIGAPPLICATIONIDExists
	 *
	 * tests ERestEvent has const::POST_FILTER_CONFIG_APPLICATION_ID
	 */ 
	public function testHasConstPOSTFILTERCONFIGAPPLICATIONIDExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_CONFIG_APPLICATION_ID'));
	}

	/**
	 * testHasConstPOSTFILTERCONFIGDEVFLAGExists
	 *
	 * tests ERestEvent has const::POST_FILTER_CONFIG_DEV_FLAG 
	 */ 
	public function testHasConstPOSTFILTERCONFIGDEVFLAGExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_CONFIG_DEV_FLAG'));
	}

	/**
	 * testHasConstPOSTFILTERREQEVENTLOGGERRExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_EVENT_LOGGER
	 */ 
	public function testHasConstPOSTFILTERREQEVENTLOGGERRExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_EVENT_LOGGER'));
	}

	/**
	 * testHasConstPOSTFILTERREQAUTHTYPExists
	 *
	 * tests ERestEvent has const::PoST_FILTER_REQ_AUTH_TYPE
	 */ 
	public function testHasConstPOSTFILTERREQAUTHTYPExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AUTH_TYPE'));
	}

	/**
	 * testHasConstPOSTFILTERREQDISABLECWEBLOGROUTE
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_DISABLE_CWEBLOGROUTE
	 */
	public function testHasConstPOSTFILTERRREQDISABLECWEBLOGROUTE()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_DISABLE_CWEBLOGROUTE'));
	}


	/**
	 * testHasConstPOSTFILTERREQAUTHUSERExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_AUTH_USER
	 */ 
	public function testHasConstPOSTFILTERREQAUTHUSERExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AUTH_USER'));
	}

	/**
	 * testHasConstPOSTFILTERREQAUTHHTTPSONLYExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_AUTH_HTTPS_ONLY 
	 */ 
	public function testHasConstPOSTFILTERREQAUTHHTTPSONLYExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AUTH_HTTPS_ONLY'));
	}

	/**
	 * testHasConstPOSTFILTERREQAUTHUSERNAMEExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_AUTH_USERNAME
	 */ 
	public function testHasConstPOSTFILTERREQAUTHUSERNAMEExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AUTH_USERNAME'));
	}

	/**
	 * testHasConstPOSTFILTERREQAUTHPASSWORDExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_AUTH_PASSWORD
	 */ 
	public function testHasConstPOSTFILTERREQAUTHPASSWORDExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AUTH_PASSWORD'));
	}

	/**
	 * testHasConstPOSTFILTERREQAUTHAJAXUSERExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_AUTH_AJAX_USER 
	 */ 
	public function testHasConstPOSTFILTERREQAUTHAJAXUSERExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AUTH_AJAX_USER'));
    }

    /**
	 * testHasConstPOSTFILTERREQAUTHURIExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_AUTH_URI 
	 */ 
	public function testHasConstPOSTFILTERREQAUTHURIExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AUTH_URI'));
	}

	/**
	 * testHasConstPOSTFILTERREQAFTERACTIONExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_AFTER_ACTION
	 */ 
	public function testHasConstPOSTFILTERREQAFTERACTIONExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AFTER_ACTION'));
	}

	/**
	 * testHasConstPOSTFILTERREQPARAMISPKExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_PARAM_IS_PK 
	 */ 
	public function testHasConstPOSTFILTERREQPARAMISPKExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_PARAM_IS_PK'));
	}

		/**
	 * testPOSTFILTERREQAUTCORSHExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_AUTH_CORS
	 */ 
	public function testHasConstPOSTFILTERREQAUTHCORSExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_AUTH_CORS'));
	}

	/**
	 * testHasConstPOSTFILTERREQDATAREADExists
	 *
	 * tests ERestEvent has const::POST_FILTER_REQ_DATA_READ
	 */ 
	public function testHasConstPOSTFILTERREQDATAREADExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_REQ_DATA_READ'));
	}

	/**
	 * testHasConstPOSTFILTERMODELINSTANCEExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_INSTANCE 
	 */ 
	public function testHasConstPOSTFILTERMODELINSTANCEExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_INSTANCE'));
	}

	/**
	 * testHasConstPOSTFILTERMODELATTACHBEHAVIORSExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_ATTACH_BEHAVIORS
	 */ 
	public function testHasConstPOSTFILTERMODELATTACHBEHAVIORSExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_ATTACH_BEHAVIORS'));
	}

	/**
	 * testHasConstPOSTFILTERMODELWITHRELATIONSExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_WITH_RELATIONS
	 */ 
	public function testHasConstPOSTFILTERMODELWITHRELATIONSExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_WITH_RELATIONS'));
	}

	/**
	 * testHasConstPOSTFILTERMODELLAZYLOADRELATIONSExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_LAZY_LOAD_RELATIONS
	 */ 
	public function testHasConstPOSTFILTERMODELLAZYLOADRELATIONSExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_LAZY_LOAD_RELATIONS'));
	}

	/**
	 * testHasConstPOSTFILTERMODELLIMITExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_LIMIT 
	 */ 
	public function testHasConstPOSTFILTERMODELLIMITExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_LIMIT'));
	}

	/**
	 * testHasConstPOSTFILTERMODELOFFSETExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_OFFSET
	 */ 
	public function testHasConstPOSTFILTERMODELOFFSETExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_OFFSET'));
	}

	/**
	 * testHasConstPOSTFILTERMODELSCENARIOExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SCENARIO 
	 */ 
	public function testHasConstPOSTFILTERMODELSCENARIOExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SCENARIO'));
	}

	/**
	 * testHasConstPOSTFILTERMODELFILTERExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_FILTER
	 */ 
	public function testHasConstPOSTFILTERMODELFILTERExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_FILTER'));
	}

	/**
	 * testHasConstPOSTFILTERMODELSORTExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SORT 
	 */ 
	public function testHasConstPOSTFILTERMODELSORTExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SORT'));
	}

	/**
	 * testHasConstPOSTFILTERMODELFINDExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_FIND 
	 */ 
	public function testHasConstPOSTFILTERMODELFINDExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_FIND'));
	}

	/**
	 * testHasConstPOSTFILTERMODELFINDALLExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_FIND_ALL 
	 */ 
	public function testHasConstPOSTFILTERMODELFINDALLExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_FIND_ALL'));
	}

	/**
	 * testHasConstPOSTFILTERMODELCOUNTExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_COUNT 
	 */ 
	public function testHasConstPOSTFILTERMODELCOUNTExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_COUNT'));
	}

	/**
	 * testHasConstPOSTFILTERMODELSUBRESOURCEFINDExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SUBRESOURCE_FIND 
	 */ 
	public function testHasConstPOSTFILTERMODELSUBRESOURCEFINDExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SUBRESOURCE_FIND'));
	}

	/**
	 * testHasConstPOSTFILTERMODELSUBRESOURCESFINDALLExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SUBRESOURCES_FIND_ALL 
	 */ 
	public function testHasConstPOSTFILTERMODELSUBRESOURCESFINDALLExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SUBRESOURCES_FIND_ALL'));
	}

	/**
	 * testHasConstPOSTFILTERMODELSUBRESOURCECOUNTExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SUBRESOURCE_COUNT 
	 */ 
	public function testHasConstPOSTFILTERMODELSUBRESOURCECOUNTExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SUBRESOURCE_COUNT'));
	}

	/**
	 * testHasConstPOSTFILTERMODELAPPLYPOSTDATAExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_APPLY_POST_DATA
	 */ 
	public function testHasConstPOSTFILTERMODELAPPLYPOSTDATAExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_APPLY_POST_DATA'));
	}

	/**
	 * testHasConstPOSTFILTERMODELAPPLYPUTDATAExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_APPLY_PUT_DATA
	 */ 
	public function testHasConstPOSTFILTERMODELAPPLYPUTDATAExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_APPLY_PUT_DATA'));
	}

	/**
	 * testHasConstPOSTFILTERMODELSAVEExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SAVE 
	 */ 
	public function testHasConstPOSTFILTERMODELSAVEExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SAVE'));
	}

	/**
	 * testHasConstPOSTFILTERMODELSUBRESOURCESAVEExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SUBRESOURCE_SAVE 
	 */ 
	public function testHasConstPOSTFILTERMODELSUBRESOURCESAVEExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SUBRESOURCE_SAVE'));
	}

	/**
	 * testHasConstPOSTFILTERMODELDELETEExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_DELETE
	 */ 
	public function testHasConstPOSTFILTERMODELDELETEExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_DELETE'));
	}

	/**
	 * testHasConstPOSTFILTERMODELSUBRESOURCEDELETEExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_SUBRESOURCE_DELETE
	 */ 
	public function testHasConstPOSTFILTERMODELSUBRESOURCEDELETEExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_SUBRESOURCE_DELETE'));
	}

	/**
	 * testHasConstPOSTFILTERMODELRESTRICTEDPROPERTIESExists
	 *
	 * tests ERestEvent has const::POST_FILTER_MODEL_RESTRICTED_PROPERTIES 
	 */ 
	public function testHasConstPOSTFILTERMODELRESTRICTEDPROPERTIESExists()
	{
		$this->assertTrue(defined('ERestEvent::POST_FILTER_MODEL_RESTRICTED_PROPERTIES'));
	}

}
