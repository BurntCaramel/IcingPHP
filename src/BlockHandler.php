<?php
/*
** Copyright 2014-2015 Patrick Smith
** http://www.burntcaramel.com/icing/
*/

namespace BurntIcing
{
	require_once(__DIR__. '/glaze.php');
	use BurntCaramel\Glaze;
	use BurntCaramel\Glaze\Prepare as GlazePrepare;
	use BurntCaramel\Glaze\Serve as GlazeServe;

	class BlockHandler
	{
		// TODO: Use $blockTraitHandler
		protected $blockTraitHandler;
	
		public function __construct($blockTraitHandler = null)
		{
			$this->blockTraitHandler = $blockTraitHandler;
		}
	
		public function createGlazeItemForParticularWithBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions)
		{
			// Needs subclassing.
			return null;
		}
	
		public function createGlazeItemForTextItemBasedBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions)
		{
			// Needs subclassing.
			return null;
		}
	
		public function createGlazeItemForBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions = null)
		{
			$typeGroup = burntCheck($blockJSON['typeGroup'], 'text');
		
			if ($typeGroup === 'particular' || $typeGroup === 'media'):
				return $this->createGlazeItemForParticularWithBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions);
			else:
				return $this->createGlazeItemForTextItemBasedBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions);
			endif;
		}
	}
}