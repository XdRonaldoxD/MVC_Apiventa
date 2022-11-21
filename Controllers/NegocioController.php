<?php

use Dompdf\Dompdf;
use Symfony\Component\Console\Helper\Helper;

require_once "EloquentsModel/Boleta.php";
require_once "EloquentsModel/Factura.php";
require_once "EloquentsModel/Folio.php";
require_once "EloquentsModel/Cliente.php";
require_once "EloquentsModel/ProductoHistorial.php";
require_once "EloquentsModel/Negocio.php";
require_once "EloquentsModel/NegocioDetalle.php";
require_once "EloquentsModel/producto.php";

class NegocioController
{
    private $request;
    public function __construct()
    {
        $DatosPost = file_get_contents("php://input");
        $this->request = json_decode($DatosPost);
    }
    public function GenerarNegocio()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $serie =strtoupper(helpers::generate_string($permitted_chars, 3));
        $tipo_documento = $this->request->tipo_documento;
        if ($tipo_documento === 'FACTURA') {
            $folio_documento = Folio::where('id_folio', 9)->first();
            $tipoDoc = "01";
            $serie = "F" . $serie;
            $datos_cliente = [
                "tipoDoc" => "6",
                "numDoc" => $this->request->factura->ruc,
                "rznSocial" => $this->request->factura->razonSocial,
                "address" => [
                    "direccion" => $this->request->factura->direccion,
                    "provincia" => $this->request->factura->provincia,
                    "departamento" => $this->request->factura->departamento,
                    "distrito" => $this->request->factura->distrito,
                    "ubigueo" => $this->request->factura->ubigeo
                ]
            ];
        } elseif ($tipo_documento === 'BOLETA') {
            $folio_documento = Folio::where('id_folio', 6)->first();
            $tipoDoc = "03";
            $serie = "B" . $serie;
            $datos_cliente = [
                "tipoDoc" => "1",
                "numDoc" => $this->request->boleta->dni,
                "rznSocial" => $this->request->boleta->nombres . ' ' . $this->request->boleta->apellidoPaterno . ' ' . $this->request->boleta->apellidoMaterno,
                "address" => [
                    "direccion" => "Direccion cliente",
                    "provincia" => "LIMA",
                    "departamento" => "LIMA",
                    "distrito" => "LIMA",
                    "ubigueo" => "150101"
                ]
            ];
        }

        $producto = $this->request->notaVenta;
        $mtoOperGravadas = 0;
        $mtoIGV = 0;
        $valorVenta = 0;
        $totalImpuestos = 0;
        $subTotal = 0;
        $mtoImpVenta = 0;
        $details = [];
        foreach ($producto as $key => $elemento) {
            $cantidad = intval($elemento[6]);
            $precio = intval(str_replace('S/', '', $elemento[5]));
            $mtoBaseIgv = round($precio / (1 + 0.18), 1);
            $igv = $precio - $mtoBaseIgv;
            $precio_sin_igv = $precio - $igv;

            $total_venta = intval(str_replace('S/', '', $elemento[7]));
            $total_venta_mtoBaseIgv = round($total_venta / (1 + 0.18), 1);
            $total_venta_igv = $total_venta - $total_venta_mtoBaseIgv;
            $total_venta_sin_igv = $total_venta - $igv;
            //-------------------------------------------------
            $mtoOperGravadas += $total_venta_sin_igv;
            $mtoIGV += $total_venta_igv;
            $totalImpuestos += $total_venta_igv;
            $valorVenta += $total_venta_sin_igv;
            $subTotal += $total_venta_sin_igv;
            $mtoImpVenta += $total_venta;
            // -----------------------------------------
            $elementos = [
                "codProducto" => "P002",
                "unidad" => "NIU",
                "descripcion" => $elemento[3],
                "cantidad" => $cantidad,
                "mtoValorUnitario" => round($precio_sin_igv, 2),
                "mtoValorVenta" => round($total_venta_sin_igv, 2),
                "mtoBaseIgv" => round($total_venta_sin_igv, 2),
                "porcentajeIgv" => 18,
                "igv" => round($total_venta_igv, 2),
                "tipAfeIgv" => 10,
                "totalImpuestos" => round($total_venta_igv, 2),
                "mtoPrecioUnitario" => round($precio + $igv, 2)
            ];
            array_push($details, $elementos);
        }

        //NOTA EL CORRELATIVO ES EL NUMERO DE FOLIO QUE AVANZA
        //PARA BOELTA ES B001-FACTURA ES F001
        $cantidad_digito_folio = strlen($folio_documento->numero_folio);
        $correlativo = "00000000";
        $correlativo = substr($correlativo, 0, (8 - $cantidad_digito_folio));
        $correlativo = $correlativo . $folio_documento->numero_folio;
        $arregloJson = array(
            "ublVersion" => "2.1",
            "tipoOperacion" => "0101",
            "tipoDoc" => $tipoDoc,
            "serie" => $serie,
            "correlativo" => $correlativo,
            "fechaEmision" => date('Y-m-d') . "T00:00:00-05:00",
            "formaPago" => [
                "moneda" => "PEN",
                "tipo" => "Contado"
            ],
            "tipoMoneda" => "PEN",
            "client" => $datos_cliente,
            "company" => [
                "ruc" => 90254813519,
                "razonSocial" => "PRUEBA",
                "nombreComercial" => "PRUEBA",
                "address" => [
                    "direccion" => "Lima , L. 123",
                    "provincia" => "LIMA",
                    "departamento" => "LIMA",
                    "distrito" => "LIMA",
                    "ubigueo" => "150101"
                ]
            ],
            "mtoOperGravadas" => round($mtoOperGravadas, 1),
            "mtoIGV" => round($mtoIGV, 1),
            "valorVenta" => round($valorVenta, 1),
            "totalImpuestos" => round($totalImpuestos, 1),
            "subTotal" => round($subTotal, 1),
            "mtoImpVenta" => round($mtoImpVenta, 1),
            "details" => $details,
            "legends" => [
                [
                    "code" => "1000",
                    "value" => "SON DOS CON 00/100 SOLES"
                ]
            ]
        );

        $payload = json_encode($arregloJson);
        // http_response_code(404);
        // echo $payload;
        // die;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://facturacion.apisperu.com/api/v1/invoice/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MjA0NDkxODcsImV4cCI6NDc3NDA0OTE4NywidXNlcm5hbWUiOiJ4cm9uYWxkbyIsImNvbXBhbnkiOiI5MDI1NDgxMzUxOSJ9.AqigLZMv7h7KQtC6ZUzXPJqbWdlr_ymmSCrqJeGUYu4WAe_gs16Vr0diNvK7qQFR9D87xIvzx68iviMOljqMH1WDkWM6CncplHhvuLeuG_ZobV3TKq5HbExSBtw8KTVE9R5gMDm_yQ_5uu1yw6sAwQduOF3LtmlegQaQjO0-1VezTD_gEqbT7ck5QGEdJq0ULemnlSszK8aZJE7b_cNvr_7n8Ha5of4P9VYtYfVgHa8D5JOezvODRtVauNsGRPxS4YzR35DTyPSJ_GNbvZ34lhm7BTxJqCXlXuxj081GBi2KdXWFt6XR603mOGQ2C6LKlatg95NYA3swooGvrwZHq3KF1oWghckfrKPieGZ3_p7N6O5_0dqHqUSsMLKcrY3de6y80WpOdyTkljVtyEJIhVn3CKyvL_-fNTz82qf71bLid18ozW7TGFAN6iGcRulHqWYVHBge_uAZa5lJGuThwKIIgR112rNM7tkoLVga1xd3juz9mPdCLPCaWC2HG6hB5J4bJA_afx3EvRGtRKrNZTuzmEcRdbYN1RBgvs6dRXQpmFr95ZnjdHPnJPRSlVHPC-ULkJd3Q6uegVrbYPpbIkNyY8Xa0Tm28_6PGFZYeabNw5AkRzRFxwR1oktQ9nyCLhi3539Gk_FD7noa86Vk5mNrnW5evSSACCDZ_YNOhUg',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $jsonArray  = json_decode($response, true);
        if ($jsonArray['sunatResponse']['success'] == false) {
            http_response_code(404);
            echo  $response;
            exit();
        }
        // http_response_code(404);
        // echo  $response;
        // exit();

        //VERIFICAMOS EL CLIENTE SI EXISTE O LO CREA
        if ($tipo_documento === 'BOLETA') {
            $cliente_encontrado = Cliente::where('dni_cliente', $this->request->boleta->dni)
                ->where('tipo_documento_cliente', 1)
                ->first();
        } else {
            $cliente_encontrado = Cliente::where('ruc_cliente', $this->request->factura->ruc)
                ->where('tipo_documento_cliente', 6)
                ->first();
        }
        if (isset($cliente_encontrado)) {
            $id_cliente = $cliente_encontrado->id_cliente;
        } else {
            $dni_cliente = null;
            $ruc_cliente = null;
            $nombre_cliente = null;
            $apellidopaterno_cliente = null;
            $apellidomaterno_cliente = null;
            if ($tipo_documento === 'BOLETA') {
                $tipo_documento_cliente = 1;
                $dni_cliente = $this->request->boleta->dni;
                $nombre_cliente = $this->request->boleta->nombres;
                $apellidopaterno_cliente = $this->request->boleta->apellidoPaterno;
                $apellidomaterno_cliente = $this->request->boleta->apellidoMaterno;
            } else {
                $tipo_documento_cliente = 6;
                $ruc_cliente = $this->request->factura->ruc;
            }
            $data_cliente = [
                'tipo_documento_cliente' => $tipo_documento_cliente,
                'dni_cliente' => $dni_cliente,
                'ruc_cliente' => $ruc_cliente,
                'nombre_cliente' => $nombre_cliente,
                'apellidopaterno_cliente' => $apellidopaterno_cliente,
                'apellidomaterno_cliente' => $apellidomaterno_cliente,
                'vigente_cliente' => 1,
            ];
            $cliente_creado = Cliente::create($data_cliente);
            $id_cliente = $cliente_creado->id_cliente;
        }
        //-----------------------------------------------------------------------------

        if ($tipo_documento === 'BOLETA') {
            //CREAMOS LA BOLETA-----------------------------------------------------------------------------------------
            $Datos_Boleta = [
                'id_usuario' => $this->request->id_usuario,
                'id_folio' => 6,
                'numero_boleta' => $correlativo,
                'serie_boleta' => $serie,
                'valor_boleta' => $subTotal,
                'fechacreacion_boleta' => date('Y-m-d H:i:s'),
                'iva_boleta' => $mtoIGV,
                'total_boleta' => $valorVenta,
                'xml_boleta' => $jsonArray['xml'],
                'cdrzip_boleta' => $jsonArray['sunatResponse']['cdrZip'],
                'estado_boleta' => 1,
                'comentario_boleta' => $jsonArray['sunatResponse']['cdrResponse']['description'],
            ];
            $folio_documento->numero_folio += 1;
            $folio_documento->save();
            $boleta_creado = Boleta::create($Datos_Boleta);
            $id_documento = $boleta_creado->id_boleta;
            //------------------------------------------------------------------------------------------------------------
        } else {
            //CREAMOS LA FACTURA------------------------------------------------------------------------------------------
            $Datos_Factura = [
                'id_usuario' => $this->request->id_usuario,
                'id_folio' => 9,
                'numero_factura' => $correlativo,
                'serie_factura' => $serie,
                'fechacreacion_factura' => date('Y-m-d H:i:s'),
                'iva_factura' => $mtoIGV,
                'total_factura' => $valorVenta,
                'xml_factura' => $jsonArray['xml'],
                'cdrzip_factura' => $jsonArray['sunatResponse']['cdrZip'],
                'estado_factura' => 1,
                'comentario_factura' => $jsonArray['sunatResponse']['cdrResponse']['description'],
            ];
            $folio_documento->numero_folio += 1;
            $folio_documento->save();
            $factura_creado = Factura::create($Datos_Factura);
            $id_documento = $factura_creado->id_factura;

            //-----------------------------------------------------------------------------------------------------------------
        }

        $Folio = Folio::where('id_folio', 2)->first();
        $negocio_crear = [
            'id_usuario' => $this->request->id_usuario,
            'id_folio' => $Folio->id_folio,
            'id_cliente' => $id_cliente,
            'fechacreacion_negocio' => date('Y-m-d H:i:s'),
            'numero_negocio' => $Folio->numero_folio,
            'valor_negocio' => $this->request->total_pagar,
            'vigente_negocio' => 1,
            'id_apertura_caja' => $this->request->id_aperturar_caja,
            'efectivo_negocio' => $this->request->cambio,
            'vuelto_negocio' => $this->request->pago
        ];
        $Folio->numero_folio += 1;
        $Folio->save();
        $Negocio = Negocio::create($negocio_crear);
        $producto = $this->request->notaVenta;
        $notificar_stock = array();
        foreach ($producto as $key => $elemento) {
            $cantidad = intval($elemento[6]);
            $precio = intval(str_replace('S/', '', $elemento[7]));
            $mtoBaseIgv = round($precio / (1 + 0.18), 1);
            $igv = $precio - $mtoBaseIgv;

            // RESTANDO STOCK DEL PRODUCTOS
            $producto_encontrado = producto::where('id_producto', $elemento[0])->first();
            $stockActual = $producto_encontrado->stock_producto - $cantidad;
            $producto_encontrado->stock_producto = $stockActual;
            $producto_encontrado->save();
            $producto_historial = [
                'id_usuario' => $this->request->id_usuario,
                'tipo_movimiento' => 'Quitar',
                'id_producto' => $elemento[0],
                'cantidadmovimiento_producto_historial' => $cantidad,
                'fecha_producto_historial' => date('Y-m-d H:i:s'),
                'comentario_producto_historial' => "$tipo_documento DE VENTA ELECTRONICA"
            ];
            ProductoHistorial::create($producto_historial);
            //-----------------------------------
            //para el pusher Notificar
            $elementos_pusher = [
                "id_producto" => $elemento[0],
                "cantidad" => $stockActual
            ];
            array_push($notificar_stock, $elementos_pusher);
            //
            $negocio_detalle = [
                'id_negocio' => $Negocio->id_negocio,
                'id_producto' => $elemento[0],
                'valorneto_negocio_detalle' => $mtoBaseIgv,
                'iva_negocio_detalle' => $igv,
                'total_negocio_detalle' => $precio,
                'fechacreacion_negocio_detalle' => date('Y-m-d H:i:s'),
                'cantidad_negocio_detalle' => $cantidad,
                // 'preciounitario_negocio_detalle',
            ];
            NegocioDetalle::create($negocio_detalle);
        }
        $pusher = Eventopusher::conectar();
        $elementos = [
            "id_usuario" => $this->request->id_usuario,
            "notificar_stock" => $notificar_stock
        ];
        $pusher->trigger('Stock', 'ActualizarStockEvent', $elementos);
      
        if ($tipo_documento === 'BOLETA') {
            $Boletas = Boleta::where('id_boleta', $id_documento)->first();
            $Boletas->id_negocio = $Negocio->id_negocio;
            $Boletas->id_cliente = $id_cliente;
            $Boletas->save();
        } else {
            $Facturas = Factura::where('id_factura', $id_documento)->first();
            $Facturas->id_negocio = $Negocio->id_negocio;
            $Facturas->id_cliente = $id_cliente;
            $Facturas->save();
        }
  
        $pathNotaVenta = $this->EnviarNegocioVenta($Negocio->id_negocio, $tipo_documento);
        echo json_encode($pathNotaVenta, $tipo_documento);
    }
    public function EnviarNegocioVenta($id_negocio, $tipo_documento)
    {
        if ($tipo_documento==='BOLETA') {
            $Boleta=Boleta::where('id_negocio',$id_negocio)->first();
            $serie=$Boleta->serie_boleta;
            $correlativo=$Boleta->numero_boleta;
        }else{
            $Factura=Factura::where('id_negocio',$id_negocio)->first();
            $serie=$Factura->serie_factura;
            $correlativo=$Factura->numero_factura;
        }
        $fecha = date("Y-m-d H:i:s");
        $separaFecha = explode(" ", $fecha);
        $Fecha = explode("-", $separaFecha[0]);
        $filename = "Ticket_" . $Fecha[0] . $Fecha[1] . $Fecha[2] . time() . ".pdf";
        $path = 'archivos/' . $tipo_documento . 'Venta';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $path_imagen = __DIR__ . '/../archivos/imagenes/ahorro_farma.jpg';
        $imagen = base64_encode(file_get_contents($path_imagen));
        $negocios = Negocio::join('negocio_detalle', 'negocio_detalle.id_negocio', 'negocio.id_negocio')
            ->join('producto', 'producto.id_producto', 'negocio_detalle.id_producto')
            ->join('cliente','cliente.id_cliente','negocio.id_cliente')
            ->where('negocio.id_negocio', $id_negocio)
            ->get();
        $valorventa = $negocios[0]['valor_negocio'];
        $fecha_creacion_venta = $negocios[0]['fechacreacion_negocio'];
        $pagocliente_venta = $negocios[0]['efectivo_negocio'];
        $vuelto_negocio = $negocios[0]['vuelto_negocio'];
        $codigoBarra = base64_encode(file_get_contents((new \chillerlan\QRCode\QRCode())->render($valorventa)));
        $informacion_empresa = [
            "nombre_empresa" => "AHORROFARMA",
            "ruc" => "10468481940",
            "razonSocial" => 'DORA YULITH REMIGIO ZELAYA',
            "direccion" => 'RB. LOS JARDINES MZ. C LT. 10',
            "departamento" => 'LIMA',
            "provincia" => 'HUAURA',
            "distrito" => 'SANTA MARIA',
            'tipo_documento'=>$tipo_documento
        ];
        $informacion_cliente = [
            "dni_cliente" => $negocios[0]['dni_cliente'],
        ];
        $informacion_documento = [
            'serie' => $serie,
            'correlativo' => $correlativo
        ];
        //CHAPO TODO EL CONTENIDO EN HTML
        ob_start();
        require_once 'generar-pdf/pdf/Negocioventa.php';
        $html = ob_get_clean();
        ////
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper(array(0, 0, 221, 544));
        // Render the HTML as PDF
        //GUARDAMOS EL DPF
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents($path . '/' . $filename, $output);

        return $filename;
    }


    public function VisualizarVentaTicket()
    {
        $pathticket = $this->request->pathticket;
        $pathtoFile = "http://localhost/MVC_APIVENTA/archivos/{$this->request->tipo_documento}Venta/$pathticket";
        echo json_encode($pathtoFile);
    }
}
