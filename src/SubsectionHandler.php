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

	class SubsectionHandler
	{
		public function __construct()
		{
		}
	
		// TODO: change to abstract class.
		public function glazeItemForHoldingGlazeItemsInSubsectionOfType($innerGlazeItems, $subsectionType)
		{
			if ($subsectionType === 'unorderedList'):
				return GlazePrepare::element('ul', $innerGlazeItems);
			elseif ($subsectionType === 'orderedList'):
				return GlazePrepare::element('ol', $innerGlazeItems);
			endif;
		
			return GlazePrepare::content($innerGlazeItems);
		}
	
		public function blockCreationOptionsForChildrenInSubsectionOfType($subsectionType)
		{
			if ($subsectionType === 'unorderedList' || $subsectionType === 'orderedList'):
				return array(
					'noParagraphs' => true
				);
			endif;
		
			return null;
		}
	
		public function modifyOrWrapGlazeItemForBlockJSONInSubsectionOfType($glazeItem, $blockJSON, $subsectionType)
		{
			if ($subsectionType === 'unorderedList' || $subsectionType === 'orderedList'):
				$glazeItem = GlazePrepare::element('li', $glazeItem);
			endif;
		
			return $glazeItem;
		}
	}
}