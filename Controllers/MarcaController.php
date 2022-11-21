<?php
require_once "models/Marca.php";
require_once "Helpers/JwtAuth.php";

class MarcaController
{

    private $request;
    public function __construct()
    {
        $DatosPost = file_get_contents("php://input");
        $this->request = json_decode($DatosPost);
    }

    public function ListarMarca()
    {
        $marca = new Marca();
        $todos = $marca->All("marca", 1);
        echo json_encode($todos);
    }
    public function ListarMarcaDesactivados()
    {
        $marca = new Marca();
        $todos = $marca->All("marca", 0);
        echo json_encode($todos);
    }

    public function GuardarMarca()
    {

        // echo json_encode($this->request);
        $marca = new Marca();
        if (!isset($this->request->id_marca)) {
            $marca->set_glosa_marca($this->request->glosa_marca);
            $marca->set_vigente_marca(1);
            $marca->Registrar();
            $respuesta = "Creado";
        } else {
            $marca->setId_marca($this->request->id_marca);
            $marca->set_glosa_marca($this->request->glosa_marca);
            $marca->Actualizar();
            $respuesta = "Editado";
        }
        echo json_encode($respuesta);
    }

    public function DesactivarMarca()
    {
        $marca = new Marca();
        $marca->setId_marca($this->request);
        $marca->set_vigente_marca(0);
        $marca->ActualizarVigente();
        echo json_encode("ok");
    }
    public function ActivarMarca()
    {
        $marca = new Marca();
        $marca->set_vigente_marca(1);
        $marca->setId_marca($this->request);
        $marca->ActualizarVigente();
        echo json_encode("ok");
    }

    public function TraerMarca()
    {
        // var_dump($this->request);
        $marca = new Marca();
        $marca->setId_marca($this->request->id_marca);
        $datos = $marca->Traer_Marca('id_marca');
        echo json_encode($datos);
    }
}
