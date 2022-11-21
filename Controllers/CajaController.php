<?php

use Dompdf\Dompdf;

require_once "EloquentsModel/Caja.php";
require_once "EloquentsModel/aperturacaja.php";
require_once "EloquentsModel/notaventadetalle.php";
require_once "EloquentsModel/notaventa.php";

class CajaController
{
    public function ListarCaja()
    {
        $caja = Caja::where("estado_caja", 1)->get();
        echo $caja;
    }
    public function GuardarCaja()
    {
        if ($_POST['id_caja'] === 'undefined') {
            $caja = caja::latest('id_caja')->first();
            $numero_caja = 1;
            if ($caja) {
                $numero_caja += $caja['numero_caja'];
            }
            $datosCaja = [
                'glosa_caja' => $_POST['glosa_caja'],
                'numero_caja' => $numero_caja,
                'estado_caja' => 1
            ];
            Caja::create($datosCaja);
            $respuesta = "Creado";
        } else {
            $caja = Caja::where('id_caja', $_POST['id_caja'])->first();
            $caja->glosa_caja = $_POST['glosa_caja'];
            $caja->estado_caja = $_POST['estado_caja'];
            $caja->save();
            $respuesta = "Editado";
        }
        echo  json_encode($respuesta);
    }

    public function EliminarCaja()
    {
        $AperturaCaja = aperturacaja::where('id_caja',  $_POST['id_caja'])->first();
        if (!isset($AperturaCaja)) {
            caja::where('id_caja',  $_POST['id_caja'])->delete();
            $respuesta = 'Eliminado';
        } else {
            $respuesta = 'Error';
        }
        echo json_encode($respuesta);
    }

    public function TraerDatoCaja()
    {
        $marca = caja::where('id_caja', $_POST['id_caja'])->first();
        echo $marca;
    }



    public function group_by($key, $data)
    {
        $result = array();
        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }
        return $result;
    }

    public function ListarNotaVenta()
    {
        $NotaVentaDetalle = notaventa::query();
        $NotaVentaDetalle = $NotaVentaDetalle->join('usuario', 'usuario.id_usuario', '=', 'nota_venta.id_usuario');
        if (!empty($_POST['fechaInicio']) && !empty($_POST['fechafin'])) {
            $NotaVentaDetalle = $NotaVentaDetalle->whereRaw("(nota_venta.fecha_creacion_venta >= ? AND nota_venta.fecha_creacion_venta <= ?)", [$_POST['fechaInicio'] . " 00:00:00", $_POST['fechafin'] . " 23:59:59"]);
        }
        $NotaVentaDetalle = $NotaVentaDetalle->select("nota_venta.id_nota_venta", "nota_venta.numero_venta", "nota_venta.fecha_creacion_venta", "nota_venta.hora_creacion_venta", 'usuario.nombre_usuario', 'usuario.apellido_usuario');
        $NotaVentaDetalle = $NotaVentaDetalle->orderBy('nota_venta.id_nota_venta', "desc");
        $NotaVentaDetalle = $NotaVentaDetalle->get()->toArray();

        $ventadetalle = array();
        foreach ($NotaVentaDetalle as $elemento) {
            $datos = array(
                "id_nota_venta" => $elemento['id_nota_venta'],
                "numero_venta" => $elemento['numero_venta'],
                "nombre_usuario" => $elemento['nombre_usuario'],
                "apellido_usuario" => $elemento['apellido_usuario'],
                "fecha_creacion_venta" => date("d/m/Y", strtotime($elemento['fecha_creacion_venta'])),
                "hora_creacion_venta" => date('g:i A', strtotime($elemento['hora_creacion_venta']))
            );
            array_push($ventadetalle, $datos);
        }
        echo json_encode($ventadetalle);
    }

    public function TraerNotaVenta()
    {
        $notaventa = notaventa::where("id_nota_venta", $_POST['id_nota_venta'])->first();
        echo $notaventa;
    }

    public function GraficaFechaNotaVenta()
    {
        $paciente = notaventa::whereBetWeen("fecha_creacion_venta", [$_POST['fechaInicio'], $_POST['FechaFin']])
            ->get()
            ->toArray();
        $arraymes = array();
        $Enero = 0;
        $Febrero = 0;
        $Marzo = 0;
        $Abril = 0;
        $Mayo = 0;
        $Junio = 0;
        $Julio = 0;
        $Agosto = 0;
        $Septiembre = 0;
        $Octubre = 0;
        $Noviembre = 0;
        $Diciembre = 0;
        foreach ($paciente as  $elemento) {
            $meses = array(
                "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
            );
            $numero_mes = date("m", strtotime($elemento['fecha_creacion_venta']));
            $mes = $meses[intval($numero_mes) - 1];
            array_push($arraymes, $mes);
            switch ($mes) {
                case 'Enero':
                    $Enero++;
                    break;
                case 'Febrero':
                    $Febrero++;
                    break;
                case 'Marzo':
                    $Marzo++;
                    break;
                case 'Abril':
                    $Abril++;
                    break;
                case 'Mayo':
                    $Mayo++;
                    break;
                case 'Junio':
                    $Junio++;
                    break;
                case 'Julio':
                    $Julio++;
                    break;
                case 'Agosto':
                    $Agosto++;
                    break;
                case 'Septiembre':
                    $Septiembre++;
                    break;
                case 'Octubre':
                    $Octubre++;
                    break;
                case 'Noviembre':
                    $Noviembre++;
                    break;
                case 'Diciembre':
                    $Diciembre++;
                    break;
            }
        }
        $cantidadmes = [
            "Enero" => $Enero,
            "Febrero" => $Febrero,
            "Marzo" => $Marzo,
            "Abril" => $Abril,
            "Mayo" => $Mayo,
            "Junio" => $Junio,
            "Julio" => $Julio,
            "Agosto" => $Agosto,
            "Septiembre" => $Septiembre,
            "Octubre" => $Octubre,
            "Noviembre" => $Noviembre,
            "Diciembre" => $Diciembre,
        ];
        $mesAsignado = array();
        foreach ($cantidadmes as $elemento) {
            if ($elemento != 0) {
                array_push($mesAsignado, $elemento);
            }
        }
        $mesesAtendido = array_unique($arraymes);
        $mesEscogido = array();
        foreach ($mesesAtendido as $elemento) {
            array_push($mesEscogido, $elemento);
        }
        $respuesta = array(
            "mes" => $mesEscogido,
            "cantidadmes" => $mesAsignado
        );
        echo json_encode($respuesta);
    }

    public function ReportePDFNotaVenta()
    {
        $inventario = $this->LogicaNotaVenta($_POST);
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFin = $_POST['FechaFin'];
        ob_start();
        require_once 'generar-pdf/pdf/detalleproducto.php';
        $html = ob_get_clean();
        ////
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        // Render the HTML as PDF
        //GUARDAMOS EL DPF
        $dompdf->render();
        $fecha = date("Y-m-d");
        // Output the generated PDF to Browser
        echo  $dompdf->stream("ReporteNotaVenta_$fecha.pdf", array("Attachment" => 0));
    }
    public function RerpoteNotaVendidos()
    {

        $inventario = $this->LogicaNotaVenta($_POST);
        echo json_encode($inventario);
    }

    public function LogicaNotaVenta($request)
    {
        // var_dump($request);die;

        if (isset($request['FechaFin'])) {
            $fechaInicio = $request['fechaInicio'];
            $fechaFin = $request['FechaFin'];
            $nota_venta_detalle = notaventadetalle::join("producto", "producto.id_producto", "=", "nota_venta_detalle.id_producto")
                ->join("tipo_inventario", "tipo_inventario.id_tipo_inventario", "=", "producto.id_tipo_inventario")
                ->select('producto.id_producto', 'producto.codigo_producto', 'tipo_inventario.glosa_tipo_inventario', 'producto.glosa_producto', 'producto.id_tipo_inventario', 'nota_venta_detalle.valor_venta', 'nota_venta_detalle.cantidad_venta_detalle');
            if (!empty($request['fechaInicio'])) {
                $nota_venta_detalle = $nota_venta_detalle->whereRaw("(nota_venta_detalle.fechacreacion_venta_detalle >= ? AND nota_venta_detalle.fechacreacion_venta_detalle <= ?)", [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
            } else {
                $nota_venta_detalle = $nota_venta_detalle->Where('nota_venta_detalle.fechacreacion_venta_detalle', "<=", $fechaFin);
            }
            $nota_venta_detalle = $nota_venta_detalle->get()->toArray();
        } else {
            $nota_venta_detalle = notaventa::select("nota_venta.id_apertura_caja", 'nota_venta_detalle.*', 'producto.*', 'tipo_inventario.*')
                ->join('nota_venta_detalle', 'nota_venta_detalle.id_nota_venta', '=', 'nota_venta.id_nota_venta')
                ->join("producto", "producto.id_producto", "=", "nota_venta_detalle.id_producto")
                ->join("tipo_inventario", "tipo_inventario.id_tipo_inventario", "=", "producto.id_tipo_inventario")
                ->where("nota_venta.id_apertura_caja", $request)
                ->get()
                ->toArray();
        }

        $id_producto_agrupado = $this->group_by('id_producto', $nota_venta_detalle);
        $inventario = [];
        foreach ($id_producto_agrupado as $key => $value) {
            $valorventa = 0;
            $cantidadventa = 0;
            foreach ($value as $key => $elemento) {
                $valorventa += $elemento['valor_venta'];
                $cantidadventa += $elemento['cantidad_venta_detalle'];
                $datos = [
                    "id_producto" => $elemento['id_producto'],
                    "codigo_producto" => $elemento['codigo_producto'],
                    "glosa_tipo_inventario" => $elemento['glosa_tipo_inventario'],
                    'glosa_producto' => $elemento['glosa_producto'],
                    "id_tipo_inventario" => $elemento['id_tipo_inventario'],
                    "valor_venta" => $valorventa,
                    "cantidad_venta_detalle" => $cantidadventa
                ];
            }
            array_push($inventario, $datos);
        }
        $inventario = $this->group_by('glosa_tipo_inventario', $inventario);
        return $inventario;
    }
}
