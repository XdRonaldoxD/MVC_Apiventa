<?php

class Inventario{

    private $db;
    public function __construct()
    {
        $this->db=database::conectar();
    }

    private $id_tipo_inventario;
    private $glosa_tipo_inventario;
    private $ventaproducto_tipo_inventario;
    private $orden_tipo_inventario;
    private $vigente_tipo_inventario;



    /**
     * Get the value of id_tipo_inventario
     */ 
    public function getId_tipo_inventario()
    {
        return $this->id_tipo_inventario;
    }

    /**
     * Set the value of id_tipo_inventario
     *
     * @return  self
     */ 
    public function setId_tipo_inventario($id_tipo_inventario)
    {
        $this->id_tipo_inventario = $id_tipo_inventario;

        return $this;
    }

    /**
     * Get the value of glosa_tipo_inventario
     */ 
    public function getGlosa_tipo_inventario()
    {
        return $this->glosa_tipo_inventario;
    }

    /**
     * Set the value of glosa_tipo_inventario
     *
     * @return  self
     */ 
    public function setGlosa_tipo_inventario($glosa_tipo_inventario)
    {
        $this->glosa_tipo_inventario = $glosa_tipo_inventario;

        return $this;
    }

    /**
     * Get the value of ventaproducto_tipo_inventario
     */ 
    public function getVentaproducto_tipo_inventario()
    {
        return $this->ventaproducto_tipo_inventario;
    }

    /**
     * Set the value of ventaproducto_tipo_inventario
     *
     * @return  self
     */ 
    public function setVentaproducto_tipo_inventario($ventaproducto_tipo_inventario)
    {
        $this->ventaproducto_tipo_inventario = $ventaproducto_tipo_inventario;

        return $this;
    }

    /**
     * Get the value of orden_tipo_inventario
     */ 
    public function getOrden_tipo_inventario()
    {
        return $this->orden_tipo_inventario;
    }

    /**
     * Set the value of orden_tipo_inventario
     *
     * @return  self
     */ 
    public function setOrden_tipo_inventario($orden_tipo_inventario)
    {
        $this->orden_tipo_inventario = $orden_tipo_inventario;

        return $this;
    }

    /**
     * Get the value of vigente_tipo_inventario
     */ 
    public function getVigente_tipo_inventario()
    {
        return $this->vigente_tipo_inventario;
    }

    /**
     * Set the value of vigente_tipo_inventario
     *
     * @return  self
     */ 
    public function setVigente_tipo_inventario($vigente_tipo_inventario)
    {
        $this->vigente_tipo_inventario = $vigente_tipo_inventario;

        return $this;
    }

    public function All($tabla, $activas)
    {
        $marcar = $this->db->prepare("SELECT * FROM $tabla where vigente_tipo_inventario=$activas order by id_tipo_inventario desc");
        $marcar->execute();
        $result = $marcar->fetchAll(PDO::FETCH_ASSOC);
        $marcar = null;
        return $result;
    }
    public function Traer_Inventario($columna_inventario)
    {
        $sql = "SELECT * FROM tipo_inventario where $columna_inventario=?";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->id_tipo_inventario));
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }

    public function Registrar()
    {
        $sql = "INSERT INTO tipo_inventario(glosa_tipo_inventario,vigente_tipo_inventario)values(?,?)";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->getGlosa_tipo_inventario(), $this->getVigente_tipo_inventario()));
        $sentencia = null;
        return  $this->db->lastInsertId();
    }

    public function Actualizar()
    {
        $sql = "UPDATE  tipo_inventario  SET glosa_tipo_inventario=?  where id_tipo_inventario=?";
        $sentencia = $this->db->prepare($sql);
        try {
            $sentencia->execute(array($this->glosa_tipo_inventario, $this->id_tipo_inventario));
        } catch (PDOException $e) {
            $this->db->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        }
        $sentencia = null;
        return  $this->db->lastInsertId();
    }

    public function ActualizarVigente()
    {
        $sql = "UPDATE  tipo_inventario  SET vigente_tipo_inventario=?  where id_tipo_inventario=? ";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->vigente_tipo_inventario, $this->id_tipo_inventario));
        return $sentencia->fetch();
        $sentencia = null;
    }

}
