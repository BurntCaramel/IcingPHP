<?php
/*
** Copyright 2013-2015 Patrick Smith
** http://www.burntcaramel.com/
*/

if (!function_exists('burntCheck')):
	function burntCheck(&$valueToCheck, $default = null)
	{
		if (isset($valueToCheck)):
			return $valueToCheck;
		else:
			return $default;
		endif;
	}
endif;