<?php

use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once "models/Producto.php";
require_once "models/Marca.php";
require_once "models/Unidad.php";
require_once "models/Inventario.php";
require_once "models/TipoProducto.php";
require_once "models/Concentracion.php";
require_once "models/Proveedor.php";
// require_once "EloquentsModel/producto.php";


class ProductoController
{

    public function ListarProducto()
    {
        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);
        $productos = new Producto();
        $productos->setVigente_producto(1);
        if (!empty($DatosPost->codigo_filtro)) {
            $productos->setCodigo_producto($DatosPost->codigo_filtro);
        }
        if (!empty($DatosPost->nombre_produc_filtro)) {
            $productos->setGlosa_producto($DatosPost->nombre_produc_filtro);
        }
        if (!empty($DatosPost->producto_inventario_filtro)) {
            $productos->setId_tipo_inventario($DatosPost->producto_inventario_filtro);
        }
        if (!empty($DatosPost->producto_tipo_filtro)) {
            $productos->setId_tipo_producto($DatosPost->producto_tipo_filtro);
        }
        $todos = $productos->All();
        echo json_encode($todos);
    }
    public function ListarProductoDesactivados()
    {
        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);
        $productos = new Producto();
        $productos->setVigente_producto(0);
        if (!empty($DatosPost->codigo_filtro)) {
            $productos->setCodigo_producto(helpers::validar_input($DatosPost->codigo_filtro));
        }
        if (!empty($DatosPost->nombre_produc_filtro)) {
            $productos->setGlosa_producto(helpers::validar_input($DatosPost->nombre_produc_filtro));
        }
        if (!empty($DatosPost->producto_inventario_filtro)) {
            $productos->setId_tipo_inventario(helpers::validar_input($DatosPost->producto_inventario_filtro));
        }
        if (!empty($DatosPost->producto_tipo_filtro)) {
            $productos->setId_tipo_producto(helpers::validar_input($DatosPost->producto_tipo_filtro));
        }
        $todos = $productos->All();
        echo json_encode($todos);
    }

    public function Guardar_Editar_Producto()
    {
        $request = file_get_contents("php://input");
        $request = json_decode($request);
        $productos = new Producto();
        if (!isset($request->id_producto)) {
            $productos->setId_tipo_producto($request->id_tipo_producto);
            $productos->setId_tipo_concentracion($request->id_tipo_concentracion);
            $productos->setId_tipo_inventario($request->id_tipo_inventario);
            $productos->setId_unidad($request->id_unidad);
            $productos->setId_marca($request->id_marca);
            $productos->setId_proveedor($request->id_proveedor);
            $productos->setCodigo_producto($request->codigo_producto);
            $productos->setGlosa_producto($request->glosa_producto);
            $productos->setDetalle_producto($request->detalle_producto);
            $productos->setMultidosis_producto($request->multidosis_producto);
            $productos->setDosis_producto($request->dosis_producto);
            $productos->setConcentracion_producto($request->concentracion_producto);
            $productos->setStock_producto($request->stock_producto);
            $productos->setPrecioventa_producto($request->precioventa_producto);
            $productos->setPreciocosto_producto($request->preciocosto_producto);
            $productos->setFechacreacion_producto(date('Y-m-d H:i:s'));
            $productos->setContenidomultidosis_producto($request->contenidomultidosis_producto);
            $productos->setVigente_producto(1);
            $productos->CrearProducto();
            $respuesta = "$request->glosa_producto Creado";
        } else {
            $productos->setId_tipo_producto($request->id_tipo_producto);
            $productos->setId_tipo_concentracion($request->id_tipo_concentracion);
            $productos->setId_tipo_inventario($request->id_tipo_inventario);
            $productos->setId_unidad($request->id_unidad);
            $productos->setId_marca($request->id_marca);
            $productos->setId_proveedor($request->id_proveedor);
            $productos->setCodigo_producto($request->codigo_producto);
            $productos->setGlosa_producto($request->glosa_producto);
            $productos->setDetalle_producto($request->detalle_producto);
            $productos->setMultidosis_producto($request->multidosis_producto);
            $productos->setDosis_producto($request->dosis_producto);
            $productos->setConcentracion_producto($request->concentracion_producto);
            $productos->setStock_producto($request->stock_producto);
            $productos->setPrecioventa_producto($request->precioventa_producto);
            $productos->setPreciocosto_producto($request->preciocosto_producto);
            $productos->setId_producto($request->id_producto);
            $productos->ActualizarProducto();
            $respuesta = "$request->glosa_producto Editado";
        }
        echo json_encode($respuesta);
    }

    public function TraerProducto()
    {
        $request = file_get_contents("php://input");
        $request = json_decode($request);
        $Producto = new producto();
        $Producto->setId_producto($request);
        $datos = $Producto->TraerProducto();
        echo json_encode($datos);
    }
    public function Listar_Producto_Proveedor()
    {
        $request = file_get_contents("php://input");
        $proveedor_id = json_decode($request);
        $Producto = new producto();
        $Producto->setId_proveedor($proveedor_id);
        $proveedor = $Producto->Listar_Producto_Proveedor();
        $proveedor_id = $Producto->Traer_Producto_Proveedor($proveedor_id);
        if (in_array($proveedor_id, $proveedor)) {
            $proveedor = $proveedor;
        } else {
            array_push($proveedor, $proveedor_id);
        }
        echo json_encode($proveedor);
    }

    public function Listar_Producto_Marca()
    {
        $request = file_get_contents("php://input");
        $marca_id = json_decode($request);
        $marcas = new Marca();
        $marca = $marcas->All("marca", 1);
        $marcas->setId_marca($marca_id);
        $marca_id = $marcas->Traer_Marca('id_marca');
        if (in_array($marca_id, $marca)) {
            $marca = $marca;
        } else {
            array_push($marca, $marca_id);
        }
        echo json_encode($marca);
    }

    public  function ListarTipoConcentracion()
    {
        $request = file_get_contents("php://input");
        $request = json_decode($request);
        $unidad = new Unidad();
        $unidad->setId_unidad($request->id_unidad);
        $datos = $unidad->ListarTipoConcentracion();
        echo json_encode($datos);
    }
    public  function ListarTipoProducto()
    {
        $unidad = new Producto();
        $datos = $unidad->ListarTipoProducto();
        echo json_encode($datos);
    }

    public function Listar_Producto_Tipo_Producto()
    {
        $request = file_get_contents("php://input");
        $request = json_decode($request);
        $Inventario = new Inventario();
        $todos = $Inventario->All("tipo_inventario", 1);
        $Inventario->setId_tipo_inventario($request->id_tipo_inventario);
        $dato = $Inventario->Traer_Inventario('id_tipo_inventario');
        if (in_array($dato, $todos)) {
            $todos = $todos;
        } else {
            array_push($todos, $dato);
        }
        echo json_encode($todos);
    }

    public function Listar_Producto_unidad()
    {
        $request = file_get_contents("php://input");
        $request = json_decode($request);
        $Inventario = new Unidad();
        $todos = $Inventario->All("unidad", 1);
        $Inventario->setId_unidad($request->id_unidad);
        $dato = $Inventario->Traer_Unidad('id_unidad');
        if (in_array($dato, $todos)) {
            $todos = $todos;
        } else {
            array_push($todos, $dato);
        }
        echo json_encode($todos);
    }

    public function Listar_Producto_concentracion()
    {
        $request = file_get_contents("php://input");
        $request = json_decode($request);
        $Inventario = new Unidad();
        $Inventario->setId_unidad($request->id_unidad);
        $todos = $Inventario->ListarTipoConcentracion();

        echo json_encode($todos);
    }

    public function ExportarDatos()
    {
        $fechacreacion = date('Y-m-d');
        $spread = new Spreadsheet();
        $sheet = $spread->getActiveSheet();
        $gdImage = imagecreatefrompng('http://localhost/MVC_APIVENTA/archivos/imagenes/ahorro_farma.png');
        // $textColor = imagecolorallocate($gdImage, 255, 255, 5);
        // imagestring($gdImage, 1, 7, 5, date("F Y"), $textColor);

        //  Add the In-Memory image to a worksheet
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing();
        $drawing->setName('In-Memory image 1');
        $drawing->setDescription('In-Memory image 1');
        $drawing->setCoordinates('A1');
        $drawing->setImageResource($gdImage);
        $drawing->setRenderingFunction(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::RENDERING_JPEG);
        $drawing->setMimeType(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_DEFAULT);
        $drawing->setWidth(80);
        $drawing->setHeight(20);
        $drawing->setWorksheet($spread->getActiveSheet());

        $sheet->getStyle('A2:N2')
            ->getFont()->getColor()->setARGB('000000');
        $sheet->getStyle('A2:N2')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFDBE2F1',
                ],
            ],
        ]);
        $sheet->getStyle('A1:N1')
            ->getFont()->getColor()->setARGB('000000');
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFDBE2F1',
                ],
            ],
            'alignment' => [
                'horizontal' => 'center', // Centro horizontal
                'vertical' => 'center', // Centro vertical
            ],
        ]);


        $encabezado = ["Tipo Producto (Obligatorio)
      ", "Unidad (Obligatorio)
      ", "Tipo Concentración (Obligatorio)", "Tipo Inventario (Obligatorio)
      ", "Marca
      ", "Nombre del Laboratorio
      ", "Nombre del Producto
      ", "Codigo del Producto
      ", "Multidosis
      ", "Dosis
      ", "Concentración
      ", "Cantidad (Obligatorio)
      ", "Precio Venta (Obligatorio)
      ", "Precio Costo
      "];
        # El último argumento es por defecto A1
        $sheet->fromArray($encabezado, null, 'A2');
        $sheet->fromArray(["INVENTARIO DEL PRODUCTO"], null, 'A1');

        //SE UNE LAS CELDAS
        $sheet->mergeCells('A1:N1');
        foreach (range('A', 'N') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $productos = new Producto();
        $productos->setVigente_producto(1);
        $todos = $productos->All();

        // TRAEMOS TODOS LOS TIPO DE INVENTARIO
        $Inventario = new Inventario();
        $unidades = new Unidad();
        $tipo_inventario = $Inventario->All("tipo_inventario", 1);
        $TipoProducto = $productos->ListarTipoProducto();
        $unidad = $unidades->All('unidad', 1);
        $marca = (new Marca)->All('marca', 1);

        foreach ($todos as $key => $elemento) {
            $sheet->setCellValueByColumnAndRow(1, $key + 3, $elemento['glosa_tipo_producto']);
            $sheet->setCellValueByColumnAndRow(2, $key + 3, $elemento['glosa_unidad']);
            $sheet->setCellValueByColumnAndRow(3, $key + 3, $elemento['glosa_tipo_concentracion']);
            $sheet->setCellValueByColumnAndRow(4, $key + 3, $elemento['glosa_tipo_inventario']);
            $sheet->setCellValueByColumnAndRow(5, $key + 3, $elemento['glosa_marca']);
            $sheet->setCellValueByColumnAndRow(6, $key + 3, $elemento['glosa_proveedor']);
            $sheet->setCellValueByColumnAndRow(7, $key + 3, $elemento['glosa_producto']);
            $sheet->setCellValueByColumnAndRow(8, $key + 3, $elemento['codigo_producto']);
            $sheet->setCellValueByColumnAndRow(9, $key + 3, ($elemento['multidosis_producto'] == 'null')  ? null : $elemento['multidosis_producto']);
            $sheet->setCellValueByColumnAndRow(10, $key + 3, $elemento['dosis_producto']);
            $sheet->setCellValueByColumnAndRow(11, $key + 3, $elemento['concentracion_producto']);
            $sheet->setCellValueByColumnAndRow(12, $key + 3, $elemento['stock_producto']);
            $sheet->setCellValueByColumnAndRow(13, $key + 3, $elemento['precioventa_producto']);
            $sheet->setCellValueByColumnAndRow(14, $key + 3, $elemento['preciocosto_producto']);

            //ASIGANMOS A CADA CELDA EL SELECT multiple
            $validation = $sheet->getCell('A' . ($key + 3))->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setFormula1('=\'Segunda_Hoja\'!$A$1:$A$' . count($TipoProducto));
            $validation->setAllowBlank(false);
            $validation->setShowDropDown(true);
            $validation->setShowInputMessage(true);
            $validation->setPromptTitle('Nota');
            $validation->setPrompt('Debe seleccionar uno de las opciones desplegables.');
            $validation->setShowErrorMessage(true);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setErrorTitle('Opción Invalida.');
            $validation->setError('Seleccione uno de la lista desplegable.');

            //ASIGANMOS A CADA CELDA EL SELECT multiple
            $validation = $sheet->getCell('B' . ($key + 3))->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setFormula1('=\'Segunda_Hoja\'!$B$1:$B$' . count($unidad));
            $validation->setAllowBlank(false);
            $validation->setShowDropDown(true);
            $validation->setShowInputMessage(true);
            $validation->setPromptTitle('Nota');
            $validation->setPrompt('Debe seleccionar uno de las opciones desplegables.');
            $validation->setShowErrorMessage(true);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setErrorTitle('Opción Invalida.');
            $validation->setError('Seleccione uno de la lista desplegable.');

            $validation = $sheet->getCell('D' . ($key + 3))->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setFormula1('=\'Segunda_Hoja\'!$D$1:$D$' . count($tipo_inventario));
            $validation->setAllowBlank(false);
            $validation->setShowDropDown(true);
            $validation->setShowInputMessage(true);
            $validation->setPromptTitle('Nota');
            $validation->setPrompt('Debe seleccionar uno de las opciones desplegables.');
            $validation->setShowErrorMessage(true);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setErrorTitle('Opción Invalida.');
            $validation->setError('Seleccione uno de la lista desplegable.');

            $validation = $sheet->getCell('E' . ($key + 3))->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setFormula1('=\'Segunda_Hoja\'!$E$1:$E$' . count($marca));
            $validation->setAllowBlank(false);
            $validation->setShowDropDown(true);
            $validation->setShowInputMessage(true);
            $validation->setPromptTitle('Nota');
            $validation->setPrompt('Debe seleccionar uno de las opciones desplegables.');
            $validation->setShowErrorMessage(true);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setErrorTitle('Opción Invalida.');
            $validation->setError('Seleccione uno de la lista desplegable.');
        }
        $sheet->setTitle("Productos Almacenado");
        //FINALIZAR LA PRIMERA HOJA DEL EXCEL
        //CREAMOS LA SEGUNDA HOJAS NO SE PONE EL ACTIVE PARA INICIARLZAR LA HOJA DE TRABAJO
        $objPHPExcel = $spread->createSheet();
        //OCULTAMOS LA SEGUNDA HOJA
        $objPHPExcel = $objPHPExcel->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
        foreach ($TipoProducto as $key => $elemento) {
            //Otra manera de pintar de pinta la celda con setcellvalue
            $objPHPExcel->setCellValue('A' . ($key + 1), $elemento['glosa_tipo_producto']);
        }
        foreach ($unidad as $key => $elemento) {
            $objPHPExcel->setCellValue('B' . ($key + 1), $elemento['glosa_unidad']);
        }
        foreach ($tipo_inventario as $key => $elemento) {
            $objPHPExcel->setCellValue('D' . ($key + 1), $elemento['glosa_tipo_inventario']);
        }

        foreach ($marca as $key => $elemento) {
            $objPHPExcel->setCellValue('E' . ($key + 1), $elemento['glosa_marca']);
        }
        $objPHPExcel->setTitle('Segunda_Hoja');
        $fileName = "Inventario_excel$fechacreacion.xlsx";
        # Crear un "escritor"
        $writer = new Xlsx($spread);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');
    }

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
                            "columna" => $columnas,
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
        // var_dump($validarExcel);
        // die;
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
                        $tipo_producto = new TipoProducto();
                        $tipo_producto->setGlosa_tipo_producto($tipo_producto_insertar);
                        $tipoProducto = $tipo_producto->Traer_glosa_Tipo_Producto();
                        if (isset($tipoProducto)) {
                            $id_tipo_producto = $tipoProducto['id_tipo_producto'];
                        } else {
                            $ultimo = $tipo_producto->Traer_Ultimo_Producto();
                            $tipo_producto->setGlosa_tipo_producto($tipo_producto_insertar);
                            $tipo_producto->setOrden_tipo_producto($ultimo['orden_tipo_producto']++);
                            $tipo_producto->setVigente_tipo_producto(1);
                            $id_tipo_producto = $tipo_producto->Registrar();
                        }
                        //CREA EL TIPO DE UNIDAD
                        $id_unidad = null;
                        $tablaunidad = new Unidad();
                        $tablaunidad->setGlosa_unidad($unidad_insertar);
                        $unidad = $tablaunidad->Traer_Unidad('glosa_unidad');
                        if (isset($unidad)) {
                            $id_unidad = $unidad['id_unidad'];
                        } else {
                            // $tablaunidadnueva = new Unidad();
                            $tablaunidad = $tablaunidad->Traer_Ultimo_Unidad();
                            // $tablaunidadnueva->setGlosa_unidad($unidad_insertar);
                            $tablaunidad->setOrder_unidad($tablaunidad['order_unidad']++);
                            $tablaunidad->setVigente_unidad(1);
                            $unidad = $tablaunidad->Registrar();
                            $id_unidad = $unidad;
                        }
                        //SE VERIFICA EL TIPO DE CONCENTRACIÓN
                        $id_tipo_concentracion = null;
                        $concentracion = new  concentracion();
                        $concentracion->setGlosa_tipo_concentracion($tipo_concentracion_insertar);
                        $datos_concentracion = $concentracion->TraerTipoConcentracion();
                        if (isset($datos)) {
                            $id_tipo_concentracion = $datos_concentracion['id_tipo_concentracion'];
                        } else {
                            // $concentracion_nueva = new  concentracion();
                            $concentracion->setId_unidad($id_unidad);
                            // $concentracion_nueva->setGlosa_tipo_concentracion($tipo_concentracion_insertar);
                            $concentracion->setVigente_tipo_concentracion(1);
                            $id_tipo_concentracion = $concentracion->Registrar();
                        }

                        $id_tipo_inventario = null;
                        $Inventario = new Inventario();
                        $Inventario->setGlosa_tipo_inventario($tipo_inventario_insertar);
                        $tablainventario =  $Inventario->Traer_Inventario('glosa_tipo_inventario');
                        if ($tablainventario) {
                            $id_tipo_inventario = $tablainventario->id_tipo_inventario;
                        } else {
                            // $Inventario_crear = new Inventario();
                            // $Inventario_crear->setGlosa_tipo_inventario($tipo_inventario_insertar);
                            $Inventario->setVigente_tipo_inventario(1);
                            $id_tipo_inventario = $Inventario->Registrar();
                        }

                        $id_marca = null;
                        $marca = new Marca();
                        $marca->set_glosa_marca($marca_insertar);
                        $datos_marca = $marca->Traer_Marca('glosa_marca');
                        if ($datos_marca) {
                            $id_marca = $datos_marca['id_marca'];
                        } else {
                            $marca->set_vigente_marca(1);
                            $id_marca = $marca->Registrar();
                        }
                        $id_proveedor = null;
                        $Proveedor = new Proveedor();
                        $Proveedor->setGlosa_proveedor($nombre_proveedor_insertar);
                        $datos_proveedor = $Proveedor->Traer_Proveedor('glosa_proveedor');
                        if ($datos_proveedor) {
                            $id_proveedor = $datos_proveedor['id_proveedor'];
                        } else {
                            $Proveedor->setVigente_proveedor(1);
                            $id_proveedor = $Proveedor->Registrar();
                        }
                        $productos =new producto();
                        $productos->setGlosa_producto($nombre_producto_insertar);
                        $productos->setCodigo_producto($codigo_producto_insertar);
                        $Traendo_productos=$productos->ConsultaMigrarProducto();
                        if (isset($Traendo_productos)) {
                            $productos->setId_tipo_producto($id_tipo_producto);
                            $productos->setId_tipo_concentracion($id_tipo_concentracion);
                            $productos->setId_tipo_inventario($id_tipo_inventario);
                            $productos->setId_unidad($id_unidad);
                            $productos->setId_marca($id_marca);
                            $productos->setId_proveedor($id_proveedor);
                            $productos->setCodigo_producto($codigo_producto_insertar);
                            $productos->setCodigo_producto($codigo_producto_insertar);
                            $productos->setGlosa_producto($nombre_producto_insertar);
                            $productos->setMultidosis_producto($multidosis_insertar);
                            $productos->setDosis_producto($dosis_insertar);
                            $productos->setConcentracion_producto($concentracion_insertar);
                            $productos->setCantidad_producto($cantidad_producto_insertar);
                            $productos->setstock_producto($cantidad_producto_insertar);
                            $productos->setstock_producto($cantidad_producto_insertar);
                            $productos->setprecioventa_producto($precio_venta_insertar);
                            $productos->setpreciocosto_producto($precio_costo_insertar);
                            $productos->setid_producto($Traendo_productos['id_producto']);
                            $productos->ActualizarProducto();
                        } else {
                            //CREA EL TIPO PRODUCTO
                            $productos->setId_tipo_producto($id_tipo_producto);
                            $productos->setId_tipo_concentracion($id_tipo_concentracion);
                            $productos->setId_tipo_inventario($id_tipo_inventario);
                            $productos->setId_unidad($id_unidad);
                            $productos->setId_marca($id_marca);
                            $productos->setId_proveedor($id_proveedor);
                            $productos->setCodigo_producto($codigo_producto_insertar);
                            $productos->setCodigo_producto($codigo_producto_insertar);
                            $productos->setGlosa_producto($nombre_producto_insertar);
                            $productos->setMultidosis_producto($multidosis_insertar);
                            $productos->setDosis_producto($dosis_insertar);
                            $productos->setConcentracion_producto($concentracion_insertar);
                            $productos->setCantidad_producto($cantidad_producto_insertar);
                            $productos->setstock_producto($cantidad_producto_insertar);
                            $productos->setstock_producto($cantidad_producto_insertar);
                            $productos->setprecioventa_producto($precio_venta_insertar);
                            $productos->setpreciocosto_producto($precio_costo_insertar);
                            $productos->CrearProducto();
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

    public function TraerHistorialProducto(){
        $DatosHistorial = (new Producto)->ProductoHistorial($_POST['id_producto']);
        echo json_encode($DatosHistorial);
    
    }

    public function GuardarHistorial()
    {
        $datos = array(
            'id_usuario' => $_POST['id_usuario'],
            'tipo_movimiento' => $_POST['tipo_movimiento'],
            'id_producto' => $_POST['id_producto'],
            'cantidadmovimiento_producto_historial' =>$_POST['movimiento_historial'],
            'fecha_producto_historial' => date('Y-m-d H:i:s'),
            'comentario_producto_historial' => ($_POST['comentario_producto_historial'] == 'undefined') ? '' :  $_POST['comentario_producto_historial'],
        );
        $historial = (new Producto)->CrearHistorialProducto($datos);
        $datoProducto = array(
            "stock_producto" => $_POST['stock_final_producto'],
            "fechacreacion_producto" => date('Y-m-d H:i:s'),
        );
        if ($_POST['preciocosto_producto'] !== "undefined") {
            $datoProducto += array(
                "preciocosto_producto" => $_POST['preciocosto_producto']
            );
        }
         (new Producto)->ActualizarStockProducto($datoProducto, $_POST['id_producto']);
        if ($historial) {
            $respuesta = "creado";
        } else {
            $respuesta = "error";
        }
        echo json_encode($respuesta);
    }



}
