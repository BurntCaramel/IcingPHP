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
	
	require_once(__DIR__ . '/TraitHandler.php');
	require_once(__DIR__ . '/HTMLRepresentationAssistant.php');
	
	class SpecsTraitHandler
	{
		protected $specs;
	
		public function __construct($specs)
		{
			$this->specs = $specs;
		}
	
		public function modifyOrWrapGlazeContentForTraits($glazeItem, $traits)
		{
			$specs = $this->specs;
		
			foreach($traits as $traitID => $traitValue):
				if (burntCheck($traitValue, false) === false):
					continue;
				endif;
			
				$traitOptions = $specs->findParticularTraitOptions($traitID);
				$valueForRepresentation = null;
				// Fields
				if (isset($traitOptions['fields'])):
					$valueForRepresentation = array(
						'originalElement' => $glazeItem,
						'fields' => $traitValue
					);
				// On/off trait
				else:
					$valueForRepresentation = array(
						'originalElement' => $glazeItem
					);
				endif;
			
				if (isset($traitOptions['innerHTMLRepresentation'])):
					$HTMLRepresentation = $traitOptions['innerHTMLRepresentation'];
					if (burntCheck($HTMLRepresentation, false) !== false):
						$glazeItem = HTMLRepresentationAssistant::createGlazeContentForHTMLRepresentationAndValue(
							$HTMLRepresentation, $valueForRepresentation
						);
					else:
						// For example, hide trait
						$glazeItem = null;
					endif;
				endif;
			endforeach;
		
			return $glazeItem;
		}
	}
}