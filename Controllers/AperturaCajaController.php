<?php

use Dompdf\Dompdf;
require_once "EloquentsModel/aperturacaja.php";
require_once "EloquentsModel/notaventa.php";
require_once "EloquentsModel/Caja.php";
require_once "Controllers/CajaController.php";


class AperturaCajaController
{
    // public function ReporteCajaCerrado()
    // {
    //     $dompdf = new Dompdf();
    //     $dompdf->loadHtml('hello world');
    //     // (Optional) Setup the paper size and orientation
    //     $dompdf->setPaper('A4', 'landscape');
    //     // Render the HTML as PDF
    //     $dompdf->render();
    //     $fecha = date("Y-m-d");
    //     // Output the generated PDF to Browser
    //     $dompdf->stream("Cierre Caja $fecha.pdf", array("Attachment" => 0));
    // }
    public function listarApertura()
    {
        $listarAperturar = aperturacaja::join("usuario", 'usuario.id_usuario', '=', 'apertura_caja.id_usuario');
        $listarAperturar = $listarAperturar->where('id_caja', $_POST['id_caja']);
        if (!empty($_POST['fecha_incio']) && !empty($_POST['fecha_fin'])) {
            $listarAperturar = $listarAperturar->whereRaw("(apertura_caja.apertura_caja_fecha >= ? AND apertura_caja.apertura_caja_fecha <= ?)", [$_POST['fecha_incio'], $_POST['fecha_fin']]);
        }
        $listarAperturar = $listarAperturar->orderBy('apertura_caja.id_apertura_caja', "desc");
        $listarAperturar = $listarAperturar->get();
        $listar = array();
        if (isset($listarAperturar)) {
            foreach ($listarAperturar as $elemento) {
                $datos = array(
                    "id_apertura_caja" => $elemento->id_apertura_caja,
                    "id_caja" => $elemento->id_caja,
                    "usuario" => $elemento->nombre_usuario . ' ' . $elemento->apellido_usuario,
                    "apertura_caja_fechainicio" => date('d/m/Y g:i A', strtotime($elemento->apertura_caja_fechainicio)),
                    "apertura_caja_fechafin" => isset($elemento->apertura_caja_fechafin) ? date('d/m/Y g:i A', strtotime($elemento->apertura_caja_fechafin)) : '',
                    "apertura_caja_monto_inicial" => number_format($elemento->apertura_caja_monto_inicial, 2),
                    "apertura_caja_monto_final" => number_format($elemento->apertura_caja_monto_final, 2),
                    "apertura_caja_total_ventas" => number_format($elemento->apertura_caja_total_ventas, 2),
                    "apertura_caja_estado" => $elemento->apertura_caja_estado

                );
                array_push($listar, $datos);
            }
        }

        echo json_encode($listar);
    }

    public function NumeroCaja()
    {
        $caja = caja::select("numero_caja")->where('id_caja', $_POST['id_caja'])->first();
        echo $caja;
    }


    public function VerificarCajaAbierta()
    {
        $caja = aperturacaja::where('apertura_caja_estado', 1)->get();
        $totalVentas = 0;
        $CantidadVentas = 0;
        $descuentoVentas = 0;
        $datoCaja = null;
        foreach ($caja as $key => $elementos) {
            $cajaAbierta = aperturacaja::where('id_caja', $elementos->id_caja)
                ->where('apertura_caja_estado', 1)
                ->first();
            if (isset($cajaAbierta)) {
                $datoCaja = $cajaAbierta;
                $totalVentas = notaventa::select("valor_venta")->where('id_apertura_caja', $cajaAbierta->id_apertura_caja)
                    ->get()
                    ->sum("valor_venta");
                $descuentoVentas = notaventa::select("descuento_negocio_venta")->where('id_apertura_caja', $cajaAbierta->id_apertura_caja)
                    ->get()
                    ->sum("descuento_negocio_venta");
                $CantidadVentas = notaventa::where('id_apertura_caja', $cajaAbierta->id_apertura_caja)
                    ->get()
                    ->count("id_apertura_caja");
            }
        }
        $datos = array(
            "cajaAbierta" => $datoCaja,
            "totalVentas" => $totalVentas,
            "descuentoVentas" => $descuentoVentas,
            "CantidadVentas" => $CantidadVentas
        );


       echo json_encode($datos);
    }

 


    public function ReporteVentaCerrado($id_apertura_caja)
    {
        $fecha = date("Y-m-d H:i:s");
        $separaFecha = explode(" ", $fecha);
        $Fecha = explode("-", $separaFecha[0]);
        $filename = "Venta_" . $Fecha[0] . $Fecha[1] . $Fecha[2] . time() . ".pdf";
        $path = "archivos/VentaCerrado";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $caja = new CajaController();
        $inventario = $caja->LogicaNotaVenta($id_apertura_caja);
        $fechaInicio = null;
        $fechaFin = null;
        ob_start();
        require_once 'generar-pdf/pdf/detalleproducto.php';
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4');
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
    public function ReporteCajaCerrado($id_apertura_caja)
    {
        $fecha = date("Y-m-d H:i:s");
        $separaFecha = explode(" ", $fecha);
        $Fecha = explode("-", $separaFecha[0]);
        $filename = "Ticket_" . $Fecha[0] . $Fecha[1] . $Fecha[2] . time() . ".pdf";
        $path = "archivos/TicketCerrado";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $pathimagen = 'archivos/imagenes/ahorro_farma.jpg';
        $imagen = base64_encode(file_get_contents($pathimagen));
        $cierreCaja = aperturacaja::select('usuario.nombre_usuario', 'apellido_usuario', 'apertura_caja.*')
            ->join('usuario', 'usuario.id_usuario', '=', 'apertura_caja.id_usuario')
            ->where('id_apertura_caja', $id_apertura_caja)
            ->first()
            ->toArray();
        // dd($cierreCaja);
        $valorventa = $cierreCaja['apertura_caja_total_ventas'];
        // print_r($valorventa);
        // die;
        if ($valorventa==0) {
            $codigoBarra='';
        }else{
            $codigoBarra = base64_encode(file_get_contents((new \chillerlan\QRCode\QRCode())->render($valorventa)));
        }
        // print_r($codigoBarra);
        ob_start();
        require_once 'generar-pdf/pdf/CierreCaja.php';
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


    public function Guardar_Cerrar_Apertura_Caja()
    {
        if ($_POST['id_apertura_caja'] === 'undefined') {
            $crearApertura = array(
                "id_caja" => $_POST['id_caja'],
                "id_usuario" => $_POST['id_usuario'],
                "apertura_caja_fecha" => date('Y-m-d'),
                "apertura_caja_fechainicio" => date('Y-m-d H:i:s'),
                'apertura_caja_monto_inicial' => ($_POST['apertura_caja_monto_inicial'] === 'undefined') ? null : $_POST['apertura_caja_monto_inicial'],
            );
            aperturacaja::create($crearApertura);
            $respuesta = "Creado";
        } else {
            $caja = aperturacaja::where('id_apertura_caja', $_POST['id_apertura_caja'])->first();
            $caja->apertura_caja_fechafin = date('Y-m-d H:i:s');
            $caja->apertura_caja_monto_final = $_POST['apertura_caja_monto_final'];
            $caja->apertura_caja_total_ventas = $_POST['apertura_caja_total_ventas'];
            $caja->apertura_caja_descuento = $_POST['apertura_caja_descuento'];
            $caja->apertura_caja_cantidad_ventas = $_POST['apertura_caja_cantidad_ventas'];
            $caja->apertura_caja_estado = 0;
            $caja->save();

            //GUARDAMOS LOS PDF
            $caja = aperturacaja::where('id_apertura_caja',  $_POST['id_apertura_caja'])->first();
            $apertura_caja_venta_path = $this->ReporteVentaCerrado($_POST['id_apertura_caja']);
            $apertura_caja_ticket_path = $this->ReporteCajaCerrado($_POST['id_apertura_caja']);
            $caja->apertura_caja_venta_path = $apertura_caja_venta_path;
            $caja->apertura_caja_ticket_path = $apertura_caja_ticket_path;
            $caja->save();
            $respuesta = "Cerrado";
        }
        echo json_encode($respuesta);
    }
    public function VisualizaCajaCerrado()
    {
        $caja = aperturacaja::where("id_apertura_caja", $_POST['id_apertura_caja'])->first();
        $datos=[
            "venta_path" => "http://localhost/MVC_APIVENTA/archivos/VentaCerrado/$caja->apertura_caja_venta_path",
            "ticket_path" =>"http://localhost/MVC_APIVENTA/archivos/TicketCerrado/$caja->apertura_caja_ticket_path"
        ];
        echo json_encode($datos);
    }

    public function VerificarCajaAbierto(){
        $aperturar=aperturacaja::select('id_apertura_caja')
        ->where("apertura_caja_estado",1)->first();
        if (isset($aperturar)) {
            echo json_encode($aperturar);
        }else{
            echo json_encode([]);
        }
  
    }



}
