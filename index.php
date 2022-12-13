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
if ($_SERVER['SERVER_NAME'] === 'sistemasdurand.com') {
    $host = '162.241.60.172';
    $username = 'siste268';
    $password = 'zSj55IiL2+e8:E';
    $base_datos = 'siste268_nota_venta';
    $ruta_archivo='https://sistemasdurand.com/';
    define('RUTA_ARCHIVO', $ruta_archivo);
} else {
    $dominio = "";
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $base_datos = 'notaventa';
    $ruta_archivo='http://localhost/MVC_APIVENTA/';
    define('RUTA_ARCHIVO', $ruta_archivo);
}
require_once "vendor/autoload.php";
require_once "config/Eloquentdatabase.php";
require_once "config/database.php";
require_once "config/Eventopusher.php";
require_once "Helpers/helpers.php";
require_once "Helpers/JwtAuth.php";



if (isset($_GET['controller'])) {
    $classname = $_GET['controller'] . "Controller";
    include "Controllers/" . $classname . '.php';
}




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
