<?php


class TipoProducto{

    private $db;
    public function __construct()
    {
        $this->db = database::conectar();
    }

    private $id_tipo_producto;
    private $glosa_tipo_producto;
    private $orden_tipo_producto;
    private $vigente_tipo_producto;
    

    /**
     * Get the value of id_tipo_producto
     */ 
    public function getId_tipo_producto()
    {
        return $this->id_tipo_producto;
    }

    /**
     * Set the value of id_tipo_producto
     *
     * @return  self
     */ 
    public function setId_tipo_producto($id_tipo_producto)
    {
        $this->id_tipo_producto = $id_tipo_producto;

        return $this;
    }

    /**
     * Get the value of glosa_tipo_producto
     */ 
    public function getGlosa_tipo_producto()
    {
        return $this->glosa_tipo_producto;
    }

    /**
     * Set the value of glosa_tipo_producto
     *
     * @return  self
     */ 
    public function setGlosa_tipo_producto($glosa_tipo_producto)
    {
        $this->glosa_tipo_producto = $glosa_tipo_producto;

        return $this;
    }

    /**
     * Get the value of orden_tipo_producto
     */ 
    public function getOrden_tipo_producto()
    {
        return $this->orden_tipo_producto;
    }

    /**
     * Set the value of orden_tipo_producto
     *
     * @return  self
     */ 
    public function setOrden_tipo_producto($orden_tipo_producto)
    {
        $this->orden_tipo_producto = $orden_tipo_producto;

        return $this;
    }

    /**
     * Get the value of vigente_tipo_producto
     */ 
    public function getVigente_tipo_producto()
    {
        return $this->vigente_tipo_producto;
    }

    /**
     * Set the value of vigente_tipo_producto
     *
     * @return  self
     */ 
    public function setVigente_tipo_producto($vigente_tipo_producto)
    {
        $this->vigente_tipo_producto = $vigente_tipo_producto;

        return $this;
    }

    public function Traer_glosa_Tipo_Producto()
    {
        $sql = "SELECT * FROM tipo_producto where glosa_tipo_producto LIKE '%".$this->getGlosa_tipo_producto()."%'";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }

    
    public function Traer_Ultimo_Producto()
    {
        $sql = "SELECT top 1 * FROM tipo_producto where order by id_tipo_producto desc";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }

    public function Registrar()
    {
        $sql = "INSERT INTO tipo_producto(glosa_tipo_producto,orden_tipo_producto,vigente_tipo_producto)values(?,?,?)";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->getGlosa_tipo_producto(), $this->getOrden_tipo_producto(),$this->getVigente_tipo_producto()));
        $sentencia = null;
        return  $this->db->lastInsertId();
    }
 
}