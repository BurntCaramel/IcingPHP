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
		
		public function createGlazeItemForTextItemBasedBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions, $generalOptions = null)
		{
			// Needs subclassing.
			return null;
		}
	
		public function createGlazeItemForParticularWithBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions, $generalOptions = null)
		{
			// Needs subclassing.
			return null;
		}
		
		public function createGlazeItemForMediaWithBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions, $generalOptions = null)
		{
			// Needs subclassing.
			return null;
		}
	
		public function createGlazeItemForBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions = null, $generalOptions = null)
		{
			$blockAdjuster = burntCheck($generalOptions['blockAdjuster']);
			if (isset($blockAdjuster)):
				$blockJSON = $blockAdjuster->adjustBlockJSON($blockJSON);
			endif;
			
			$typeGroup = burntCheck($blockJSON['typeGroup'], 'text');
		
			if ($typeGroup === 'particular'):
				return $this->createGlazeItemForParticularWithBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions, $generalOptions);
			elseif ($typeGroup === 'media'):
				return $this->createGlazeItemForMediaWithBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions, $generalOptions);
			else:
				return $this->createGlazeItemForTextItemBasedBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions, $generalOptions);
			endif;
		}
	}
}