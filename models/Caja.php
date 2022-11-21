<?php
class Caja
{
    private $db;
    public function __construct()
    {
        $this->db = database::conectar();
    }
    public $id_caja;
    public $glosa_caja;
    public $numero_caja;
    public $folio_caja;
    public $fechacreacion_caja;
    public $fechacierre_caja;
    public $estado_caja;


    public function getId_caja()
    {
        return $this->id_caja;
    }

    public function setId_caja($id_caja)
    {
        $this->id_caja = $id_caja;

        return $this;
    }

    public function getGlosa_caja()
    {
        return $this->glosa_caja;
    }


    public function setGlosa_caja($glosa_caja)
    {
        $this->glosa_caja = $glosa_caja;

        return $this;
    }

    public function getNumero_caja()
    {
        return $this->numero_caja;
    }


    public function setNumero_caja($numero_caja)
    {
        $this->numero_caja = $numero_caja;

        return $this;
    }


    public function getFolio_caja()
    {
        return $this->folio_caja;
    }


    public function setFolio_caja($folio_caja)
    {
        $this->folio_caja = $folio_caja;

        return $this;
    }


    public function getFechacreacion_caja()
    {
        return $this->fechacreacion_caja;
    }


    public function setFechacreacion_caja($fechacreacion_caja)
    {
        $this->fechacreacion_caja = $fechacreacion_caja;

        return $this;
    }

 
    public function getFechacierre_caja()
    {
        return $this->fechacierre_caja;
    }


    public function setFechacierre_caja($fechacierre_caja)
    {
        $this->fechacierre_caja = $fechacierre_caja;

        return $this;
    }


    public function getEstado_caja()
    {
        return $this->estado_caja;
    }


    public function setEstado_caja($estado_caja)
    {
        $this->estado_caja = $estado_caja;

        return $this;
    }

     
    public function All($tabla, $activas)
    {
        $marcar = $this->db->prepare("SELECT * FROM $tabla where estado_caja=$activas order by id_caja desc");
        $marcar->execute();
        $result = $marcar->fetchAll(PDO::FETCH_ASSOC);
        $marcar = null;
        return $result;
    }

    public function Traer_ultimo()
    {
        $sql = "SELECT * FROM caja order by id_marca desc limit 1";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }
    public function Registrar()
    {
        $sql = "INSERT INTO caja(glosa_caja,numero_caja,fechacreacion_caja)values(?,?,?)";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->getGlosa_caja(), $this->getNumero_caja(),$this->getFechacierre_caja()));
        $sentencia = null;
        return  $this->db->lastInsertId();
    }

    public function Actualizar()
    {
        $sql = "UPDATE  marca  SET glosa_marca=?  where id_marca=?";
        $sentencia = $this->db->prepare($sql);
        try {
            $sentencia->execute(array($this->glosa_marca, $this->id_marca));
        } catch (PDOException $e) {
            $this->db->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        }
        $sentencia = null;
        return  $this->db->lastInsertId();
    }

    public function ActualizarVigente()
    {
        $sql = "UPDATE  marca  SET vigente_marca=?  where id_marca=? ";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->vigente_marca, $this->id_marca));
        return $sentencia->fetch();
        $sentencia = null;
    }
}
