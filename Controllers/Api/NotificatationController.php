<?php

require_once "EloquentsModel/Notificacion.php";

class NotificatationController{

    public function NotificarMercadoPago(){
       
        $datos=[
            'json_notificacion'=>isset($_POST["type"])?$_POST["type"] : '',
            'fecha_creacion_notificacion'=>date('Y-m-d H:i:s')
        ];
        Notificacion::create($datos);
        echo json_encode('ok');
    }

    public function SuccessMercadoPago(){
        echo json_encode('ok');
    }

}