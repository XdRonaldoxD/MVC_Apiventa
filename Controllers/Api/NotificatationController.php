<?php
class NotificatationController{

    public function NotificarMercadoPago(){
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($data);
    }

}