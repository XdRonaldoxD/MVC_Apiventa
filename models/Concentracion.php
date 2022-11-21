<?php


class Concentracion
{

    private $db;
    public function __construct()
    {
        $this->db = database::conectar();
    }
    private $id_tipo_concentracion;
    private $glosa_tipo_concentracion;
    private $orden_tipo_concentracion;
    private $vigente_tipo_concentracion;
    private $id_unidad;
    public function getId_tipo_concentracion()
    {
        return $this->id_tipo_concentracion;
    }
    public function setId_tipo_concentracion($id_tipo_concentracion)
    {
        $this->id_tipo_concentracion = helpers::validar_input($id_tipo_concentracion);
        return $this;
    }
    public function getGlosa_tipo_concentracion()
    {
        return $this->glosa_tipo_concentracion;
    }
    public function setGlosa_tipo_concentracion($glosa_tipo_concentracion)
    {
        $this->glosa_tipo_concentracion = helpers::validar_input($glosa_tipo_concentracion);
        return $this;
    }
    public function getOrden_tipo_concentracion()
    {
        return $this->orden_tipo_concentracion;
    }
    public function setOrden_tipo_concentracion($orden_tipo_concentracion)
    {
        $this->orden_tipo_concentracion = helpers::validar_input($orden_tipo_concentracion);
        return $this;
    }
    public function getVigente_tipo_concentracion()
    {
        return $this->vigente_tipo_concentracion;
    }
    public function setVigente_tipo_concentracion($vigente_tipo_concentracion)
    {
        $this->vigente_tipo_concentracion = helpers::validar_input($vigente_tipo_concentracion);
        return $this;
    }
    public function getId_unidad()
    {
        return $this->id_unidad;
    }
    public function setId_unidad($id_unidad)
    {
        $this->id_unidad = helpers::validar_input($id_unidad);
        return $this;
    }

    public function Registrar()
    {
        $sql = "INSERT INTO tipo_concentracion(id_unidad,glosa_tipo_concentracion,vigente_tipo_concentracion)
        values(?,?,?)";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->id_unidad, $this->glosa_tipo_concentracion, $this->vigente_tipo_concentracion));
        $sentencia = null;
        return  $this->db->lastInsertId();
    }

    public function Actualizar()
    {
        $sql = "UPDATE  tipo_concentracion  SET id_unidad=?,glosa_tipo_concentracion=?  where id_tipo_concentracion=?";
        $sentencia = $this->db->prepare($sql);
        try {
            $sentencia->execute(array($this->id_unidad, $this->glosa_tipo_concentracion, $this->id_tipo_concentracion));
        } catch (PDOException $e) {
            $this->db->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        }
        $sentencia = null;
        return  $this->db->lastInsertId();
    }
    public function Eliminar()
    {
        $sql = "DELETE FROM  tipo_concentracion  where id_tipo_concentracion=?";
        $sentencia = $this->db->prepare($sql);
        try {
            $sentencia->execute(array($this->getId_tipo_concentracion()));
        } catch (PDOException $e) {
            $this->db->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        }
        $sentencia = null;
        return 'ok';
    }
    public function TraerTipoConcentracion()
    {
        //OTRA MANERA DE TRAER LA CONSULTA
        $sql = "SELECT * FROM tipo_concentracion where ";
        if (isset($this->id_tipo_concentracion)) {
            $sql .= " id_tipo_concentracion=:id_tipo_concentracion and";
        }
        if (isset($this->id_unidad)) {
            $sql .= " id_unidad=:id_unidad and";
        }
        if (isset($this->vigente_tipo_concentracion)) {
            $sql .= " vigente_tipo_concentracion=:vigente_tipo_concentracion and";
        }
        if (isset($this->glosa_tipo_concentracion)) {
            $sql .= " glosa_tipo_concentracion=:glosa_tipo_concentracion and";
        }
        $sql = rtrim($sql, "and");
        $sentencia = $this->db->prepare($sql);
        if ($this->id_tipo_concentracion) {
            $sentencia->bindParam(':id_tipo_concentracion', $this->id_tipo_concentracion, helpers::ValidarTipoParametros($this->id_tipo_concentracion));
        }
        if ($this->id_unidad) {
            $sentencia->bindParam(':id_unidad', $this->id_unidad, helpers::ValidarTipoParametros($this->id_unidad));
        }
        if ($this->vigente_tipo_concentracion) {
            $sentencia->bindParam(':vigente_tipo_concentracion', $this->vigente_tipo_concentracion, helpers::ValidarTipoParametros($this->vigente_tipo_concentracion));
        }
        if ($this->glosa_tipo_concentracion) {
            $sentencia->bindParam(':glosa_tipo_concentracion', $this->glosa_tipo_concentracion, helpers::ValidarTipoParametros($this->glosa_tipo_concentracion));
        }
        $sentencia->execute();
        if (isset($this->id_tipo_concentracion) || isset($this->glosa_tipo_concentracion)) {
            $datos = $sentencia->fetch(PDO::FETCH_ASSOC);
        } else {
            $datos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        }
        return $datos;
        $sentencia = null;
    }
    
}
