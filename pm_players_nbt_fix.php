<?php
//error_reporting(E_ERROR | E_PARSE);
function removeFloatNotation($input) {
	$replacement=sprintf("%0.12f",preg_replace("/([^0-9.-.])/", "", $input));
	if(substr($input,0,1) == "-") {
		return "-" . $replacement;
	} else {
		return $replacement;
	}
}

require("PHP-NBT-Decoder-Encoder/nbt.class.php");
if(!isset($argv[1])) {
	die("Please specify the folder where your pocketmine player dat files reside" . PHP_EOL);
}
$targetfolder = rtrim(trim($argv[1]), '/');
if(!is_dir($argv[1] . "/")) {
	return "'" . $targetfolder . "' does not appear to be a valid directory";
}

$files = glob($argv[1] . '/*.{dat,old}', GLOB_BRACE);
foreach($files as $file) {
	$nbt = new nbt();
	$nbt->verbose = false;
	$nbt->loadFile($file);
	foreach($nbt->root[0]["value"] as $currentkey => $currentbit) {
		if($currentbit["name"] == "Pos") {
			if(isset($nbt->root[0]["value"][$currentkey]["value"]["value"][0]) &&
			   isset($nbt->root[0]["value"][$currentkey]["value"]["value"][1]) &&
			   isset($nbt->root[0]["value"][$currentkey]["value"]["value"][2])) {
				$coords=implode(",", $nbt->root[0]["value"][$currentkey]["value"]["value"]);
				$result = preg_match("/([0-9.-.,-,---]*),([0-9.-.,-,---]*),([0-9.-.,-,---]*)/", $coords);
				if($result == 0) {
					echo "Invalid Player Position in " . $file . " " . $coords . PHP_EOL;
					$nbt->root[0]["value"][$currentkey]["value"]["value"][0] = removeFloatNotation($currentbit["value"]["value"][0]);
					$nbt->root[0]["value"][$currentkey]["value"]["value"][1] = removeFloatNotation($currentbit["value"]["value"][1]);
					$nbt->root[0]["value"][$currentkey]["value"]["value"][2] = removeFloatNotation($currentbit["value"]["value"][2]);
					$coords=implode(",", $nbt->root[0]["value"][$currentkey]["value"]["value"]);
					echo "    Writing repaired co-ordinates " . $coords . PHP_EOL;
					// unlink($file);
					$nbt->writeFile($file);
				}
			}
		}
	}
}
?>
