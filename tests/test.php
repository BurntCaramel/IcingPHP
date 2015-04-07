<?php
/*
Copyright 2015: Patrick Smith

This content is released under the Apache License: http://www.apache.org/licenses/LICENSE-2.0
*/

require_once(__DIR__. '/../src/Specs.php');
require_once(__DIR__. '/../src/HTMLTransformer.php');

use BurntIcing\Specs;
use BurntIcing\HTMLTransformer;


$specsJSON = json_decode(file_get_contents(__DIR__. '/specs.json'), true);
$contentJSON = json_decode(file_get_contents(__DIR__. '/content.icing'), true);

$specs = new Specs($specsJSON);
$HTMLTransformer = HTMLTransformer::newTransformerWithSpecs($specs);
$HTMLTransformer->displayHTMLFromDocumentJSON($contentJSON);

/*
$html = GlazePrepare::element('html');
{
	$html->appendNewElement('head', array(
		Glaze\Prepare::element('title', 'An example of using glaze.php'),
		Glaze\Prepare::element(array('tagName' => 'meta', 'charset' => 'utf-8'))
	));
	
	$body = $html->appendNewElement('body');
	{
		$body->appendNewElement('header', array(
			Glaze\Prepare::element('h1', 'Glaze preserves your content by escaping any <html> characters.')
		));
		
		$body->appendPreparedItem($HTMLTransformer->createGlazeElementForContentJSON($contentJSON));
	}
}

echo '<!doctype html>' ."\n";
$html->serve();
*/