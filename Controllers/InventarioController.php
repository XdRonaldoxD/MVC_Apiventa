<?php

use Dompdf\Dompdf;

require_once "models/Inventario.php";
require_once "EloquentsModel/inventarioproducto.php";
require_once "EloquentsModel/producto.php";
require_once "Controllers/CajaController.php";
class InventarioController
{
    private $request;
    public function __construct()
    {
        $DatosPost = file_get_contents("php://input");
        $this->request = json_decode($DatosPost);
    }

    public function ListarInventario()
    {
        $Inventario = new Inventario();
        $todos = $Inventario->All("tipo_inventario", 1);
        echo json_encode($todos);
    }
    public function ListarInventarioDesactivados()
    {
        $Inventario = new Inventario();
        $todos = $Inventario->All("tipo_inventario", 0);
        echo json_encode($todos);
    }

    public function TraerDatoInventario()
    {
        $Inventario = new Inventario();
        $Inventario->setId_tipo_inventario($this->request->id_tipo_inventario);
        echo json_encode($Inventario->Traer_Inventario('id_tipo_inventario'));
    }

    public function Guardar_Editar_Inventario()
    {
        $Inventario = new Inventario();
        if (!isset($this->request->id_tipo_inventario)) {
            $Inventario->setGlosa_tipo_inventario($this->request->glosa_tipo_inventario);
            $Inventario->setVigente_tipo_inventario(1);
            $Inventario->Registrar();
            $respuesta = "Creado";
        } else {
            $Inventario->setId_tipo_inventario($this->request->id_tipo_inventario);
            $Inventario->setGlosa_tipo_inventario($this->request->glosa_tipo_inventario);
            $Inventario->Actualizar();
            $respuesta = "Editado";
        }
        echo json_encode($respuesta);
    }

    public function DesactivarInventario()
    {
        $Inventario = new Inventario();
        $Inventario->setId_tipo_inventario($this->request->id_tipo_inventario);
        $Inventario->setVigente_tipo_inventario(0);
        $Inventario->ActualizarVigente();
        echo json_encode("Desactivado");
    }
    public function ActivarInventario()
    {
        $Inventario = new Inventario();
        $Inventario->setId_tipo_inventario($this->request->id_tipo_inventario);
        $Inventario->setVigente_tipo_inventario(1);
        $Inventario->ActualizarVigente();
        echo json_encode("Activado");
    }

    public function ListarInventarioProducto()
    {
        $Lista = inventarioproducto::join('usuario', "usuario.id_usuario", "=", "inventario_producto.id_usuario")
            ->whereRaw("(inventario_producto.fecha_creacion_inventario >= ? AND inventario_producto.fecha_creacion_inventario <= ?)", [$_POST['fechaInicio'], $_POST['FechaFin']])
            ->orderby("inventario_producto.id_inventario", "desc")
            ->get();
        $datos = array();
        foreach ($Lista as $key => $elemento) {
            $element = [
                "usuario" => $elemento->nombre_usuario . " " . $elemento->apellido_usuario,
                "fecha_inventario" => date('d/m/Y g:i A', strtotime($elemento->fecha_inventario)),
                "path_inventario" => $elemento->path_inventario
            ];
            array_push($datos, $element);
        }
        echo json_encode($datos);
    }

    public function ReporteProductoInventario(){
     
        $inventario=$this->LogicaProductoInventario($_POST);
        echo json_encode($inventario);
    }

    public function LogicaProductoInventario($request)
    {
        $fechaInicio = $request['fechaInicio'];
        $fechaFin = $request['FechaFin'];
        $producto = producto::join("tipo_inventario", "tipo_inventario.id_tipo_inventario", "=", "producto.id_tipo_inventario")
            ->select('producto.id_producto', 'producto.codigo_producto', "producto.precioventa_producto","producto.stock_producto","producto.preciocosto_producto",'tipo_inventario.glosa_tipo_inventario', 'producto.glosa_producto', 'producto.id_tipo_inventario')
            ->whereRaw("(producto.fechacreacion_producto >= ? AND producto.fechacreacion_producto <= ?)", [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->get()
            ->toArray();
        $cajacontroller = new CajaController();
        $inventario = $cajacontroller->group_by('glosa_tipo_inventario', $producto);
        return $inventario;
    }

    public function AlmacenarProductoInventario(){
        $inventario=$this->LogicaProductoInventario($_POST);
        $fechaInicio=$_POST['fechaInicio'];
        $fechaFin=$_POST['FechaFin'];
        $fecha = date("Y-m-d H:i:s");
        $separaFecha = explode(" ", $fecha);
        $Fecha = explode("-", $separaFecha[0]);
        $filename = "AlmacenarProductoInventario_" . $Fecha[0] . $Fecha[1] . $Fecha[2] . time() . ".pdf";
        $path = "archivos/ProductoInventario";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        ob_start();
        require_once 'generar-pdf/pdf/productoinventario.php';
        $html = ob_get_clean();
        ////
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4');
        // Render the HTML as PDF
        //GUARDAMOS EL DPF
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents($path . '/' . $filename, $output);

        $datos=array(
            "id_usuario"=>$_POST['id_usuario'],
            "path_inventario"=>$filename,
            "fecha_creacion_inventario"=>date('Y-m-d'),
            "fecha_inventario"=>date('Y-m-d H:i:s')
        );
        inventarioproducto::create($datos);
        echo "ok";
    }

    // public function VerificarSkuProducto(){
    //    $stock=trim($_POST['stock_producto']);
    //     $Producto=producto::where("stock_producto","=",'Comprimido')->get();
    //     echo $Producto;
    
    // }

    public function DesactivarProducto()
    {
        $producto = producto::where('id_producto', $_POST['id_producto'])->first();
        $producto->vigente_producto = 0;
        $producto->save();
        echo json_encode($producto->glosa_producto);
    }
    public function ActivarProducto()
    {
        $producto = producto::where('id_producto',  $_POST['id_producto'])->first();
        $producto->vigente_producto = 1;
        $producto->save();
        echo json_encode($producto->glosa_producto);

    }
}
