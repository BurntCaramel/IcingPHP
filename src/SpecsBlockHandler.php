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
	
	require_once(__DIR__ . '/BlockHandler.php');
	require_once(__DIR__ . '/HTMLRepresentationAssistant.php');
	
	class SpecsBlockHandler extends BlockHandler
	{
		protected $specs;
	
		public function __construct($specs)
		{
			$this->specs = $specs;
		
			$traitHandler = new SpecsTraitHandler($specs);
			parent::__construct($traitHandler);
		}
	
		public function createGlazeItemForBlockJSONAndInnerGlazeItem($blockJSON, $innerGlazeItem, $blockCreationOptions, $generalOptions)
		{
			$specs = $this->specs;
			$blockTypeOptions = $specs->findParticularBlockTypeOptionsForBlockJSON($blockJSON);
		
			$valueForRepresentation = array();
			if (isset($blockTypeOptions['fields'])):
				$value = burntCheck($blockJSON['value'], array());
				$valueForRepresentation['fields'] = $value;
			endif;
		
			if (!isset($innerGlazeItem)):
				$HTMLRepresentation = burntCheck($blockTypeOptions['innerHTMLRepresentation']);
				if (isset($HTMLRepresentation)):
					$innerGlazeItem = HTMLRepresentationAssistant::createGlazeContentForHTMLRepresentationAndValue($HTMLRepresentation, $valueForRepresentation);
				endif;
			endif;
			
			$outerHTMLTagName = burntCheck($blockTypeOptions['outerHTMLTagName']);
			if (isset($outerHTMLTagName)):
				$justUseInnerElements = ($outerHTMLTagName === 'p' && burntCheck($blockCreationOptions['noParagraphs'], false));
				if (!$justUseInnerElements):
					return GlazePrepare::element($outerHTMLTagName, $innerGlazeItem);
				endif;
			endif;
		
			return $innerGlazeItem;
		}
	
		public function createGlazeItemForParticularWithBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions, $generalOptions = null)
		{
			return $this->createGlazeItemForBlockJSONAndInnerGlazeItem($blockJSON, null, $blockCreationOptions, $generalOptions);
		}
		
		public function createGlazeItemForMediaWithBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions, $generalOptions = null)
		{
			return $this->createGlazeItemForBlockJSONAndInnerGlazeItem($blockJSON, null, $blockCreationOptions, $generalOptions);
		}
	
		public function createGlazeItemForTextItemBasedBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions, $generalOptions = null)
		{
			$textItems = burntCheck($blockJSON['textItems'], array());
			$innerGlazeItem = $textItemHandler->createGlazeContentForArrayOfTextItemsJSON($textItems, $blockJSON);
		
			return $this->createGlazeItemForBlockJSONAndInnerGlazeItem($blockJSON, $innerGlazeItem, $blockCreationOptions, $generalOptions);
		}
	}
}