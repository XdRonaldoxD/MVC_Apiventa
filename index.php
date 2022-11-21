<?php
session_start();
date_default_timezone_set('America/Lima');
// Definir constante con directorio actual
define('PROY_RUTA', __DIR__);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE,OPTIONS");
header("Allow: GET, POST, PUT, DELETE,OPTIONS");
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');



require_once "vendor/autoload.php";
require_once "config/Eloquentdatabase.php";
require_once "config/database.php";
require_once "config/Eventopusher.php";
require_once "Helpers/helpers.php";
require_once "Helpers/JwtAuth.php";



// require_once "EloquentsModel/Caja.php";

// // $caja = Caja::where("estado_caja", 1)->get();

// $datos = [
//     'glosa_caja'=>"prueba2",
//     'numero_caja'=>"1",
//     'folio_caja'=>1,
//     'fechacreacion_caja'=>date("Y-m-d"),
// ];
// Caja::create($datos);
// echo "creado";
// die;
$classname = $_GET['controller'] . "Controller";
include "Controllers/" . $classname . '.php';



//REQUES VEO SI ESTAN ENVIANDO EL CONTROLADOR Y SU ACCION SI NO ENVIA NO ENTRARA 
if ($_SERVER['REQUEST_METHOD'] === "POST" || $_SERVER['REQUEST_METHOD'] === "GET") {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $jwtAth = new JwtAuth();
        $checktoken = $jwtAth->checktoken($headers['Authorization']);
        if (!$checktoken) {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'El usuario no esta Autenticado',
            );
            echo json_encode($data);
        } else {
            if (isset($_GET['controller'])) {
                $nombre_controlador = $_GET['controller'] . "Controller";
            } else {
                echo "No exite la Pagina";
                die();
            }
            if (isset($_GET['action']) && class_exists($nombre_controlador)) {
                $controlador = new $nombre_controlador();
                if (isset($_GET['action']) && method_exists($controlador, $_GET['action'])) {
                    $accion = $_GET['action'];
                    $controller = new $nombre_controlador();
                    $controller->$accion();
                } else {
                    http_response_code(403);
                }
            } else {
                http_response_code(403);
            }
        }
    } else {
        if (isset($_GET['controller'])) {
            $nombre_controlador = $_GET['controller'] . "Controller";
        } else {
            echo "No exite la Pagina";
            die(http_response_code(403));
        }
        if (isset($_GET['action'])) {
            $controlador = new $nombre_controlador();
            if (isset($_GET['action']) && method_exists($controlador, $_GET['action'])) {
                $accion = $_GET['action'];
                $controller = new $nombre_controlador();
                $controller->$accion();
            } else {
                echo "No existe la pagina";
                http_response_code(403);
            }
        } else {
            echo "No existe la pagina Inicio";
            http_response_code(403);
        }
    }
}
