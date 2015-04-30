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

	class HTMLTransformer
	{
		protected $subsectionHandler;
		protected $blockHandler;
		protected $textItemHandler;
	
		public function __construct($subsectionHandler, $blockHandler, $textItemHandler)
		{
			$this->subsectionHandler = $subsectionHandler;
			$this->blockHandler = $blockHandler;
			$this->textItemHandler = $textItemHandler;
		}
	
		public static function newTransformerWithSpecs($specs)
		{
			require_once(__DIR__ . '/SpecsSubsectionHandler.php');
			require_once(__DIR__ . '/SpecsBlockHandler.php');
			require_once(__DIR__ . '/SpecsTraitHandler.php');
			require_once(__DIR__ . '/TextItemHandler.php');
			
			$subsectionHandler = new SpecsSubsectionHandler($specs);
		
			$blockHandler = new SpecsBlockHandler($specs);
	
			$textItemTraitHandler = new SpecsTraitHandler($specs);
			$textItemHandler = new TextItemHandler($textItemTraitHandler);
	
			$HTMLTransformer = new HTMLTransformer($subsectionHandler, $blockHandler, $textItemHandler);
		
			return $HTMLTransformer;
		}
	
		public static function displayContentJSONWithSpecsJSON($contentJSON, $specsJSON)
		{
			$specs = new Specs($specsJSON);

			$HTMLTransformer = static::newTransformerWithSpecs($specs);
			$HTMLTransformer->displayHTMLFromContentJSON($postContent);
		}
	
		public function appendToGlazeItemFromContentJSON($glazeItem, $contentJSON, $generalOptions = null)
		{
			if (empty($contentJSON)):
				return;
			endif;
		
			$blockHandler = $this->blockHandler;
			$textItemHandler = $this->textItemHandler;
			$subsectionHandler = $this->subsectionHandler;
		
			$currentSubsectionType = 'normal';
			$currentSubsectionItems = array();
			$blockCreationOptions = null;
		
			if (isset($subsectionHandler)):
				$blockCreationOptions = $subsectionHandler->blockCreationOptionsForChildrenInSubsectionOfType($currentSubsectionType);
			endif;
		
			$blocks = $contentJSON['blocks'];
			foreach ($blocks as $blockJSON):
				$blockTypeGroup = burntCheck($blockJSON['typeGroup'], 'text');
				// Subsection
				if ($blockTypeGroup === 'subsection'):
					if (isset($subsectionHandler)):
						if (count($currentSubsectionItems) > 0):
							$glazeItem->appendPreparedItem(
								$subsectionHandler->glazeItemForHoldingGlazeItemsInSubsectionOfType($currentSubsectionItems, $currentSubsectionType)
							);
						endif;
						$currentSubsectionItems = array();
						$blockCreationOptions = $subsectionHandler->blockCreationOptionsForChildrenInSubsectionOfType($currentSubsectionType);
					endif;
				
					$currentSubsectionType = $blockJSON['type'];
				// Normal block
				else:
					// Create element for block
					$elementForBlock = $blockHandler->createGlazeItemForBlockJSON($blockJSON, $textItemHandler, $blockCreationOptions, $generalOptions);
					if (isset($subsectionHandler)):
						// Get subsection to wrap element
						$elementForBlock = $subsectionHandler->modifyOrWrapGlazeItemForBlockJSONInSubsectionOfType($elementForBlock, $blockJSON, $currentSubsectionType);
						$currentSubsectionItems[] = $elementForBlock;
					else:
						$glazeItem->appendPreparedItem($elementForBlock);
					endif;
				endif;
			endforeach;
		
			if (isset($subsectionHandler) && count($currentSubsectionItems) > 0):
				$glazeItem->appendPreparedItem(
					$subsectionHandler->glazeItemForHoldingGlazeItemsInSubsectionOfType($currentSubsectionItems, $currentSubsectionType)
				);
			endif;
		}
	
		public function createGlazeElementForContentJSON($contentJSON, $options = null)
		{
			$sectionID = burntCheck($options['sectionID'], 'main');
			
			$mainElement = GlazePrepare::element(array(
				'tagName' => 'div',
				'class' => "icingSection-{$sectionID}"
			));
			$this->appendToGlazeItemFromContentJSON($mainElement, $contentJSON, $options);
			return $mainElement;
		}
		
		// DEPRECATED, use displayHTMLFromDocumentJSON()
		public function displayHTMLFromContentJSON($contentJSON)
		{
			$mainElement = $this->createGlazeElementForContentJSON($contentJSON);
			$mainElement->serve();
		}
		
		public function displayHTMLFromDocumentJSON($documentJSON, $options = null)
		{
			$sectionID = burntCheck($options['sectionID'], 'main');
			$sections = $documentJSON['sections'];
			$contentJSON = $sections[$sectionID];
			
			$mainElement = $this->createGlazeElementForContentJSON($contentJSON, $options);
			$mainElement->serve();
		}
	}
}