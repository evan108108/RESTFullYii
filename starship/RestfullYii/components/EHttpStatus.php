<?php
/**
 * EHttpStatus
 *
 * Helps in setting http response codes and messages
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/components
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class EHttpStatus {

	const CODE_CONTINUE = 100;
	const CODE_SWITCHING_PROTOCOLS = 101;
	const CODE_OK = 200;
	const CODE_CREATED = 201;
	const CODE_ACCEPTED = 202;
	const CODE_NON_AUTHORITATIVE_INFORMATION = 203;
	const CODE_NO_CONTENT = 204;
	const CODE_RESET_CONTENT = 205;
	const CODE_PARTIAL_CONTENT = 206;
	const CODE_MULTIPLE_CHOICES = 300;
	const CODE_MOVED_PERMANENTLY = 301;
	const CODE_FOUND = 302;
	const CODE_SEE_OTHER = 303;
	const CODE_NOT_MODIFIED = 304;
	const CODE_USE_PROXY = 305;
	const CODE_TEMPORARY_REDIRECT = 307;
	const CODE_BAD_REQUEST = 400;
	const CODE_UNAUTHORIZED = 401;
	const CODE_PAYMENT_REQUIRED = 402;
	const CODE_FORBIDDEN = 403;
	const CODE_NOT_FOUND = 404;
	const CODE_METHOD_NOT_ALLOWED = 405;
	const CODE_NOT_ACCEPTABLE = 406;
	const CODE_PROXY_AUTHENTICATION_REQUIRED = 407;
	const CODE_REQUEST_TIMEOUT = 408;
	const CODE_CONFLICT = 409;
	const CODE_GONE = 410;
	const CODE_LENGTH_REQUIRED = 411;
	const CODE_PRECONDITION_FAILED = 412;
	const CODE_REQUEST_ENTITY_TOO_LARGE = 413;
	const CODE_REQUEST_URI_TOO_LONG = 414;
	const CODE_UNSUPPORTED_MEDIA_TYPE = 415;
	const CODE_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
	const CODE_EXPECTATION_FAILED = 417;
	const CODE_INTERNAL_SERVER_ERROR = 500;
	const CODE_NOT_IMPLEMENTED = 501;
	const CODE_BAD_GATEWAY = 502;
	const CODE_SERVICE_UNAVAILABLE = 503;
	const CODE_GATEWAY_TIMEOUT = 504;
	const CODE_HTTP_VERSION_NOT_SUPPORTED = 505;

	/**
	 * @var array All the http status messages.
	 */
	public static $messages = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
	];

	/**
	 * @var int The http status code.
	 */
	public $code;

	/**
	 * @var string The http status message.
	 */
	public $message;

	/**
	 * @param int $code The Http status code. [optional]
	 */
	public function __construct($code = self::CODE_OK, $message = null)
	{
		$this->set($code, $message);
	}

	public function set($code = self::CODE_OK, $message = null)
	{
		if ($message === null && isset(self::$messages[$code])) {
			$message = self::$messages[$code];
		}
		$this->code = $code;
		$this->message = $message;

		return $this;
	}
	/**
	 * @return string The Http status.
	 */
	public function __toString() 
	{
		return 'HTTP/1.1 '.$this->code.' '.$this->message;
	}

}
