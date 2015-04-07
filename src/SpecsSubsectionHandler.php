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
	
	require_once(__DIR__ . '/SubsectionHandler.php');
	require_once(__DIR__ . '/HTMLRepresentationAssistant.php');
	
	class SpecsSubsectionHandler extends SubsectionHandler
	{
		protected $specs;
	
		public function __construct($specs)
		{
			$this->specs = $specs;
		
			parent::__construct();
		}
		
		public function glazeItemForHoldingGlazeItemsInSubsectionOfType($innerGlazeItems, $subsectionType)
		{
			$specs = $this->specs;
			$subsectionOptions = $specs->findParticularSubsectionOptions($subsectionType);
			
			$outerHTMLTagName = burntCheck($subsectionOptions['outerHTMLTagName']);
			if (isset($outerHTMLTagName)):
				return GlazePrepare::element($outerHTMLTagName, $innerGlazeItems);
			else:
				return GlazePrepare::content($innerGlazeItems);
			endif;
		}
		
		public function blockCreationOptionsForChildrenInSubsectionOfType($subsectionType)
		{
			$specs = $this->specs;
			$subsectionOptions = $specs->findParticularSubsectionOptions($subsectionType);
			
			return burntCheck($subsectionOptions['blockHTMLOptions']);
		}
	
		public function modifyOrWrapGlazeItemForBlockJSONInSubsectionOfType($glazeItem, $blockJSON, $subsectionType)
		{
			$specs = $this->specs;
			$subsectionOptions = $specs->findParticularSubsectionOptions($subsectionType);
			
			$HTMLRepresentation = burntCheck($subsectionOptions['childHTMLRepresentation']);
			if (isset($HTMLRepresentation)):
				$valueForRepresentation = array(
					'originalElement' => $glazeItem
				);
				$glazeItem = HTMLRepresentationAssistant::createGlazeContentForHTMLRepresentationAndValue($HTMLRepresentation, $valueForRepresentation);
			endif;
		
			return $glazeItem;
		}
	}
}