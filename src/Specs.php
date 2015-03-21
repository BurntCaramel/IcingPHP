<?php
/*
** Copyright 2014-2015 Patrick Smith
** http://www.burntcaramel.com/icing/
*/

namespace BurntIcing
{
	require_once(__DIR__. '/burntCheck.php');
	
	class Specs
	{
		protected $specsJSON;
		protected $blockGroupIDsToTypes;
		protected $traitsListJSON;
	
		public function __construct($specsJSON)
		{
			$this->specsJSON = $specsJSON;
			$this->subsectionTypesJSON = burntCheck($specsJSON['subsectionTypes']);
			$this->blockGroupIDsToTypes = burntCheck($specsJSON['blockTypesByGroups']);
			$this->traitsListJSON = burntCheck($specsJSON['traits']);
		}
		
		public function findParticularSubsectionOptions($subsectionTypeToFind)
		{
			$subsectionTypesJSON = $this->subsectionTypesJSON;
			if (!isset($subsectionTypesJSON)):
				return null;
			endif;
		
			foreach ($subsectionTypesJSON as $subsectionOptions):
				if ($subsectionOptions['id'] === $subsectionTypeToFind):
					return $subsectionOptions;
				endif;
			endforeach;
		
			return null;
		}
	
		public function findParticularBlockTypeOptionsWithGroupAndType($typeGroup, $type)
		{
			$blockGroupIDsToTypes = $this->blockGroupIDsToTypes;
			if (!isset($blockGroupIDsToTypes)):
				return null;
			endif;
		
			// Find options by searching for the particular ID
			$chosenBlockTypeOptions = null;
			$chosenTypesList = burntCheck($blockGroupIDsToTypes[$typeGroup]);
			if (!isset($chosenTypesList)):
				return null;
			endif;
		
			foreach ($chosenTypesList as $blockTypeOptions):
				if ($blockTypeOptions['id'] === $type):
					$chosenBlockTypeOptions = $blockTypeOptions;
					break;
				endif;
			endforeach;
		
			return $chosenBlockTypeOptions;
		}
	
		public function findParticularBlockTypeOptionsForBlockJSON($blockJSON)
		{
			$typeGroup = burntCheck($blockJSON['typeGroup'], 'text');;
			$type = $blockJSON['type'];
			return $this->findParticularBlockTypeOptionsWithGroupAndType($typeGroup, $type);
		}
	
		public function findParticularTraitOptions($traitIDToFind)
		{
			$traitsListJSON = $this->traitsListJSON;
			if (!isset($traitsListJSON)):
				return null;
			endif;
		
			foreach ($traitsListJSON as $traitOptions):
				if ($traitOptions['id'] === $traitIDToFind):
					return $traitOptions;
				endif;
			endforeach;
		
			return null;
		}
	}
}
