<?php
class Usuario
{
    private $db;
    public function __construct()
    {
        $this->db = database::conectar();
    }
    private $id_usuario;
    private $nombre_usuario;
    private $email_usuario;
    private $apellido_usuario;
    private $password_usuario;
    private $dni_usuario;
    private $direccion_usuario;
    private $rol_usuario;
    private $vigencia_usuario;
    private $session_usuario;
    private $path_usuario;

    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }


    public function getNombre_usuario()
    {
        return $this->nombre_usuario;
    }


    public function setNombre_usuario($nombre_usuario)
    {
        $this->nombre_usuario = $nombre_usuario;

        return $this;
    }


    public function getEmail_usuario()
    {
        return $this->email_usuario;
    }


    public function setEmail_usuario($email_usuario)
    {
        $this->email_usuario = $email_usuario;

        return $this;
    }

    public function getApellido_usuario()
    {
        return $this->apellido_usuario;
    }


    public function setApellido_usuario($apellido_usuario)
    {
        $this->apellido_usuario = $apellido_usuario;

        return $this;
    }


    public function getPassword_usuario()
    {
        return $this->password_usuario;
    }


    public function setPassword_usuario($password_usuario)
    {
        $this->password_usuario = $password_usuario;

        return $this;
    }


    public function getDni_usuario()
    {
        return $this->dni_usuario;
    }


    public function setDni_usuario($dni_usuario)
    {
        $this->dni_usuario = $dni_usuario;

        return $this;
    }

    public function getDireccion_usuario()
    {
        return $this->direccion_usuario;
    }


    public function setDireccion_usuario($direccion_usuario)
    {
        $this->direccion_usuario = $direccion_usuario;

        return $this;
    }


    public function getRol_usuario()
    {
        return $this->rol_usuario;
    }


    public function setRol_usuario($rol_usuario)
    {
        $this->rol_usuario = $rol_usuario;

        return $this;
    }


    public function getVigencia_usuario()
    {
        return $this->vigencia_usuario;
    }


    public function setVigencia_usuario($vigencia_usuario)
    {
        $this->vigencia_usuario = $vigencia_usuario;

        return $this;
    }

    public function getSession_usuario()
    {
        return $this->session_usuario;
    }

    public function setSession_usuario($session_usuario)
    {
        $this->session_usuario = $session_usuario;

        return $this;
    }

    public function getPath_usuario()
    {
        return $this->path_usuario;
    }


    public function setPath_usuario($path_usuario)
    {
        $this->path_usuario = $path_usuario;

        return $this;
    }


    public function TreandoUsuario()
    {
        $sql="SELECT * FROM usuario where email_usuario=? and  password_usuario=? and vigencia_usuario=1";
        $sentencia=$this->db->prepare($sql);
        $sentencia->execute(array($this->email_usuario,$this->password_usuario));
        return $sentencia->fetch();
        $sentencia=null;
    }

    public function Registrar(){
        $sql="INSERT INTO usuario(nombre_usuario,apellido_usuario,email_usuario,password_usuario,rol_usuario,vigencia_usuario)values(?,?,?,?,?,?)";
        $sentencia=$this->db->prepare($sql);
        try {
            $sentencia->execute(array($this->nombre_usuario,$this->apellido_usuario,$this->email_usuario,$this->password_usuario,$this->rol_usuario,$this->vigencia_usuario));
        } catch(PDOException $e) {
            $this->db->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        }
        $sentencia=null;
        return  $this->db->lastInsertId();

    }

    public function ActualizarSession(){
        $sql="UPDATE  usuario  SET session_usuario=?  where id_usuario=? ";
        $sentencia=$this->db->prepare($sql);
        $sentencia->execute(array($this->session_usuario,$this->id_usuario));
        return $sentencia->fetch();
        $sentencia=null;
    }

    
}
