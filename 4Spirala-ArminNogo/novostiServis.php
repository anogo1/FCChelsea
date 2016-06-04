<?php
function zag() {
    header("{$_SERVER['SERVER_PROTOCOL']} 200 OK");
    header('Content-Type: text/html');
    header('Access-Control-Allow-Origin: *');
}
function rest_get($request, $data) {
$brojNovosti = $data['x'];
$idAutor = $data['autor'];

define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
        define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
        define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
        define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
        define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
        $veza = new PDO($dsn, DB_USER, DB_PASS);
$veza->exec("set names utf8");

$upit = $veza->prepare("SELECT * FROM novost WHERE autor=?");
$upit->bindValue(1, $idAutor, PDO::PARAM_INT);
$upit->execute();

$json = array();
$brojac = 0;

foreach($upit->fetchAll() as $novost)
{
	if($brojac < $brojNovosti)
	{
		
		$nizPodataka = array(	'id' => $novost['id'],
								'naslov' => $novost['naslov'],
								'slika' => $novost['slika'],
								'tekst' => $novost['tekst'],
								'vrijeme' => $novost['vrijeme'],
								'autor' => $novost['autor'],
								'otvorena' => $novost['otvorena']
								);
		array_push($json,$nizPodataka);
	}
	
	$brojac = $brojac + 1;
}

$jsonString = json_encode($json);
print $jsonString;
}

function rest_post($request, $data) { }
function rest_delete($request) { }
function rest_put($request, $data) { }
function rest_error($request) { }

$method  = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

switch($method) {
    case 'PUT':
        parse_str(file_get_contents('php://input'), $put_vars);
        zag(); $data = $put_vars; rest_put($request, $data); break;
    case 'POST':
        zag(); $data = $_POST; rest_post($request, $data); break;
    case 'GET':
        zag(); $data = $_GET; rest_get($request, $data); break;
    case 'DELETE':
        zag(); rest_delete($request); break;
    default:
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        rest_error($request); break;
}
?>
