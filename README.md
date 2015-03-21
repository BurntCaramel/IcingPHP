# IcingPHP

PHP library for viewing Icing files. [Read more about Icing here.](http://www.burntcaramel.com/icing/)

## Example

You just need a **.icing** file with your content, and a specs .json file that determines how the content is displayed.

	use BurntIcing\Specs;
	use BurntIcing\HTMLTransformer;


	$specsJSON = json_decode(file_get_contents(__DIR__. '/specs.json'), true);
	$contentJSON = json_decode(file_get_contents(__DIR__. '/content.icing'), true);

	$specs = new Specs($specsJSON);
	$HTMLTransformer = HTMLTransformer::newTransformerWithSpecs($specs);
	$HTMLTransformer->displayHTMLFromContentJSON($contentJSON);

## Extensible

### PHP

Subclass `BlockHandler`, `TraitHandler`, or `SubsectionHandler` to customise the content as it is being displayed.
This is how my [Blik page](http://www.burntcaramel.com/blik/) is displayed, with iTunes links and images interweaved in PHP on the server.

### Specs JSON files

The structure is still in flux, but specs .json files allow custom block and traits to be added, with fields that get transformed into HTML.
These will be handled both with this PHP library and with the React.js based [Icing Editor](https://github.com/BurntIcing/IcingEditor).

## Test Output

	php -f tests/test.php 