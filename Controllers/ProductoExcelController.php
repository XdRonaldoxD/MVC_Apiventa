<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

require_once "EloquentsModel/producto.php";
require_once "EloquentsModel/Proveedor.php";
require_once "EloquentsModel/Marcas.php";
require_once "EloquentsModel/TipoConcentracion.php";
require_once "EloquentsModel/TipoInventario.php";
require_once "EloquentsModel/TipoProductos.php";
require_once "EloquentsModel/Unidad.php";
require_once "EloquentsModel/ProductoHistorial.php";


class ProductoExcelController
{

    public function EnviarArchivoProducto()
    {

        $Excel = $_FILES['archivo'];
        $guardado = $_FILES['archivo']['tmp_name'];
        $nombreArchivo =  $Excel['name'];
        $nombreArchivos = pathinfo($nombreArchivo, PATHINFO_FILENAME);
        $path = "archivos/ImportarExcelProducto";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $nombre_excel = $nombreArchivos . time() . '.' .  pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        move_uploaded_file($guardado, $path . '/' . $nombre_excel);
        $ruta = $path . "/" . $nombre_excel;
        $documento = IOFactory::load($ruta);
        // print_r($cantidad);
        $MascotaExiste = array();
        $respuesta = "";
        $ProductoNoRegistrado = array();
        $validandoExcel = array();
        $datosexistente = array();
        $validarExcel = false;
        //saber la cantidad de hojas
        $totalDeHojas  =  $documento->getSheetCount();
        // for ($i = 0; $i < $totalDeHojas; $i++) {
        $hojaActual = $documento->getSheet(0);
        # Iterar filas
        try {
            foreach ($hojaActual->getRowIterator() as $key => $fila) {
                if ($key >= 3) {
                    $elemento = array();
                    foreach ($fila->getCellIterator() as $i => $celda) {
                        # El valor, así como está en el documento
                        $valorRaw = trim($celda->getValue());
                        array_push($elemento, $valorRaw);
                    }
                    $tipo_producto = null;
                    if (isset($elemento[0])) {
                        if (!empty($elemento[0])) {
                            $tipo_producto =  $elemento[0];
                        }
                    }

                    $unidad = null;
                    if (isset($elemento[1])) {
                        if (!empty($elemento[1])) {
                            $unidad =  $elemento[1];
                        }
                    }
                    $tipo_concentracion = null;
                    if (isset($elemento[2])) {
                        if (!empty($elemento[2])) {
                            $tipo_concentracion =  $elemento[2];
                        }
                    }

                    $tipo_inventario = null;
                    if (isset($elemento[3])) {
                        if (!empty($elemento[3])) {
                            $tipo_inventario =  $elemento[3];
                        }
                    }
                    $cantidad_producto = null;
                    if ($elemento[11] !== "") {
                        $cantidad_producto =  $elemento[11];
                    } else if ($elemento[11] === 0) {
                        $cantidad_producto = 0;
                    }
                    $nombre_producto = null;
                    if (!empty($elemento[6])) {
                        $nombre_producto = $elemento[6];
                    }
                    $codigo_producto_insertar = null;
                    if (isset($elemento[7])) {
                        if (!empty($elemento[7])) {
                            $codigo_producto_insertar = trim($elemento[7]);
                        }
                    }
                    $precio_venta = null;
                    if ($elemento[12] !== "") {
                        $precio_venta = $elemento[12];
                    } else if ($elemento[12] === 0) {
                        $precio_venta = 0;
                    }
                    if (
                        $tipo_producto === null || $tipo_concentracion === null || $tipo_inventario === null || $unidad === null
                        || $nombre_producto === null  || $cantidad_producto === null || $precio_venta === null
                    ) {
                        $fila = $key;
                        $datosnull = array(
                            "Tipo Producto" => $tipo_producto,
                            "Tipo Concentración" => $tipo_concentracion,
                            "Tipo Inventario" => $tipo_inventario,
                            "Unidad" => $unidad,
                            "Nombre Producto" => $nombre_producto,
                            "Cantidad Producto" => $cantidad_producto,
                            "Precio Venta" => $precio_venta
                        );
                        $columnas = "";
                        foreach ($datosnull as $key => $element) {
                            if ($element === null) {
                                $columnas .= $key . ',';
                            }
                        }
                        $columnas = substr($columnas, 0, -1);

                        //arregloColumna
                        $arreglocolumna = array(
                            "fila" => "Fila del Excel " . $fila,
                            "columna" => "$columnas~$nombre_producto~$nombre_producto~$codigo_producto_insertar",
                            "comentario" => "Campo vacio"
                        );
                        array_push($validandoExcel, $arreglocolumna);
                        $validarExcel = true;
                    }
                }
            }
        } catch (\Throwable $e) {
            $respuesta = $e->getMessage();
        }

        if ($validarExcel !== true) {
            try {
                foreach ($hojaActual->getRowIterator() as $key => $fila) {
                    if ($key >= 3) {
                        $elemento = array();
                        //Obtenemos los datos de la fila y lo guardamos de el arreglo
                        foreach ($fila->getCellIterator() as $i => $celda) {
                            # El valor, así como está en el documento
                            $valorRaw = trim($celda->getValue());
                            array_push($elemento, $valorRaw);
                        }
                        $tipo_producto_insertar = null;
                        if (isset($elemento[0])) {
                            if (!empty($elemento[0])) {
                                $tipo_producto_insertar =  trim($elemento[0]);
                            }
                        }
                        $unidad_insertar = null;
                        if (isset($elemento[1])) {
                            if (!empty($elemento[1])) {
                                $unidad_insertar = trim($elemento[1]);
                            }
                        }
                        $tipo_concentracion_insertar = null;
                        if (isset($elemento[2])) {
                            if (!empty($elemento[2])) {
                                $tipo_concentracion_insertar = trim($elemento[2]);
                            }
                        }
                        $tipo_inventario_insertar = null;
                        if (isset($elemento[3])) {
                            if (!empty($elemento[3])) {
                                $tipo_inventario_insertar = trim($elemento[3]);
                            }
                        }
                        $marca_insertar = null;
                        if (isset($elemento[4])) {
                            if (!empty($elemento[4])) {
                                $marca_insertar =  trim($elemento[4]);
                            }
                        }
                        $nombre_proveedor_insertar = null;
                        if (isset($elemento[5])) {
                            if (!empty($elemento[5])) {
                                $nombre_proveedor_insertar =  trim($elemento[5]);
                            }
                        }
                        $nombre_producto_insertar = null;
                        if (isset($elemento[6])) {
                            if (!empty($elemento[6])) {
                                $nombre_producto_insertar = trim($elemento[6]);
                            }
                        }
                        $codigo_producto_insertar = null;
                        if (isset($elemento[7])) {
                            if (!empty($elemento[7])) {
                                $codigo_producto_insertar = trim($elemento[7]);
                            }
                        }
                        $multidosis_insertar = null;
                        if (isset($elemento[8])) {
                            if (!empty($elemento[8])) {
                                $multidosis_insertar =  trim($elemento[8]);
                            }
                        }
                        $dosis_insertar = null;
                        if (isset($elemento[9])) {
                            if (!empty($elemento[9])) {
                                $dosis_insertar =  trim($elemento[9]);
                            }
                        }

                        $concentracion_insertar = null;
                        if (isset($elemento[10])) {
                            if (!empty($elemento[10])) {
                                $concentracion_insertar =  trim($elemento[10]);
                            }
                        }

                        $cantidad_producto_insertar = null;
                        if (isset($elemento[11])) {
                            $cantidad_producto_insertar = trim($elemento[11]);
                        }
                        $precio_venta_insertar = null;
                        if (isset($elemento[12])) {
                            if (!empty($elemento[12])) {
                                $precio_venta_insertar = trim($elemento[12]);
                            }
                        }

                        $precio_costo_insertar = null;
                        if (isset($elemento[13])) {
                            if (!empty($elemento[13])) {
                                $precio_costo_insertar = trim($elemento[13]);
                            }
                        }
                        if ($_POST['tipo_accion'] == "CREAR") {
                            $Traendo_productos = Producto::where('codigo_producto', $codigo_producto_insertar)->first();
                            if (isset($Traendo_productos)) {
                                $elementos = [
                                    "fila" => "Fila del Excel " . $key,
                                    "columna" => "Nombre Producto,Codigo Producto~$nombre_producto_insertar~$codigo_producto_insertar",
                                    "comentario" => "Producto  $codigo_producto_insertar,existente Verificar."
                                ];
                                array_push($datosexistente, $elementos);
                            } else {
                                $tipoProducto = TipoProductos::where('glosa_tipo_producto', 'LIKE', "%$tipo_producto_insertar%")->first();
                                if (isset($tipoProducto)) {
                                    $id_tipo_producto = $tipoProducto['id_tipo_producto'];
                                } else {
                                    $id_tipo_producto = $tipo_producto->Registrar();
                                    TipoProductos::create([
                                        'glosa_tipo_producto' => $tipo_producto_insertar,
                                        'vigente_tipo_producto' => 1
                                    ]);
                                }
                                //CREA EL TIPO DE UNIDAD
                                $id_unidad = null;
                                $unidad = Unidad::where('glosa_unidad', 'like', "%$unidad_insertar%")->first();
                                if (isset($unidad)) {
                                    $id_unidad = $unidad['id_unidad'];
                                } else {
                                    $unidad = Unidad::create([
                                        'glosa_unidad' => $unidad_insertar,
                                        'vigente_unidad' => 1
                                    ]);
                                    $id_unidad = $unidad->id_unidad;
                                }
                                //SE VERIFICA EL TIPO DE CONCENTRACIÓN
                                $id_tipo_concentracion = null;
                                $TipoConcentracion = TipoConcentracion::where('glosa_tipo_concentracion', 'like', "%$tipo_concentracion_insertar%")->first();
                                if (isset($TipoConcentracion)) {
                                    $id_tipo_concentracion = $TipoConcentracion->id_tipo_concentracion;
                                } else {
                                    $TipoConcentracion = TipoConcentracion::create([
                                        'id_unidad' => $id_unidad,
                                        'glosa_tipo_concentracion' => $tipo_concentracion_insertar,
                                        'vigente_tipo_concentracion' => 1,
                                    ]);
                                    $id_tipo_concentracion = $TipoConcentracion->id_tipo_concentracion;
                                }
                                $id_tipo_inventario = null;
                                $TipoInventario = TipoInventario::where('glosa_tipo_inventario', 'like', "%$tipo_inventario_insertar%")->first();
                                if ($TipoInventario) {
                                    $id_tipo_inventario = $TipoInventario->id_tipo_inventario;
                                } else {
                                    $TipoInventario = TipoInventario::create([
                                        'glosa_tipo_inventario' => $tipo_inventario_insertar,
                                        'vigente_tipo_inventario' => 1
                                    ]);
                                    $id_tipo_inventario = $TipoInventario->id_tipo_inventario;
                                }

                                $id_marca = null;
                                $marca = Marcas::where('glosa_marca', 'like', "%$marca_insertar%")->first();
                                if (isset($marca)) {
                                    $id_marca = $marca->id_marca;
                                } else {
                                    $marca = Marcas::create([
                                        'glosa_marca' => $marca_insertar,
                                        'vigente_marca' => 1,
                                    ]);
                                    $id_marca = $marca->id_marca;
                                }
                                $id_proveedor = null;
                                $Proveedor = Proveedor::where('glosa_proveedor', 'like', "%$nombre_proveedor_insertar%")->first();
                                if ($Proveedor) {
                                    $id_proveedor = $Proveedor->id_proveedor;
                                } else {
                                    $Proveedor = Proveedor::create([
                                        'comentario_proveedor' => "Migrado desde Excel",
                                        'vigente_proveedor' => 1
                                    ]);
                                    $id_proveedor = $Proveedor->id_proveedor;
                                }

                                $elemento = [
                                    'id_tipo_producto' => $id_tipo_producto,
                                    'id_tipo_concentracion' => $id_tipo_concentracion,
                                    'id_tipo_inventario' => $id_tipo_inventario,
                                    'id_unidad' => $id_unidad,
                                    'id_marca' => $id_marca,
                                    'id_proveedor' => $id_proveedor,
                                    'codigo_producto' => $codigo_producto_insertar,
                                    'glosa_producto' => $nombre_producto_insertar,
                                    'multidosis_producto' => $multidosis_insertar,
                                    'dosis_producto' => $dosis_insertar,
                                    'concentracion_producto' => $concentracion_insertar,
                                    'cantidad_producto' => $cantidad_producto_insertar,
                                    'stock_producto' => $cantidad_producto_insertar,
                                    'precioventa_producto' => $precio_venta_insertar,
                                    'preciocosto_producto' => $precio_costo_insertar,
                                    'vigente_producto' => 1,
                                    "fechacreacion_producto" => date('Y-m-d H:i:s')
                                ];
                                $Producto = Producto::create($elemento);
                                $dataHistorial=[
                                    'id_usuario'=>$_POST['id_usuario'],
                                    'id_tipo_movimiento'=>1,
                                    'id_producto'=>$Producto->id_producto,
                                    'cantidadmovimiento_producto_historial'=>$cantidad_producto_insertar,
                                    'fecha_producto_historial'=>date('Y-m-d H:i:s'),
                                    'comentario_producto_historial'=>"Migrado desde el excel.",
                                    'preciocompra_producto_historial'=>$precio_costo_insertar
                                ];
                                ProductoHistorial::create($dataHistorial);
                            }
                        } else {
                            $Traendo_productos = Producto::where('codigo_producto', $codigo_producto_insertar)->first();
                            if (isset($Traendo_productos)) {
                                $tipoProducto = TipoProductos::where('glosa_tipo_producto', 'LIKE', "%$tipo_producto_insertar%")->first();
                                if (isset($tipoProducto)) {
                                    $id_tipo_producto = $tipoProducto['id_tipo_producto'];
                                } else {
                                    $id_tipo_producto = $tipo_producto->Registrar();
                                    TipoProductos::create([
                                        'glosa_tipo_producto' => $tipo_producto_insertar,
                                        'vigente_tipo_producto' => 1
                                    ]);
                                }
                                //CREA EL TIPO DE UNIDAD
                                $id_unidad = null;
                                $unidad = Unidad::where('glosa_unidad', 'like', "%$unidad_insertar%")->first();
                                if (isset($unidad)) {
                                    $id_unidad = $unidad['id_unidad'];
                                } else {
                                    $unidad = Unidad::create([
                                        'glosa_unidad' => $unidad_insertar,
                                        'vigente_unidad' => 1
                                    ]);
                                    $id_unidad = $unidad->id_unidad;
                                }
                                //SE VERIFICA EL TIPO DE CONCENTRACIÓN
                                $id_tipo_concentracion = null;
                                $TipoConcentracion = TipoConcentracion::where('glosa_tipo_concentracion', 'like', "%$tipo_concentracion_insertar%")->first();
                                if (isset($TipoConcentracion)) {
                                    $id_tipo_concentracion = $TipoConcentracion->id_tipo_concentracion;
                                } else {
                                    $TipoConcentracion = TipoConcentracion::create([
                                        'id_unidad' => $id_unidad,
                                        'glosa_tipo_concentracion' => $tipo_concentracion_insertar,
                                        'vigente_tipo_concentracion' => 1,
                                    ]);
                                    $id_tipo_concentracion = $TipoConcentracion->id_tipo_concentracion;
                                }
                                $id_tipo_inventario = null;
                                $TipoInventario = TipoInventario::where('glosa_tipo_inventario', 'like', "%$tipo_inventario_insertar%")->first();
                                if ($TipoInventario) {
                                    $id_tipo_inventario = $TipoInventario->id_tipo_inventario;
                                } else {
                                    $TipoInventario = TipoInventario::create([
                                        'glosa_tipo_inventario' => $tipo_inventario_insertar,
                                        'vigente_tipo_inventario' => 1
                                    ]);
                                    $id_tipo_inventario = $TipoInventario->id_tipo_inventario;
                                }

                                $id_marca = null;
                                $marca = Marcas::where('glosa_marca', 'like', "%$marca_insertar%")->first();
                                if (isset($marca)) {
                                    $id_marca = $marca->id_marca;
                                } else {
                                    $marca = Marcas::create([
                                        'glosa_marca' => $marca_insertar,
                                        'vigente_marca' => 1,
                                    ]);
                                    $id_marca = $marca->id_marca;
                                }
                                $id_proveedor = null;
                                $Proveedor = Proveedor::where('glosa_proveedor', 'like', "%$nombre_proveedor_insertar%")->first();
                                if ($Proveedor) {
                                    $id_proveedor = $Proveedor->id_proveedor;
                                } else {
                                    $Proveedor = Proveedor::create([
                                        'comentario_proveedor' => "Migrado desde Excel",
                                        'vigente_proveedor' => 1
                                    ]);
                                    $id_proveedor = $Proveedor->id_proveedor;
                                }

                                $elemento = [
                                    'id_tipo_producto' => $id_tipo_producto,
                                    'id_tipo_concentracion' => $id_tipo_concentracion,
                                    'id_tipo_inventario' => $id_tipo_inventario,
                                    'id_unidad' => $id_unidad,
                                    'id_marca' => $id_marca,
                                    'id_proveedor' => $id_proveedor,
                                    // 'codigo_producto' => $codigo_producto_insertar,
                                    'glosa_producto' => $nombre_producto_insertar,
                                    'multidosis_producto' => $multidosis_insertar,
                                    'dosis_producto' => $dosis_insertar,
                                    'concentracion_producto' => $concentracion_insertar,
                                    'cantidad_producto' => $cantidad_producto_insertar,
                                    'stock_producto' => $cantidad_producto_insertar,
                                    'precioventa_producto' => $precio_venta_insertar,
                                    'preciocosto_producto' => $precio_costo_insertar,
                                    'vigente_producto' => 1,
                                ];
                                Producto::where('id_producto', $Traendo_productos->id_producto)->update($elemento);
                                $dataHistorial=[
                                    'id_usuario'=>$_POST['id_usuario'],
                                    'id_tipo_movimiento'=>3,
                                    'id_producto'=>$Traendo_productos->id_producto,
                                    'cantidadmovimiento_producto_historial'=>$cantidad_producto_insertar,
                                    'fecha_producto_historial'=>date('Y-m-d H:i:s'),
                                    'comentario_producto_historial'=>"Migrado desde el excel.",
                                    'preciocompra_producto_historial'=>$precio_costo_insertar
                                ];
                                ProductoHistorial::create($dataHistorial);
                            } else {
                                $elementos = [
                                    "fila" => "Fila del Excel " . $key,
                                    "columna" => "Nombre Producto,Codigo Producto~$nombre_producto_insertar~$codigo_producto_insertar",
                                    "comentario" => "Producto  $codigo_producto_insertar,no existe Verificar."
                                ];
                                array_push($datosexistente, $elementos);
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                $respuesta = $e->getMessage();
            }
        }

        $retornando = [
            "respuesta" => $respuesta,
            "respuesta_producto_registrado" => $ProductoNoRegistrado,
            "validandoExcel" => $validandoExcel,
            "datosexistente" => $datosexistente
        ];
        unlink('archivos/ImportarExcelProducto/' . $nombre_excel);
        echo  json_encode($retornando);
    }
}
