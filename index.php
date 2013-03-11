<?php
require_once "classes/SpotifyMetaDataAPI.php";

$output = null;
try {
	$spotify = new SpotifyMetaDataAPI();
	$artistsData = $spotify->search(array(
		"method" => "artist",
		"parameters" => array(
			"q" => "Dream Theater"
		)
	));

	$output = $spotify->lookup(array(
		"parameters" => array(
			"uri" => $artistsData->artists[0]->href,
			"extras" => "album"
		)
	));
} catch (Exception $e) {
	$output = $e->getMessage();
}

echo "<pre>";
print_r($output);
echo "</pre>";