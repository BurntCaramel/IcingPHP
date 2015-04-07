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
	
	class HTMLRepresentationAssistant
	{
		public static function getValueAtKeyPathInArray($keyPath, $array)
		{
			$nestedValue = $array;
			foreach ($keyPath as $key):
				if (!isset($nestedValue) || !is_array($nestedValue)):
					$nestedValue = null;
					break;
				endif;
				
				//print_r($nestedValue);
				$nestedValue = burntCheck($nestedValue[$key], null);
			endforeach;
		
			return $nestedValue;
		}
		
		public static function checkOptionsShouldShow($options, $sourceValue) {
			if (isset($options['checkIsPresent'])):
				$checkIsPresentInfo = $options['checkIsPresent'];
				$valueToCheck = static::getAttributeValueForInfoAndSourceValue($checkIsPresentInfo, $sourceValue);
				if (!isset($valueToCheck)):
					return false;
				endif;
			endif;
			
			if (isset($options['checkIsFilled'])):
				$checkIsFilledInfo = $options['checkIsFilled'];
				$valueToCheck = static::getAttributeValueForInfoAndSourceValue($checkIsFilledInfo, $sourceValue);
				if (!is_string($valueToCheck) || trim($valueToCheck) === ''):
					return false;
				endif;
			endif;
	
			return true;
		}
	
		public static function getAttributeValueForInfoAndSourceValue($attributeValueRepresentation, $sourceValue)
		{
			$attributeValue = null;
		
			if (is_string($attributeValueRepresentation)):
				$attributeValue = $attributeValueRepresentation;
			elseif (is_array($attributeValueRepresentation) && isset($attributeValueRepresentation[0])):
				$keyPath = $attributeValueRepresentation;
				$attributeValue = static::getValueAtKeyPathInArray($keyPath, $sourceValue);
			elseif (is_array($attributeValueRepresentation)):
				$attributeOptions = $attributeValueRepresentation;
				
				if (!static::checkOptionsShouldShow($attributeOptions, $sourceValue)):
					return null;
				endif;
			
				if (isset($attributeOptions['text'])):
					$attributeValue = $attributeOptions['text'];
				elseif (isset($attributeOptions['join'])):
					$join = $attributeOptions['join'];
					$pieces = array();
					$allPresent = true;
					foreach($join as $attributeInfoToCheck):
						$valueToCheck = static::getAttributeValueForInfoAndSourceValue($attributeInfoToCheck, $sourceValue);
						if (!isset($valueToCheck)):
							$allPresent = false;
							break;
						endif;
					
						$pieces[] = $valueToCheck;
					endforeach;
				
					if ($allPresent):
						$attributeValue = implode('', $pieces);
					endif;
				elseif (isset($attributeOptions['firstWhichIsPresent'])):
					$firstWhichIsPresent = $attributeOptions['firstWhichIsPresent'];
					foreach ($firstWhichIsPresent as $attributeInfoToCheck):
						$valueToCheck = static::getAttributeValueForInfoAndSourceValue($attributeInfoToCheck, $sourceValue);
						if (isset($valueToCheck)):
							$attributeValue = $valueToCheck;
							break;
						endif;
					endforeach;
				endif;
			endif;
		
			return $attributeValue;
		}
	
		public static function createGlazeElementForElementOptions($elementOptions, $sourceValue)
		{
			if (!static::checkOptionsShouldShow($elementOptions, $sourceValue)):
				return null;
			endif;
			
			// Referenced Element
			if (isset($elementOptions['placeOriginalElement'])):
				return burntCheck($sourceValue['originalElement']);
			// Element
			elseif (isset($elementOptions['tagName'])):
				$tagName = $elementOptions['tagName'];
			
				$attributesReady = array();
				if (isset($elementOptions['attributes']) && isset($sourceValue)):
					$attributes = $elementOptions['attributes'];
					foreach ($attributes as $attributeName => $attributeValueRepresentation):
						$attributeValue = static::getAttributeValueForInfoAndSourceValue($attributeValueRepresentation, $sourceValue);
					
						if (isset($attributeValue)):
							$attributesReady[$attributeName] = $attributeValue;
						endif;
					endforeach;
				endif;
			
				$childrenPrepared = array();
				if (isset($elementOptions['children'])):
					$childrenOptions = $elementOptions['children'];
					$childrenPrepared = static::createGlazeContentForHTMLRepresentationAndValue($childrenOptions, $sourceValue);
				endif;
			
				$attributesReady['tagName'] = $tagName;
			
				return GlazePrepare::element($attributesReady, $childrenPrepared);
			// Text
			else:
				return static::getAttributeValueForInfoAndSourceValue($elementOptions, $sourceValue);
			endif;
		}
	
		/**
		* @param $HTMLRepresentation The HTML representation as JSON
		*/
		public static function createGlazeContentForHTMLRepresentationAndValue($HTMLRepresentation, $sourceValue)
		{
			$preparedElements = array();
			foreach ($HTMLRepresentation as $elementOptions):
				$preparedElement = static::createGlazeElementForElementOptions($elementOptions, $sourceValue);
				if (isset($preparedElement)):
					$preparedElements[] = $preparedElement;
				endif;
			endforeach;
		
			return GlazePrepare::content($preparedElements);
		}
	}
}