<?php
class Marca
{
    private $db;
    public function __construct()
    {
        $this->db = database::conectar();
    }
    public $id_marca;
    public $glosa_marca;
    public $vigente_marca;

    function get_glosa_marca()
    {
        return $this->glosa_marca;
    }
    function set_glosa_marca($glosa_marca)
    {
        $this->glosa_marca = $glosa_marca;
    }

    function get_vigente_marca()
    {
        return $this->vigente_marca;
    }
    function set_vigente_marca($vigente_marca)
    {
        $this->vigente_marca = $vigente_marca;
    }

    public function getId_marca()
    {
        return $this->id_marca;
    }

    public function setId_marca($id_marca)
    {
        $this->id_marca = $id_marca;

        return $this;
    }

    public function All($tabla, $activas)
    {
        $marcar = $this->db->prepare("SELECT * FROM $tabla where vigente_marca=$activas order by id_marca desc");
        $marcar->execute();
        $result = $marcar->fetchAll(PDO::FETCH_ASSOC);
        $marcar = null;
        return $result;
    }

    public function Traer_Marca($columna_marca)
    {
        $sql = "SELECT * FROM marca where $columna_marca=?";
        $sentencia = $this->db->prepare($sql);
        if ($this->getId_marca()) {
            $sentencia->execute(array($this->getId_marca()));
        }
        if ($this->get_glosa_marca()) {
            $sentencia->execute(array($this->get_glosa_marca()));
        }
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }
    public function Registrar()
    {
        $sql = "INSERT INTO marca(glosa_marca,vigente_marca)values(?,?)";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->glosa_marca, $this->vigente_marca));
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
