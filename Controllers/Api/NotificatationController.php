<?php

require_once "EloquentsModel/Notificacion.php";

class NotificatationController
{

    public function NotificarMercadoPago()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        $datos = [
            'json_notificacion' => $data,
            'fecha_creacion_notificacion' => date('Y-m-d H:i:s')
        ];
        Notificacion::create($datos);
        echo json_encode('ok');
    }

    public function SuccessMercadoPago()
    {
        echo json_encode('ok');
    }
}
