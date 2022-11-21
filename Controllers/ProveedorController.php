<?php
require_once "models/Proveedor.php";
require_once "Helpers/JwtAuth.php";

class ProveedorController
{

    private $request;
    public function __construct()
    {
        $DatosPost = file_get_contents("php://input");
        $this->request = json_decode($DatosPost);
    }

    public function Listarproveedor()
    {
        $marca = new Proveedor();
        $todos = $marca->All("proveedor", 1);
        echo json_encode($todos);
    }
    public function ListarproveedorDesactivados()
    {
        $marca = new Proveedor();
        $todos = $marca->All("proveedor", 0);
        echo json_encode($todos);
    }

    public function Guardar_Editar_Proveedor()
    {

        // echo json_encode($this->request);
        $marca = new Proveedor();
        $marca->setGlosa_proveedor($this->request->glosa_proveedor);
        $marca->setRuc_proveedor(isset($this->request->ruc_proveedor) ? $this->request->ruc_proveedor : null);
        $marca->setDireccion_proveedor($this->request->direccion_proveedor);
        $marca->setTelefono_proveedor(isset($this->request->telefono_proveedor)  ? $this->request->telefono_proveedor : null);
        $marca->setE_mail_proveedor(isset($this->request->e_mail_proveedor) ? $this->request->e_mail_proveedor : null);
        $marca->setComentario_proveedor(isset($this->request->comentario_proveedor) ? $this->request->comentario_proveedor : null);
        if (!isset($this->request->id_proveedor)) {
            $marca->setVigente_proveedor(1);
            $marca->Registrar();
            $respuesta = "Creado";
        } else {
            $marca->setId_proveedor($this->request->id_proveedor);
            $marca->Actualizar();
            $respuesta = "Editado";
        }
        echo json_encode($respuesta);
    }

    public function DesactivarProveedor()
    {
        $marca = new Proveedor();
        $marca->setId_proveedor($this->request->id_proveedor);
        $marca->setVigente_proveedor(0);
        $marca->ActualizarVigente();
        echo json_encode("ok");
    }
    public function ActivarProveedor()
    {
        $marca = new Proveedor();
        $marca->setVigente_proveedor(1);
        $marca->setId_proveedor($this->request->id_proveedor);
        $marca->ActualizarVigente();
        echo json_encode("ok");
    }

    public function TraerProveedor()
    {
        // var_dump($this->request);
        $marca = new Proveedor();
        $marca->setId_proveedor($this->request->id_proveedor);
        $datos = $marca->Traer_Proveedor('id_proveedor');
        echo json_encode($datos);
    }
}
