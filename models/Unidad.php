<?php
class Unidad
{
    private $db;
    public function __construct()
    {
        $this->db = database::conectar();
    }
    public $id_unidad;
    public $glosa_unidad;
    public $vigente_unidad;
    public $order_unidad;

    public function getId_unidad()
    {
        return $this->id_unidad;
    }
    public function setId_unidad($id_unidad)
    {
        $this->id_unidad = $id_unidad;

        return $this;
    }
    public function getGlosa_unidad()
    {
        return $this->glosa_unidad;
    }
    public function setGlosa_unidad($glosa_unidad)
    {
        $this->glosa_unidad = $glosa_unidad;

        return $this;
    }
    public function getVigente_unidad()
    {
        return $this->vigente_unidad;
    }

    public function setVigente_unidad($vigente_unidad)
    {
        $this->vigente_unidad = $vigente_unidad;

        return $this;
    }

        /**
     * Get the value of order_unidad
     */ 
    public function getOrder_unidad()
    {
        return $this->order_unidad;
    }
    public function setOrder_unidad($order_unidad)
    {
        $this->order_unidad = $order_unidad;

        return $this;
    }
    
    public function All($tabla, $activas)
    {
        $marcar = $this->db->prepare("SELECT * FROM $tabla where vigente_unidad=$activas order by id_unidad desc");
        $marcar->execute();
        $result = $marcar->fetchAll(PDO::FETCH_ASSOC);
        $marcar = null;
        return $result;
    }

    public function Traer_Unidad($columna_unidad)
    {
        $sql = "SELECT * FROM unidad where $columna_unidad=?";
        $sentencia = $this->db->prepare($sql);
        if ($this->getId_unidad()) {
            $sentencia->execute(array($this->getId_unidad()));
        }
        if ($this->getGlosa_unidad()) {
            $sentencia->execute(array($this->getGlosa_unidad()));
        }
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }
    public function Registrar()
    {
        $sql = "INSERT INTO unidad(glosa_unidad,vigente_unidad)values(?,?)";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->glosa_unidad, $this->vigente_unidad));
        $sentencia = null;
        return  $this->db->lastInsertId();
    }

    public function Actualizar()
    {
        $sql = "UPDATE  unidad  SET glosa_unidad=?  where id_unidad=?";
        $sentencia = $this->db->prepare($sql);
        try {
            $sentencia->execute(array($this->glosa_unidad, $this->id_unidad));
        } catch (PDOException $e) {
            $this->db->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        }
        $sentencia = null;
        return  $this->db->lastInsertId();
    }

    public function ActualizarVigente()
    {
        $sql = "UPDATE  unidad  SET vigente_unidad=?  where id_unidad=? ";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->vigente_unidad, $this->id_unidad));
        return $sentencia->fetch();
        $sentencia = null;
    }

    public function ListarTipoConcentracion(){
        $sql="SELECT * FROM tipo_concentracion where id_unidad=?";
        $sentencia=$this->db->prepare($sql);
        $sentencia->execute(array($this->getId_unidad()));
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        $sentencia=null;
    }

    public function Traer_glosa_Unidad()
    {
        $sql = "SELECT * FROM unidad where glosa_unidad LIKE '%".$this->getGlosa_unidad()."%'";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }

    public function Traer_Ultimo_Unidad()
    {
        $sql = "SELECT top 1 * FROM unidad where order by id_unidad desc";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }




}
