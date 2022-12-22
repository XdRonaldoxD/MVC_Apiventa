<?php

require_once "EloquentsModel/Notificacion.php";

class NotificatationController{

    public function NotificarMercadoPago(){
        $data = json_decode(file_get_contents('php://input'), true);
        $datos=[
            'json_notificacion'=>$data,
            'fecha_creacion_notificacion'=>date('Y-m-d H:i:s')
        ];
        Notificacion::create($datos);
        echo json_encode($data);
    }

}