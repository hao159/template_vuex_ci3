<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function get_env($name)
{
	if ($_ENV[$name]) {
		return $_ENV[$name];
	}elseif($_SERVER[$name]) {
		return $_SERVER[$name];
	}
}