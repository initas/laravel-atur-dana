<?php

namespace App\Libraries;

class Request
{
	public static function header($name, $default = null)
	{
		$name = strtoupper(str_ireplace('-', '_', $name));
		$header = (isset($_SERVER[$name])) ? $_SERVER[$name] : $default;

		$name = 'HTTP_'.$name;
		$header = (isset($_SERVER[$name])) ? $_SERVER[$name] : $header;

		return $header;
	}

	public static function session($name, $default = null)
	{
		$session = (isset($_SESSION[$name])) ? $_SESSION[$name] : $default;

		return $session;
	}

	public static function input($name, $default = null)
	{
		$input = $default;

		if (isset($_REQUEST[$name])) {
			$input = ($_REQUEST[$name] != "") ? $_REQUEST[$name] : $input;
		} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
		    parse_str(file_get_contents("php://input"), $inputs);

		    dd($inputs['description']);

		    if (isset($inputs[$name])) {
				$input = $inputs[$name];
			}
		} elseif (strpos(self::header('CONTENT_TYPE'), 'application/json') !== false) {
			$content = file_get_contents('php://input');
			$json = json_decode($content);

			if (isset($json->$name)) {
				$input = $json->$name;
			}			
		}

		return $input;
	}

	public static function all(){
		return $_REQUEST;
		$content = file_get_contents('php://input');
		$json = json_decode($content, true);
	}
}