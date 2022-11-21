<?php
class Proveedor
{
    private $db;
    public function __construct()
    {
        $this->db = database::conectar();
    }
    public $id_proveedor;
    public $ruc_proveedor;
    public $glosa_proveedor;
    public $direccion_proveedor;
    public $telefono_proveedor;
    public $e_mail_proveedor;
    public $comentario_proveedor;
    public $vigente_proveedor;

    public function getId_proveedor()
    {
        return $this->id_proveedor;
    }


    public function setId_proveedor($id_proveedor)
    {
        $this->id_proveedor = $id_proveedor;

        return $this;
    }


    public function getRuc_proveedor()
    {
        return $this->ruc_proveedor;
    }


    public function setRuc_proveedor($ruc_proveedor)
    {
        $this->ruc_proveedor = $ruc_proveedor;

        return $this;
    }

  
    public function getGlosa_proveedor()
    {
        return $this->glosa_proveedor;
    }


    public function setGlosa_proveedor($glosa_proveedor)
    {
        $this->glosa_proveedor = $glosa_proveedor;

        return $this;
    }

  
    public function getDireccion_proveedor()
    {
        return $this->direccion_proveedor;
    }


    public function setDireccion_proveedor($direccion_proveedor)
    {
        $this->direccion_proveedor = $direccion_proveedor;

        return $this;
    }


    public function getTelefono_proveedor()
    {
        return $this->telefono_proveedor;
    }


    public function setTelefono_proveedor($telefono_proveedor)
    {
        $this->telefono_proveedor = $telefono_proveedor;

        return $this;
    }

 
    public function getE_mail_proveedor()
    {
        return $this->e_mail_proveedor;
    }

    public function setE_mail_proveedor($e_mail_proveedor)
    {
        $this->e_mail_proveedor = $e_mail_proveedor;

        return $this;
    }
    public function getComentario_proveedor()
    {
        return $this->comentario_proveedor;
    }
    public function setComentario_proveedor($comentario_proveedor)
    {
        $this->comentario_proveedor = $comentario_proveedor;

        return $this;
    }


    public function getVigente_proveedor()
    {
        return $this->vigente_proveedor;
    }


    public function setVigente_proveedor($vigente_proveedor)
    {
        $this->vigente_proveedor = $vigente_proveedor;

        return $this;
    }

  

    public function All($tabla, $activas)
    {
        $marcar = $this->db->prepare("SELECT * FROM $tabla where vigente_proveedor=$activas order by id_proveedor desc");
        $marcar->execute();
        $result = $marcar->fetchAll(PDO::FETCH_ASSOC);
        $marcar = null;
        return $result;
    }

    public function Traer_Proveedor($columna_proveedor)
    {
        $sql = "SELECT * FROM proveedor where $columna_proveedor=?";
        $sentencia = $this->db->prepare($sql);
        if ($this->getId_proveedor()) {
            $sentencia->execute(array($this->getId_proveedor()));
        }
        if ($this->getGlosa_proveedor()) {
            $sentencia->execute(array($this->getGlosa_proveedor()));
        }
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }
    public function Registrar()
    {
        $sql = "INSERT INTO proveedor(glosa_proveedor,ruc_proveedor,direccion_proveedor,telefono_proveedor,e_mail_proveedor,comentario_proveedor,vigente_proveedor)
        values(?,?,?,?,?,?,?)";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->glosa_proveedor, $this->ruc_proveedor,$this->direccion_proveedor,$this->telefono_proveedor,$this->e_mail_proveedor,$this->comentario_proveedor,$this->vigente_proveedor));
        $sentencia = null;
        return  $this->db->lastInsertId();
    }

    public function Actualizar()
    {
        $sql = "UPDATE  proveedor  SET glosa_proveedor=?,ruc_proveedor=?,direccion_proveedor=?,telefono_proveedor=?,e_mail_proveedor=?,comentario_proveedor=?
         where id_proveedor=?";
        $sentencia = $this->db->prepare($sql);
        try {
            $sentencia->execute(array($this->glosa_proveedor, $this->ruc_proveedor,$this->direccion_proveedor,$this->telefono_proveedor,$this->e_mail_proveedor,$this->comentario_proveedor,$this->id_proveedor));
        } catch (PDOException $e) {
            $this->db->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        }
        $sentencia = null;
        return  $this->db->lastInsertId();
    }

    public function ActualizarVigente()
    {
        $sql = "UPDATE  proveedor  SET vigente_proveedor=?  where id_proveedor=? ";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->vigente_proveedor, $this->id_proveedor));
        return $sentencia->fetch();
        $sentencia = null;
    }

 
  
}
