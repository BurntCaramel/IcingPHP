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

	class TextItemHandler
	{
		protected $traitHandler;
	
		public function __construct($traitHandler = null)
		{
			$this->traitHandler = $traitHandler;
		}
	
		public function createGlazeItemForTextItemJSON($textItemJSON, $blockJSON)
		{
			$text = $textItemJSON['text'];
			$glazeItem = GlazePrepare::content($text);
		
			$traitHandler = $this->traitHandler;
			if (isset($textItemJSON['traits']) && isset($traitHandler)):
				$traits = $textItemJSON['traits'];
				$glazeItem = $traitHandler->modifyOrWrapGlazeContentForTraits($glazeItem, $traits);
			endif;
		
			return $glazeItem;
		}
	
		public function createGlazeContentForArrayOfTextItemsJSON($arrayOfTextItemsJSON, $blockJSON)
		{
			$glazeItemsForTextItems = array();
			foreach ($arrayOfTextItemsJSON as $textItemJSON):
				$glazeItemsForTextItems[] = $this->createGlazeItemForTextItemJSON($textItemJSON, $blockJSON);
			endforeach;
		
			return GlazePrepare::content($glazeItemsForTextItems);
		}
	}
}