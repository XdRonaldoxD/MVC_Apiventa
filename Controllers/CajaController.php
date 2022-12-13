<?php

use Dompdf\Dompdf;

require_once "EloquentsModel/Caja.php";
require_once "EloquentsModel/aperturacaja.php";
require_once "EloquentsModel/notaventadetalle.php";
require_once "EloquentsModel/notaventa.php";
require_once "EloquentsModel/Negocio.php";
require_once "EloquentsModel/ConsultaGlobal.php";
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
        $consulta="SELECT id_nota_venta,null as id_factura,null as id_boleta,numero_venta,null as numero_boleta,null as numero_factura,fecha_creacion_venta,nombre_usuario,apellido_usuario
        from nota_venta 
        INNER JOIN usuario USING (id_usuario)
        where fecha_creacion_venta BETWEEN '{$_POST['fechaInicio']} 00:00:00' and '{$_POST['fechafin']}  23:59:59'
        UNION all
        select null as id_nota_venta, factura.id_factura, boleta.id_boleta, null as numero_venta, boleta.numero_boleta, factura.numero_factura, negocio.fechacreacion_negocio as fecha_creacion_venta, usuario.nombre_usuario, usuario.apellido_usuario
        FROM negocio
        INNER JOIN usuario USING (id_usuario)
        LEFT JOIN boleta USING (id_negocio)
        LEFT JOIN factura USING (id_negocio)
        where fechacreacion_negocio BETWEEN '{$_POST['fechaInicio']} 00:00:00' and '{$_POST['fechafin']}  23:59:59'
        ORDER BY fecha_creacion_venta DESC";
        $NegociosVentaDetalle = (new ConsultaGlobal())->ConsultaGlobal($consulta);
        $ventadetalle = array();
        foreach ($NegociosVentaDetalle as $elemento) {
            $documento = '';
            $tipo_documento = '';
            $id_tipo_documento = null;
            if ($elemento->id_nota_venta) {
                $documento = 'Nota De Venta N°' . $elemento->numero_venta;
                $tipo_documento = 'NOTA VENTA';
                $id_tipo_documento = $elemento->id_nota_venta;
            } else if ($elemento->id_boleta) {
                $documento = 'Boleta Electronica N°' . $elemento->numero_boleta;
                $tipo_documento = 'BOLETA';
                $id_tipo_documento = $elemento->id_boleta;
            } else if ($elemento->id_factura) {
                $documento = 'Factura Electroncia N°' . $elemento->numero_factura;
                $tipo_documento = 'FACTURA';
                $id_tipo_documento = $elemento->id_factura;
            }
            $datos = array(
                "documento" => $documento,
                "tipo_documento" => $tipo_documento,
                "id_tipo_documento" => $id_tipo_documento,
                "numero_venta" => $elemento->numero_venta,
                "nombre_usuario" => $elemento->nombre_usuario,
                "apellido_usuario" => $elemento->apellido_usuario,
                "fecha_creacion_venta" => date("d/m/Y", strtotime($elemento->fecha_creacion_venta)),
                "hora_creacion_venta" => date('g:i A', strtotime($elemento->fecha_creacion_venta))
            );
            array_push($ventadetalle, $datos);
        }
        echo json_encode($ventadetalle);
    }

    public function TraerNotaVenta()
    {
        $notaventa = notaventa::where("id_nota_venta", $_POST['id_nota_venta'])->first();
        $pathticket = $notaventa->path_nota_venta;
        $pathtoFile = RUTA_ARCHIVO."/archivos/TicketVenta/$pathticket";
        echo json_encode($pathtoFile);
    
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
        $agrupar_datos = [];
        if (isset($request['FechaFin'])) {
            $fechaInicio = $request['fechaInicio'];
            $fechaFin = $request['FechaFin'];
            $consulta_globa = "SELECT nota_venta_detalle.valor_venta,nota_venta_detalle.cantidad_venta_detalle,
            producto.id_producto,codigo_producto,glosa_tipo_inventario,glosa_producto,
            producto.id_tipo_inventario
            from apertura_caja  INNER JOIN nota_venta USING (id_apertura_caja)
            INNER JOIN nota_venta_detalle USING (id_nota_venta)
            INNER JOIN producto USING (id_producto)
            inner join tipo_inventario USING (id_tipo_inventario)
            WHERE nota_venta_detalle.fechacreacion_venta_detalle BETWEEN '$fechaInicio 00:00:00' AND '$fechaFin 23:59:59'
            UNION ALL
            select negocio_detalle.total_negocio_detalle as valor_venta,negocio_detalle.cantidad_negocio_detalle as cantidad_venta_detalle,producto.id_producto,codigo_producto,glosa_tipo_inventario,glosa_producto,
            producto.id_tipo_inventario FROM apertura_caja INNER JOIN negocio USING (id_apertura_caja)
            INNER JOIN negocio_detalle USING (id_negocio)
            INNER JOIN producto USING (id_producto)
            inner join tipo_inventario USING (id_tipo_inventario)
            WHERE negocio_detalle.fechacreacion_negocio_detalle BETWEEN '$fechaInicio 00:00:00' AND '$fechaFin 23:59:59' ";
            $nota_venta_detalle = (new ConsultaGlobal())->ConsultaGlobal($consulta_globa);
        } else {
            $consulta_globa = "SELECT nota_venta_detalle.valor_venta,nota_venta_detalle.cantidad_venta_detalle,
            producto.id_producto,codigo_producto,glosa_tipo_inventario,glosa_producto,
            producto.id_tipo_inventario
            from apertura_caja  INNER JOIN nota_venta USING (id_apertura_caja)
            INNER JOIN nota_venta_detalle USING (id_nota_venta)
            INNER JOIN producto USING (id_producto)
            inner join tipo_inventario USING (id_tipo_inventario)
            where apertura_caja.id_apertura_caja=$request
            UNION ALL
            select negocio_detalle.total_negocio_detalle as valor_venta,negocio_detalle.cantidad_negocio_detalle as cantidad_venta_detalle,producto.id_producto,codigo_producto,glosa_tipo_inventario,glosa_producto,
            producto.id_tipo_inventario FROM apertura_caja INNER JOIN negocio USING (id_apertura_caja)
            INNER JOIN negocio_detalle USING (id_negocio)
            INNER JOIN producto USING (id_producto)
            inner join tipo_inventario USING (id_tipo_inventario)
            where apertura_caja.id_apertura_caja=$request";
            $nota_venta_detalle = (new ConsultaGlobal())->ConsultaGlobal($consulta_globa);
        }
        foreach ($nota_venta_detalle as $key => $value) {
            $element = [
                "id_producto" => $value->id_producto,
                "codigo_producto" => $value->codigo_producto,
                "glosa_tipo_inventario" => $value->glosa_tipo_inventario,
                'glosa_producto' => $value->glosa_producto,
                "id_tipo_inventario" => $value->id_tipo_inventario,
                "valor_venta" => $value->valor_venta,
                "cantidad_venta_detalle" =>  $value->cantidad_venta_detalle,
            ];
            array_push($agrupar_datos, $element);
        }
        $inventario = [];
        $id_producto_agrupado = $this->group_by('id_producto', $agrupar_datos);
        // $id_producto_agrupado_negocio = $this->group_by('id_producto', $negocio_detalle);
        // echo json_encode($id_producto_agrupado_negocio);
        // die();
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
