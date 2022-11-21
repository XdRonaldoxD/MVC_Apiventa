<?php

require_once "models/Unidad.php";
require_once "models/Concentracion.php";
require_once "models/Producto.php";
class UnidadController
{
    private $request;
    public function __construct()
    {
        $DatosPost = file_get_contents("php://input");
        $this->request = json_decode($DatosPost);
    }


    public function ListarPresentacion()
    {
        $marca = new Unidad();
        $todos = $marca->All("unidad", 1);
        echo json_encode($todos);
    }
    public function ListarPresentacionDesactivados()
    {
        $marca = new Unidad();
        $todos = $marca->All("unidad", 0);
        echo json_encode($todos);
    }

    public function TraerUnidad()
    {
        $marca = new Unidad();
        $marca->setId_unidad($this->request->id_unidad);
        $datos = $marca->Traer_Unidad('id_unidad');
        echo json_encode($datos);
    }


    public function Guardar_Editar_Concentracion()
    {
        $concentracion = new  concentracion();
        // echo json_encode($this->request);
        // die(http_response_code(403));

        if (!isset($this->request->id_tipo_concentracion)) {
            $concentracion->setId_unidad($this->request->id_unidad);
            $concentracion->setGlosa_tipo_concentracion($this->request->glosa_tipo_concentracion);
            $concentracion->setVigente_tipo_concentracion(1);
            $concentracion->registrar();
            $respuesta = "Creado";
        } else {
            $concentracion->setId_tipo_concentracion($this->request->id_tipo_concentracion);
            $concentracion->setGlosa_tipo_concentracion($this->request->glosa_tipo_concentracion);
            $concentracion->setId_unidad($this->request->id_unidad);
            $concentracion->Actualizar();
            $respuesta = "Editado";
        }
        echo json_encode($respuesta);
    }

    public function EliminaridConcentracion()
    {
        //SABER SI TIENE UN PRODUCTO ASOCIADO
        $producto_concentracion = new Producto();
        $producto_concentracion=$producto_concentracion->ConsultarProducto('id_tipo_concentracion',$this->request->id_tipo_concentracion);
        if ($producto_concentracion) {
            $repuesta='No se puede eliminar';
        }else{
            $concentracion = new  concentracion();
            $concentracion->setId_tipo_concentracion($this->request->id_tipo_concentracion);
            $concentracion->Eliminar();
            $repuesta='Eliminado';

        }
        echo json_encode($repuesta);
    }

    public function GuardarUnidadEdit()
    {
        $unidad = new Unidad();
        if (!isset($this->request->id_unidad)) {
            $unidad->setGlosa_unidad($this->request->glosa_unidad);
            $unidad->setVigente_unidad(1);
            $unidad->Registrar();
            $respuesta = "Creado";
        } else {
            $unidad->setId_unidad($this->request->id_unidad);
            $unidad->setGlosa_unidad($this->request->glosa_unidad);
            $unidad->Actualizar();
            $respuesta = "Editado";
        }
        echo json_encode($respuesta);
    }
    public function DesactivarPresentacion()
    {
        $unidad = new Unidad();
        $unidad->setId_unidad($this->request->id_unidad);
        $unidad->setVigente_unidad(0);
        $unidad->ActualizarVigente();
        echo json_encode("Desactivado");
    }
    public function ActivarPresentacion()
    {
        $unidad = new Unidad();
        $unidad->setId_unidad($this->request->id_unidad);
        $unidad->setVigente_unidad(1);
        $unidad->ActualizarVigente();
        echo json_encode("Activado");
    }

    public function TraerTipoConcentracion()
    {
        $concentracion = new  concentracion();
        $concentracion->setId_unidad($this->request->id_unidad);
        $concentracion->setVigente_tipo_concentracion(1);
        $datos = $concentracion->TraerTipoConcentracion();
        echo json_encode($datos);
    }

    public function TraeridConcentracion()
    {
        $concentracion = new  concentracion();
        $concentracion->setId_tipo_concentracion($this->request->id_tipo_concentracion);
        $datos = $concentracion->TraerTipoConcentracion();
        echo json_encode($datos);
    }



    

 
}
