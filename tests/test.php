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
