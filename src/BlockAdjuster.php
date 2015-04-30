<?php
/*
** Copyright 2014-2015 Patrick Smith
** http://www.burntcaramel.com/icing/
*/

namespace BurntIcing
{
	interface BlockAdjuster
	{
		public function adjustBlockJSON($blockJSON);
	}
}