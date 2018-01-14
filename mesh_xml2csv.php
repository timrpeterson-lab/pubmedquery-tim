<?php

// https://github.com/ilios/mesh-parser
// composer require ilios/mesh-parser

require '../vendor/autoload.php';

// provide an URL or a local file path.
//$uri = 'ftp://nlmpubs.nlm.nih.gov/online/mesh/.xmlmesh/desc2017.xml'; //wget
$uri = __DIR__ . '/desc2017.xml'; 

// instantiate the parser and parse the input.
$parser = new \Ilios\MeSH\Parser();

ini_set('memory_limit', '-1');

$set = $parser->parse($uri);
//echo print_r($set);
//exit;

// process parsed data, e.g.
//$descriptor = $set->findDescriptorByUi('D000001');
//echo "Descriptor ID (Name): {$descriptor->getUi()} ({$descriptor->getName()})\n";
//$concepts = $descriptor->getConcepts();

//$descriptor = $set->findDescriptorByUi('D000002');
//echo "Descriptor ID (Name): {$descriptor->getUi()} ({$descriptor->getName()})\n";


//$file = fopen("mesh.csv","w");

$file = fopen("mesh_terms.csv", 'r+');

$cnt = 1;

for($i = 1; $i < 100000; $i++){

		if($i < 10){
			$ui = 'D00000'.$i;
		}
		elseif($i >= 10 && $i < 100){
			$ui = 'D0000'.$i;
		}
		elseif($i  >= 100 && $i < 1000){
			$ui = 'D000'.$i;
		}
		elseif($i  >= 1000 && $i < 10000){
			$ui = 'D00'.$i;
		}
		elseif($i  >= 10000 && $i < 100000){
			$ui = 'D0'.$i;
		}
		elseif($i  >= 100000){
			$ui = 'D'.$i;
		}

		

		try{

			$descriptor = $set->findDescriptorByUi($ui);

			if($descriptor){
				//$foo = $descriptor->getUi();
				$d_ui = $descriptor->getUi().",".$descriptor->getName().',';
				//fputcsv($file, explode(",", $foo.",".$descriptor->getName()));				


				$concepts = $descriptor->getConcepts();

				$term_str = '';
				foreach($concepts as $concept) {
					$c_ui = $concept->getUi().','.$concept->getName().',';

				   // echo "- Concept ID (Name): {$concept->getUi()} ({$concept->getName()})\n";
				    $terms = $concept->getTerms();

				    foreach ($terms as $term) {
				        // ...
				        //print_r($term->getUi().",".$term->getName());
				        $term_str  = $term->getUi().",".$term->getName();
				        fputcsv($file, explode(",", $cnt.','.$d_ui.$c_ui.$term_str));
				        $cnt++;
				    }

				    //break;
				}

			}
		}
		catch(\Exception $e){
			echo 'message: '.$e;

			echo $ui.'doesn\'t exist';
		}

	  
}

fclose($file);

?>