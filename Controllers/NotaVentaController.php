<?php

require_once "models/Notaventa.php";
require_once "models/NotaventaDetalle.php";
require_once "models/Producto.php";
require_once "Helpers/JwtAuth.php";
require_once "EloquentsModel/Boleta.php";
require_once "EloquentsModel/Factura.php";


use Dompdf\Dompdf;

class NotaVentaController
{

    private $request;
    public function __construct()
    {
        $DatosPost = file_get_contents("php://input");
        $this->request = json_decode($DatosPost);
    }
    public function ListarProductoVenta()
    {

        $NotaVenta = new Notaventa();
        $respuesta = $NotaVenta->ListarProductoVenta($this->request->id_producto);
        echo json_encode($respuesta);
    }
    public function GenerarNotaVenta()
    {
      
        $NotaVenta = new Notaventa();
        $notaventa = $NotaVenta->Traer_ultimo_registro();
        $numeroNotaVenta = 0;
        if (isset($notaventa)) {
            $numeroNotaVenta = $notaventa['numero_venta'];
        } else {
            $numeroNotaVenta = 0;
        }
        $NotaVenta->setId_usuario($this->request->id_usuario);
        $NotaVenta->setId_apertura_caja($this->request->id_aperturar_caja);
        $NotaVenta->setNumero_venta($numeroNotaVenta + 1);
        $NotaVenta->setValor_venta($this->request->total_pagar);
        $NotaVenta->setDescuento_negocio_venta(empty($this->request->descuento) ? null : $this->request->descuento);
        $NotaVenta->setPagocliente_venta($this->request->pago);
        $NotaVenta->setCambiocliente_venta($this->request->cambio);
        $NotaVenta->setFecha_creacion_venta(date('Y-m-d'));
        $NotaVenta->setHora_creacion_venta(date('H:i:s'));
        $id_nota_venta = $NotaVenta->CrearVenta();
        $producto = json_decode($this->request->notaVenta);

        $notificar_stock = array();

        foreach ($producto as $key => $elemento) {

            //COMO SE ENVIA CON NUMERO LOS INDICES SE LE COLOCAL EL GET_OBJECT_VARSS
            // $elemento = get_object_vars($elemento);
            $producto = new Producto();
            $producto->setId_producto($elemento[0]);
            $datos = $producto->TraerProducto();
            $stockActual = $datos['stock_producto'] - $elemento[6];
            $producto->setStock_producto($stockActual);
            $producto->ActualizarProductoStock();
            $valor_venta = explode("S/", $elemento[7]);

            //para el pusher Notificar
            $elementos_pusher = [
                "id_producto" => $elemento[0],
                "cantidad" => $stockActual
            ];
            array_push($notificar_stock, $elementos_pusher);
            //
            $nota_venta_detalle = new NotaventaDetalle();
            $nota_venta_detalle->setId_nota_venta($id_nota_venta);
            $nota_venta_detalle->setId_producto($elemento[0]);
            $nota_venta_detalle->setFechacreacion_venta_detalle(date('Y-m-d H:i:s'));
            $nota_venta_detalle->setValor_venta($valor_venta[1]);
            $nota_venta_detalle->setCantidad_venta_detalle($elemento[6]);
            $nota_venta_detalle->setOrden_venta_detalle($key + 1);
            $nota_venta_detalle->CrearVentaDetalle();
        }
        $pusher = Eventopusher::conectar();
        $elementos = [
            "id_usuario" => $this->request->id_usuario,
            "notificar_stock" => $notificar_stock
        ];
        $pusher->trigger('Stock', 'ActualizarStockEvent', $elementos);

        $pathNotaVenta = $this->EnviarPagoVenta($id_nota_venta);
        $NotaVenta2 = new Notaventa();
        $NotaVenta2->setId_nota_venta($id_nota_venta);
        $NotaVenta2->setPath_nota_venta($pathNotaVenta);
        $NotaVenta2->actualizarPath();
        echo json_encode($pathNotaVenta);
    }

    public function EnviarPagoVenta($id_venta)
    {
        $fecha = date("Y-m-d H:i:s");
        $separaFecha = explode(" ", $fecha);
        $Fecha = explode("-", $separaFecha[0]);
        $filename = "Ticket_" . $Fecha[0] . $Fecha[1] . $Fecha[2] . time() . ".pdf";
        $path = "archivos/TicketVenta";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $path_imagen = __DIR__ . '/../archivos/imagenes/ahorro_farma.jpg';
        $imagen = base64_encode(file_get_contents($path_imagen));
        $NotaVenta = new Notaventa();
        $NotaVenta->setId_nota_venta($id_venta);
        $venta = $NotaVenta->Traer_Nota_Venta();
        $nota_venta_detalle = new NotaventaDetalle();
        $nota_venta_detalle->setId_nota_venta($id_venta);
        $venta_detalle = $nota_venta_detalle->Traer_Nota_Venta_Detalle();
        $valorventa = $venta['valor_venta'];
        $codigoBarra = base64_encode(file_get_contents((new \chillerlan\QRCode\QRCode())->render($valorventa)));
        //CHAPO TODO EL CONTENIDO EN HTML
        ob_start();
        require_once 'generar-pdf/pdf/puntoventa.php';
        $html = ob_get_clean();
        ////
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper(array(0, 0, 221, 744));

        // Render the HTML as PDF
        //GUARDAMOS EL DPF
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents($path . '/' . $filename, $output);
        //FIN DE GUARDAR PDF
        // $fecha = date("Y-m-d");
        // Output the generated PDF to Browser
        // $dompdf->stream("Cierre_Caja_$fecha.pdf", array("Attachment" => 0));
        return $filename;
    }


    public function VisualizarNotaVentaTicket()
    {
        $pathticket = $this->request->pathticket;
        $pathtoFile = "http://localhost/MVC_APIVENTA/archivos/TicketVenta/$pathticket";
        echo json_encode($pathtoFile);
    }
}
