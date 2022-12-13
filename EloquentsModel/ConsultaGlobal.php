<?php
require_once "config/database.php";

class ConsultaGlobal
{
    public $db;
    public function __construct()
    {
        $this->db = database::conectar();
    }

    public function ConsultaSingular($sql)
    {
        $ConsultaSingular = $this->db->prepare($sql);
        $ConsultaSingular->execute();
        $result = $ConsultaSingular->fetch(PDO::FETCH_OBJ);
        $ConsultaSingular = null;
        return $result;
    }
    public function ConsultaGlobal($sql)
    {
        $ConsultaGlobal = $this->db->prepare($sql);
        $ConsultaGlobal->execute();
        $result = $ConsultaGlobal->fetchAll(PDO::FETCH_OBJ);
        $ConsultaGlobal = null;
        return $result;
    }
}
