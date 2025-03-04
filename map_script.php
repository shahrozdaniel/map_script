<?php
$message = '';
$apiKey = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$locations = [
		"Marseille" => ["lat" => 43.3026, "lon" => 5.3691],
		"Barcelone" => ["lat" => 41.3874, "lon" => 2.1686],
		"Palma de Majorque" => ["lat" => 39.5727, "lon" => 2.6569],
		"Alicante" => ["lat" => 38.3458, "lon" => -0.4909],
		"Valence" => ["lat" => 44.9337, "lon" => 4.8967],
		"Livourne" => ["lat" => 43.5485, "lon" => 10.3106],
		"Rome Civitavecchia" => ["lat" => 42.0923, "lon" => 11.7955],
		"Gênes" => ["lat" => 44.4071, "lon" => 8.9347],
	];

	// generate sea route
	$route = [];
	foreach ($locations as $name => $coords) {
		$route[] = $coords['lat'] . ',' . $coords['lon'];
	}
	$route = implode('|', $route);
	// print_r($route);
	// exit;

	// map parameters
	$width = 800;
	$height = 600;
	$zoom = 5;
	$mapType = 'terrain'; // Map type (roadmap, satellite, terrain, hybrid)

	// initial stattic api url
	$mapUrl = "https://maps.googleapis.com/maps/api/staticmap?size={$width}x{$height}&maptype={$mapType}&zoom={$zoom}";

	// add markers for each location
	foreach ($locations as $name => $coords) {
		$mapUrl .= "&markers=color:blue%7Clabel:" . substr($name, 0, 1) . "%7C" . $coords['lat'] . "," . $coords['lon'];
	}

	// add sea route
	$mapUrl .= "&path=color:0x0000ff80|weight:5|{$route}";

	// add API key
	$mapUrl .= "&key={$apiKey}";
	// echo $mapUrl;
	// exit;

	// create image
	$imageData = file_get_contents($mapUrl);
	if ($imageData) {
		file_put_contents('sea_route_map.png', $imageData);
		$message = "<div class='alert alert-success mt-3'>Map image downloaded successfully as 'sea_route_map.png'.</div>";
	} else {
		$message = "<div class='alert alert-danger mt-3'>Failed to download the map image.</div>";
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Generate Sea Route Map</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column justify-content-center align-items-center vh-100 bg-light">
	<div class="text-center">
		<form method="POST">
			<button type="submit" class="btn btn-primary btn-lg">Generate Sea Route Map</button>
		</form>
		<?php echo $message; ?>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>