<?php
/**
 * ERestEvent
 *
 * Acts as a scope to maintain list of event names
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/events
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestEvent
{
	CONST CONFIG_APPLICATION_ID																	= 'config.application.id';	
	CONST CONFIG_DEV_FLAG																				= 'config.dev.flag';

	CONST REQ_EVENT_LOGGER																			= 'req.event.logger';
	CONST REQ_DISABLE_CWEBLOGROUTE															= 'req.disable.cweblogroute';
	CONST REQ_AUTH_USER																					= 'req.auth.user';
	CONST REQ_AUTH_HTTPS_ONLY																		= 'req.auth.https.only';
	CONST REQ_AUTH_USERNAME																			= 'req.auth.username';
	CONST REQ_AUTH_PASSWORD																			= 'req.auth.password';
	CONST REQ_AUTH_AJAX_USER																		= 'req.auth.ajax.user';
	CONST REQ_AUTH_CORS																					= 'req.auth.cors';
	CONST REQ_AUTH_URI																					= 'req.auth.uri';
	CONST REQ_IS_SUBRESOURCE 																		= 'req.is.subresource';
	CONST REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN									= 'req.cors.access.control.allow.origin';
	CONST REQ_CORS_ACCESS_CONTROL_ALLOW_METHODS									= 'req.cors.access.control.allow.methods';
	CONST REQ_CORS_ACCESS_CONTROL_ALLOW_HEADERS									= 'req.cors.access.control.allow.headers';
	CONST REQ_CORS_ACCESS_CONTROL_MAX_AGE												= 'req.cors.access.control.max.age';
	CONST REQ_OPTIONS_RENDER																		= 'req.options.render';
	CONST REQ_AFTER_ACTION																			= 'req.after.action';
	CONST REQ_PARAM_IS_PK																				= 'req.param.is.pk';
	CONST REQ_DATA_READ																					= 'req.data.read';
	CONST REQ_GET_RESOURCE_RENDER																= 'req.get.resource.render';
	CONST REQ_GET_RESOURCES_RENDER															= 'req.get.resources.render';
	CONST REQ_PUT_RESOURCE_RENDER																= 'req.put.resource.render';
	CONST REQ_POST_RESOURCE_RENDER															= 'req.post.resource.render';
	CONST REQ_DELETE_RESOURCE_RENDER														= 'req.delete.resource.render';
	CONST REQ_GET_SUBRESOURCE_RENDER														= 'req.get.subresource.render';
	CONST REQ_GET_SUBRESOURCES_RENDER														= 'req.get.subresources.render';
	CONST REQ_PUT_SUBRESOURCE_RENDER														= 'req.put.subresource.render';
	CONST REQ_DELETE_SUBRESOURCE_RENDER													= 'req.delete.subresource.render';
	CONST REQ_EXCEPTION																					= 'req.exception';
	CONST REQ_RENDER_JSON																				= 'req.render.json';
	CONST REQ_AUTH_TYPE																					= 'req.auth.type';
	
	CONST MODEL_INSTANCE																				= 'model.instance';
	CONST MODEL_ATTACH_BEHAVIORS																= 'model.attach.behaviors';
	CONST MODEL_WITH_RELATIONS																	= 'model.with.relations';
	CONST MODEL_LAZY_LOAD_RELATIONS															= 'model.lazy.load.relations';
	CONST MODEL_LIMIT																						= 'model.limit';
	CONST MODEL_OFFSET																					= 'model.offset';
	CONST MODEL_SCENARIO																				= 'model.scenario';
	CONST MODEL_FILTER																					= 'model.filter';
	CONST MODEL_SORT																						= 'model.sort';
	CONST MODEL_FIND																						= 'model.find';
	CONST MODEL_FIND_ALL																				= 'model.find.all';
	CONST MODEL_COUNT																						= 'model.count';
	CONST MODEL_SUBRESOURCE_FIND																= 'model.subresource.find';
	CONST MODEL_SUBRESOURCES_FIND_ALL														= 'model.subresources.find.all';
	CONST MODEL_SUBRESOURCE_COUNT																= 'model.subresource.count';
	CONST MODEL_APPLY_POST_DATA																	= 'model.apply.post.data';
	CONST MODEL_APPLY_PUT_DATA																	= 'model.apply.put.data';
	CONST MODEL_SAVE																						= 'model.save';
	CONST MODEL_SUBRESOURCE_SAVE																= 'model.subresource.save';
	CONST MODEL_DELETE																					= 'model.delete';
	CONST MODEL_SUBRESOURCE_DELETE															= 'model.subresource.delete';
	CONST MODEL_RESTRICTED_PROPERTIES														= 'model.restricted.properties';
	CONST MODEL_VISIBLE_PROPERTIES															= 'model.visible.properties';
	CONST MODEL_HIDDEN_PROPERTIES																= 'model.hidden.properties';

	//Pre-Filter Events
	CONST PRE_FILTER_CONFIG_APPLICATION_ID											= 'pre.filter.config.application.id';
	CONST PRE_FILTER_CONFIG_DEV_FLAG														= 'pre.filter.config.dev.flag';

	CONST PRE_FILTER_REQ_EVENT_LOGGER														= 'pre.filter.req.event.logger';
	CONST PRE_FILTER_REQ_DISABLE_CWEBLOGROUTE										= 'pre.filter.req.disable.cweblogroute';
	CONST PRE_FILTER_REQ_AUTH_USER															= 'pre.filter.req.auth.user';
	CONST PRE_FILTER_REQ_AUTH_CORS															= 'pre.filter.req.auth.cors';
	CONST PRE_FILTER_REQ_AUTH_HTTPS_ONLY												= 'pre.filter.req.auth.https.only';
	CONST PRE_FILTER_REQ_AUTH_USERNAME													= 'pre.filter.req.auth.username';
	CONST PRE_FILTER_REQ_AUTH_PASSWORD													= 'pre.filter.req.auth.password';
	CONST PRE_FILTER_REQ_AUTH_AJAX_USER													= 'pre.filter.req.auth.ajax.user';
	CONST PRE_FILTER_REQ_AUTH_URI																= 'pre.filter.req.auth.uri';
	CONST PRE_FILTER_REQ_IS_SUBRESOURCE 												= 'pre.filter.req.is.subresource';
	CONST PRE_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN				= 'pre.filter.req.cors.access.control.allow.origin';
	CONST PRE_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_METHODS			= 'pre.filter.req.cors.access.control.allow.methods';
	CONST PRE_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_HEADERS			= 'pre.filter.req.cors.access.control.allow.headers';
	CONST PRE_FILTER_REQ_CORS_ACCESS_CONTROL_MAX_AGE						= 'pre.filter.req.cors.access.control.max.age';
	CONST PRE_FILTER_REQ_OPTIONS_RENDER													= 'pre.filter.req.options.render';
	CONST PRE_FILTER_REQ_AFTER_ACTION														= 'pre.filter.req.after.action';
	CONST PRE_FILTER_REQ_PARAM_IS_PK														= 'pre.filter.req.param.is.pk';
	CONST PRE_FILTER_REQ_DATA_READ															= 'pre.filter.req.data.read';
	CONST PRE_FILTER_REQ_GET_RESOURCE_RENDER										= 'pre.filter.req.get.resource.render';
	CONST PRE_FILTER_REQ_GET_RESOURCES_RENDER										= 'pre.filter.req.get.resources.render';
	CONST PRE_FILTER_REQ_PUT_RESOURCE_RENDER										= 'pre.filter.req.put.resource.render';
	CONST PRE_FILTER_REQ_POST_RESOURCE_RENDER										= 'pre.filter.req.post.resource.render';
	CONST PRE_FILTER_REQ_DELETE_RESOURCE_RENDER									= 'pre.filter.req.delete.resource.render';
	CONST PRE_FILTER_REQ_GET_SUBRESOURCE_RENDER									= 'pre.filter.req.get.subresource.render';
	CONST PRE_FILTER_REQ_GET_SUBRESOURCES_RENDER								= 'pre.filter.req.get.subresources.render';
	CONST PRE_FILTER_REQ_PUT_SUBRESOURCE_RENDER									= 'pre.filter.req.put.subresource.render';
	CONST PRE_FILTER_REQ_DELETE_SUBRESOURCE_RENDER							= 'pre.filter.req.delete.subresource.render';
	CONST PRE_FILTER_REQ_EXCEPTION															= 'pre.filter.req.exception';
	CONST PRE_FILTER_REQ_AUTH_TYPE															= 'pre.filter.req.auth.type';
	CONST PRE_FILTER_REQ_RENDER_JSON														= 'pre.filter.req.render.json';
	
	CONST PRE_FILTER_MODEL_INSTANCE															= 'pre.filter.model.instance';
	CONST PRE_FILTER_MODEL_ATTACH_BEHAVIORS											= 'pre.filter.model.attach.behaviors';
	CONST PRE_FILTER_MODEL_WITH_RELATIONS												= 'pre.filter.model.with.relations';
	CONST PRE_FILTER_MODEL_LAZY_LOAD_RELATIONS									= 'pre.filter.model.lazy.load.relations';
	CONST PRE_FILTER_MODEL_LIMIT																= 'pre.filter.model.limit';
	CONST PRE_FILTER_MODEL_OFFSET																= 'pre.filter.model.offset';
	CONST PRE_FILTER_MODEL_SCENARIO															= 'pre.filter.model.scenario';
	CONST PRE_FILTER_MODEL_FILTER																= 'pre.filter.model.filter';
	CONST PRE_FILTER_MODEL_SORT																	= 'pre.filter.model.sort';
	CONST PRE_FILTER_MODEL_FIND																	= 'pre.filter.model.find';
	CONST PRE_FILTER_MODEL_FIND_ALL															= 'pre.filter.model.find.all';
	CONST PRE_FILTER_MODEL_COUNT																= 'pre.filter.model.count';
	CONST PRE_FILTER_MODEL_SUBRESOURCE_FIND											= 'pre.filter.model.subresource.find';
	CONST PRE_FILTER_MODEL_SUBRESOURCES_FIND_ALL								= 'pre.filter.model.subresources.find.all';
	CONST PRE_FILTER_MODEL_SUBRESOURCE_COUNT										= 'pre.filter.model.subresource.count';
	CONST PRE_FILTER_MODEL_APPLY_POST_DATA											= 'pre.filter.model.apply.post.data';
	CONST PRE_FILTER_MODEL_APPLY_PUT_DATA												= 'pre.filter.model.apply.put.data';
	CONST PRE_FILTER_MODEL_SAVE																	= 'pre.filter.model.save';
	CONST PRE_FILTER_MODEL_SUBRESOURCE_SAVE											= 'pre.filter.model.subresource.save';
	CONST PRE_FILTER_MODEL_DELETE																= 'pre.filter.model.delete';
	CONST PRE_FILTER_MODEL_SUBRESOURCE_DELETE										= 'pre.filter.model.subresource.delete';
	CONST PRE_FILTER_MODEL_RESTRICTED_PROPERTIES								= 'pre.filter.model.restricted.properties';
	CONST PRE_FILTER_MODEL_VISIBLE_PROPERTIES										= 'pre.filter.model.visible.properties';
	CONST PRE_FILTER_MODEL_HIDDEN_PROPERTIES										= 'pre.filter.model.hidden.properties';


	//Post-Filter Events
	CONST POST_FILTER_CONFIG_APPLICATION_ID											= 'post.filter.config.application.id';	
	CONST POST_FILTER_CONFIG_DEV_FLAG														= 'post.filter.config.dev.flag';

	CONST POST_FILTER_REQ_EVENT_LOGGER													= 'post.filter.req.event.logger';
	CONST POST_FILTER_REQ_DISABLE_CWEBLOGROUTE									= 'post.filter.req.disable.cweblogroute';
	CONST POST_FILTER_REQ_AUTH_USER															= 'post.filter.req.auth.user';
	CONST POST_FILTER_REQ_AUTH_CORS															= 'post.filter.req.auth.cors';
	CONST POST_FILTER_REQ_AUTH_HTTPS_ONLY												= 'post.filter.req.auth.https.only';
	CONST POST_FILTER_REQ_AUTH_USERNAME													= 'post.filter.req.auth.username';
	CONST POST_FILTER_REQ_AUTH_PASSWORD													= 'post.filter.req.auth.password';
	CONST POST_FILTER_REQ_AUTH_AJAX_USER												= 'post.filter.req.auth.ajax.user';
	CONST POST_FILTER_REQ_AUTH_URI															= 'post.filter.req.auth.uri';
	CONST POST_FILTER_REQ_IS_SUBRESOURCE 												= 'post.filter.req.is.subresource';
	CONST POST_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN			= 'post.filter.req.cors.access.control.allow.origin';
	CONST POST_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_METHODS			= 'post.filter.req.cors.access.control.allow.methods';
	CONST POST_FILTER_REQ_CORS_ACCESS_CONTROL_ALLOW_HEADERS			= 'post.filter.req.cors.access.control.allow.headers';
	CONST POST_FILTER_REQ_CORS_ACCESS_CONTROL_MAX_AGE						= 'post.filter.req.cors.access.control.max.age';
	CONST POST_FILTER_REQ_OPTIONS_RENDER												= 'post.filter.req.options.render';
	CONST POST_FILTER_REQ_AFTER_ACTION													= 'post.filter.req.after.action';
	CONST POST_FILTER_REQ_PARAM_IS_PK														= 'post.filter.req.param.is.pk';
	CONST POST_FILTER_REQ_DATA_READ															= 'post.filter.req.data.read';
	CONST POST_FILTER_REQ_GET_RESOURCE_RENDER										= 'post.filter.req.get.resource.render';
	CONST POST_FILTER_REQ_GET_RESOURCES_RENDER									= 'post.filter.req.get.resources.render';
	CONST POST_FILTER_REQ_PUT_RESOURCE_RENDER										= 'post.filter.req.put.resource.render';
	CONST POST_FILTER_REQ_POST_RESOURCE_RENDER									= 'post.filter.req.post.resource.render';
	CONST POST_FILTER_REQ_DELETE_RESOURCE_RENDER								= 'post.filter.req.delete.resource.render';
	CONST POST_FILTER_REQ_GET_SUBRESOURCE_RENDER								= 'post.filter.req.get.subresource.render';
	CONST POST_FILTER_REQ_GET_SUBRESOURCES_RENDER								= 'post.filter.req.get.subresources.render';
	CONST POST_FILTER_REQ_PUT_SUBRESOURCE_RENDER								= 'post.filter.req.put.subresource.render';
	CONST POST_FILTER_REQ_DELETE_SUBRESOURCE_RENDER							= 'post.filter.req.delete.subresource.render';
	CONST POST_FILTER_REQ_EXCEPTION															= 'post.filter.req.exception';
	CONST POST_FILTER_REQ_AUTH_TYPE															= 'post.filter.req.auth.type';
	CONST POST_FILTER_REQ_RENDER_JSON														= 'post.filter.req.render.json';
	
	CONST POST_FILTER_MODEL_INSTANCE														= 'post.filter.model.instance';
	CONST POST_FILTER_MODEL_ATTACH_BEHAVIORS										= 'post.filter.model.attach.behaviors';
	CONST POST_FILTER_MODEL_WITH_RELATIONS											= 'post.filter.model.with.relations';
	CONST POST_FILTER_MODEL_LAZY_LOAD_RELATIONS									= 'post.filter.model.lazy.load.relations';
	CONST POST_FILTER_MODEL_LIMIT																= 'post.filter.model.limit';
	CONST POST_FILTER_MODEL_OFFSET															= 'post.filter.model.offset';
	CONST POST_FILTER_MODEL_SCENARIO														= 'post.filter.model.scenario';
	CONST POST_FILTER_MODEL_FILTER															= 'post.filter.model.filter';
	CONST POST_FILTER_MODEL_SORT																= 'post.filter.model.sort';
	CONST POST_FILTER_MODEL_FIND																= 'post.filter.model.find';
	CONST POST_FILTER_MODEL_FIND_ALL														= 'post.filter.model.find.all';
	CONST POST_FILTER_MODEL_COUNT																= 'post.filter.model.count';
	CONST POST_FILTER_MODEL_SUBRESOURCE_FIND										= 'post.filter.model.subresource.find';
	CONST POST_FILTER_MODEL_SUBRESOURCES_FIND_ALL								= 'post.filter.model.subresources.find.all';
	CONST POST_FILTER_MODEL_SUBRESOURCE_COUNT										= 'post.filter.model.subresource.count';
	CONST POST_FILTER_MODEL_APPLY_POST_DATA											= 'post.filter.model.apply.post.data';
	CONST POST_FILTER_MODEL_APPLY_PUT_DATA											= 'post.filter.model.apply.put.data';
	CONST POST_FILTER_MODEL_SAVE																= 'post.filter.model.save';
	CONST POST_FILTER_MODEL_SUBRESOURCE_SAVE										= 'post.filter.model.subresource.save';
	CONST POST_FILTER_MODEL_DELETE															= 'post.filter.model.delete';
	CONST POST_FILTER_MODEL_SUBRESOURCE_DELETE									= 'post.filter.model.subresource.delete';
	CONST POST_FILTER_MODEL_RESTRICTED_PROPERTIES								= 'post.filter.model.restricted.properties';
	CONST POST_FILTER_MODEL_VISIBLE_PROPERTIES									= 'post.filter.model.visible.properties';
	CONST POST_FILTER_MODEL_HIDDEN_PROPERTIES										= 'post.filter.model.hidden.properties';
}
