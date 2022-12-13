<?php


class Producto
{

    private $db;
    public function __construct()
    {
        $this->db = database::conectar();
    }

    private $id_producto;
    private $id_tipo_producto;
    private $id_tipo_concentracion;
    private $id_tipo_inventario;
    private $id_unidad;
    private $id_marca;
    private $id_proveedor;
    private $codigo_producto;
    private $glosa_producto;
    private $detalle_producto;
    private $multidosis_producto;
    private $dosis_producto;
    private $concentracion_producto;
    private $cantidad_producto;
    private $stock_producto;
    private $precioventa_producto;
    private $preciocosto_producto;

    private $fechacreacion_producto;
    private $saldocantidad_producto;
    private $contenidomultidosis_producto;
    private $vigente_producto;



    /**
     * Get the value of id_producto
     */
    public function getId_producto()
    {
        return $this->id_producto;
    }

    /**
     * Set the value of id_producto
     *
     * @return  self
     */
    public function setId_producto($id_producto)
    {
        $this->id_producto = $id_producto;

        return $this;
    }

    /**
     * Get the value of id_tipo_producto
     */
    public function getId_tipo_producto()
    {
        return $this->id_tipo_producto;
    }

    /**
     * Set the value of id_tipo_producto
     *
     * @return  self
     */
    public function setId_tipo_producto($id_tipo_producto)
    {
        $this->id_tipo_producto = $id_tipo_producto;

        return $this;
    }

    /**
     * Get the value of id_tipo_concentracion
     */
    public function getId_tipo_concentracion()
    {
        return $this->id_tipo_concentracion;
    }

    /**
     * Set the value of id_tipo_concentracion
     *
     * @return  self
     */
    public function setId_tipo_concentracion($id_tipo_concentracion)
    {
        $this->id_tipo_concentracion = $id_tipo_concentracion;

        return $this;
    }

    /**
     * Get the value of id_tipo_inventario
     */
    public function getId_tipo_inventario()
    {
        return $this->id_tipo_inventario;
    }

    /**
     * Set the value of id_tipo_inventario
     *
     * @return  self
     */
    public function setId_tipo_inventario($id_tipo_inventario)
    {
        $this->id_tipo_inventario = $id_tipo_inventario;

        return $this;
    }

    /**
     * Get the value of id_unidad
     */
    public function getId_unidad()
    {
        return $this->id_unidad;
    }

    /**
     * Set the value of id_unidad
     *
     * @return  self
     */
    public function setId_unidad($id_unidad)
    {
        $this->id_unidad = $id_unidad;

        return $this;
    }

    /**
     * Get the value of id_marca
     */
    public function getId_marca()
    {
        return $this->id_marca;
    }

    /**
     * Set the value of id_marca
     *
     * @return  self
     */
    public function setId_marca($id_marca)
    {
        $this->id_marca = $id_marca;

        return $this;
    }

    /**
     * Get the value of id_proveedor
     */
    public function getId_proveedor()
    {
        return $this->id_proveedor;
    }

    /**
     * Set the value of id_proveedor
     *
     * @return  self
     */
    public function setId_proveedor($id_proveedor)
    {
        $this->id_proveedor = ($id_proveedor);

        return $this;
    }

    /**
     * Get the value of codigo_producto
     */
    public function getCodigo_producto()
    {
        return $this->codigo_producto;
    }

    /**
     * Set the value of codigo_producto
     *
     * @return  self
     */
    public function setCodigo_producto($codigo_producto)
    {
        $this->codigo_producto = $codigo_producto;

        return $this;
    }

    /**
     * Get the value of glosa_producto
     */
    public function getGlosa_producto()
    {
        return $this->glosa_producto;
    }

    /**
     * Set the value of glosa_producto
     *
     * @return  self
     */
    public function setGlosa_producto($glosa_producto)
    {
        $this->glosa_producto = $glosa_producto;

        return $this;
    }

    /**
     * Get the value of detalle_producto
     */
    public function getDetalle_producto()
    {
        return $this->detalle_producto;
    }


    public function setDetalle_producto($detalle_producto)
    {
        $this->detalle_producto = $detalle_producto;

        return $this;
    }


    public function getMultidosis_producto()
    {
        return $this->multidosis_producto;
    }


    public function setMultidosis_producto($multidosis_producto)
    {
        $this->multidosis_producto = ($multidosis_producto);

        return $this;
    }


    public function getDosis_producto()
    {
        return $this->dosis_producto;
    }


    public function setDosis_producto($dosis_producto)
    {
        $this->dosis_producto = ($dosis_producto);

        return $this;
    }


    public function getConcentracion_producto()
    {
        return $this->concentracion_producto;
    }


    public function setConcentracion_producto($concentracion_producto)
    {
        $this->concentracion_producto = ($concentracion_producto);

        return $this;
    }


    public function getCantidad_producto()
    {
        return $this->cantidad_producto;
    }


    public function setCantidad_producto($cantidad_producto)
    {
        $this->cantidad_producto = ($cantidad_producto);

        return $this;
    }


    public function getStock_producto()
    {
        return $this->stock_producto;
    }


    public function setStock_producto($stock_producto)
    {
        $this->stock_producto = ($stock_producto);

        return $this;
    }


    public function getPrecioventa_producto()
    {
        return $this->precioventa_producto;
    }


    public function setPrecioventa_producto($precioventa_producto)
    {
        $this->precioventa_producto = ($precioventa_producto);

        return $this;
    }


    public function getFechacreacion_producto()
    {
        return $this->fechacreacion_producto;
    }


    public function setFechacreacion_producto($fechacreacion_producto)
    {
        $this->fechacreacion_producto = ($fechacreacion_producto);

        return $this;
    }

    public function getSaldocantidad_producto()
    {
        return $this->saldocantidad_producto;
    }


    public function setSaldocantidad_producto($saldocantidad_producto)
    {
        $this->saldocantidad_producto = ($saldocantidad_producto);

        return $this;
    }


    public function getContenidomultidosis_producto()
    {
        return $this->contenidomultidosis_producto;
    }


    public function setContenidomultidosis_producto($contenidomultidosis_producto)
    {
        $this->contenidomultidosis_producto = ($contenidomultidosis_producto);

        return $this;
    }

    public function getVigente_producto()
    {
        return $this->vigente_producto;
    }
    public function setVigente_producto($vigente_producto)
    {
        $this->vigente_producto = ($vigente_producto);

        return $this;
    }

    public function getPreciocosto_producto()
    {
        return $this->preciocosto_producto;
    }


    public function setPreciocosto_producto($preciocosto_producto)
    {
        $this->preciocosto_producto = ($preciocosto_producto);

        return $this;
    }

    public function All()
    {
        $consulta = "SELECT * FROM producto inner join tipo_producto using(id_tipo_producto)
        left join tipo_concentracion using(id_tipo_concentracion)
        left join tipo_inventario using(id_tipo_inventario)
        left join unidad on unidad.id_unidad=producto.id_unidad
        left join proveedor using(id_proveedor)
        left join marca using(id_marca)";
        if (isset($this->vigente_producto) || isset($this->codigo_producto) || isset($this->glosa_producto) ||  isset($this->id_tipo_inventario) || isset($this->id_tipo_producto)) {
            $consulta .= " WHERE ";
            $consulta .= " producto.vigente_producto = $this->vigente_producto and";
            if (isset($this->codigo_producto)) {
                $consulta .= " producto.codigo_producto = $this->codigo_producto and";
            }
            if (isset($this->glosa_producto)) {
                $consulta .= " producto.glosa_producto LIKE '%$this->glosa_producto%' and";
            }
            if (isset($this->id_tipo_inventario)) {
                $consulta .= " producto.id_tipo_inventario = $this->id_tipo_inventario and";
            }
            if (isset($this->id_tipo_producto)) {
                $consulta .= " producto.codigo_producto = $this->id_tipo_producto and";
            }
        }
        $consulta = rtrim($consulta, "and");
        $consulta .= " order by producto.id_producto desc";
        $productos = $this->db->prepare($consulta);
        $productos->execute();
        $result = $productos->fetchAll(PDO::FETCH_ASSOC);
        $productos = null;
        return $result;
    }

    public function CrearProducto()
    {
        $datos = array(
            $this->getId_tipo_producto(), $this->getid_tipo_concentracion(), $this->getid_tipo_inventario(), $this->getid_unidad(), $this->getid_marca(),
            $this->getid_proveedor(), $this->getcodigo_producto(), $this->getglosa_producto(), $this->getdetalle_producto(), $this->getmultidosis_producto(), $this->getdosis_producto(),
            $this->getconcentracion_producto(), $this->getcantidad_producto(), $this->getstock_producto(), $this->getprecioventa_producto(), $this->getpreciocosto_producto(), $this->getfechacreacion_producto(),
            $this->getsaldocantidad_producto(), $this->getcontenidomultidosis_producto(), $this->getvigente_producto()
        );
        $consulta = "INSERT INTO producto(id_tipo_producto,id_tipo_concentracion,id_tipo_inventario,id_unidad,id_marca,
        id_proveedor,codigo_producto,glosa_producto,detalle_producto,multidosis_producto,dosis_producto,concentracion_producto,
        cantidad_producto,stock_producto,precioventa_producto,preciocosto_producto,fechacreacion_producto,saldocantidad_producto,contenidomultidosis_producto,
        vigente_producto)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $sentencia = $this->db->prepare($consulta);
        try {
            $sentencia->execute($datos);
        } catch (Exception $e) {
            throw $e;
        }
        $publisher_id = $this->db->lastInsertId();
        $sentencia->fetch();
        $sentencia = null;
        return $publisher_id;
    }

    public function ActualizarProducto()
    {
        $consulta = "UPDATE producto set id_tipo_producto=?,id_tipo_concentracion=?,id_tipo_inventario=?,id_unidad=?,id_marca=?,
        id_proveedor=?,codigo_producto=?,glosa_producto=?,detalle_producto=?,multidosis_producto=?,dosis_producto=?,concentracion_producto=?,
        cantidad_producto=?,stock_producto=?,precioventa_producto=?,preciocosto_producto=?,saldocantidad_producto=?,contenidomultidosis_producto=? WHERE id_producto=?";
        $datos = array(
            $this->getId_tipo_producto(), $this->getid_tipo_concentracion(), $this->getid_tipo_inventario(), $this->getid_unidad(), $this->getid_marca(),
            $this->getid_proveedor(), $this->getcodigo_producto(), $this->getglosa_producto(), $this->getdetalle_producto(), $this->getmultidosis_producto(), $this->getdosis_producto(),
            $this->getconcentracion_producto(), $this->getcantidad_producto(), $this->getstock_producto(), $this->getprecioventa_producto(), $this->getpreciocosto_producto(),
            $this->getsaldocantidad_producto(), $this->getcontenidomultidosis_producto(), $this->getId_producto()
        );
        $sentencia = $this->db->prepare($consulta);
        try {
            $sentencia->execute($datos);
        } catch (Exception $e) {
            throw $e;
        }
        $publisher_id = $this->db->lastInsertId();
        $sentencia->fetch();
        $sentencia = null;
        return $publisher_id;
    }

    public function ActualizarProductoStock()
    {
        $consulta = "UPDATE producto set stock_producto=? WHERE id_producto=?";
        $datos = array($this->getstock_producto(), $this->getId_producto());
        // print_r($datos);
        // die;
        $sentencia = $this->db->prepare($consulta);
        try {
            $sentencia->execute($datos);
        } catch (Exception $e) {
            throw $e;
        }
        $publisher_id = $this->db->lastInsertId();
        $sentencia->fetch();
        $sentencia = null;
        return $publisher_id;
    }

    public function TraerProducto()
    {
        $sql = "SELECT * FROM producto where id_producto=?";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->id_producto));
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }
    public function ConsultarProducto($columna, $dato_consultar)
    {
        $sql = "SELECT * FROM producto where $columna=$dato_consultar";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }

    public function Listar_Producto_Proveedor()
    {
        $sql = "SELECT * FROM proveedor where vigente_proveedor=1";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        $sentencia = null;
    }
    public function Traer_Producto_Proveedor()
    {
        $sql = "SELECT * FROM proveedor where id_proveedor=?";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->id_proveedor));
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }

    public function ListarTipoProducto()
    {
        $sql = "SELECT * FROM tipo_producto where vigente_tipo_producto=1";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        $sentencia = null;
    }

    public function ConsultaMigrarProducto()
    {
        $sql = "SELECT * FROM producto left join tipo_producto as tp on tp.id_tipo_producto=producto.id_tipo_producto
         left join  tipo_concentracion as tc on tc.id_tipo_concentracion=producto.id_tipo_concentracion
         left join tipo_inventario as ti on ti.id_tipo_inventario=producto.id_tipo_inventario
         left join unidad as u on u.id_unidad=producto.id_unidad
         left join marca as m on m.id_marca=producto.id_marca
         left join proveedor as p on p.id_proveedor=producto.id_proveedor
         where producto.glosa_producto like '%$this->glosa_producto%'
         and producto.codigo_producto like '%$this->codigo_producto%'";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }
    public function ProductoHistorial($id_producto)
    {
        $sql = "SELECT producto.glosa_producto,producto_historial.*,usuario.nombre_usuario,usuario.apellido_usuario
         FROM producto_historial
         inner join producto  on producto.id_producto=producto_historial.id_producto
         inner join  usuario  on usuario.id_usuario=producto_historial.id_usuario
         where producto_historial.id_producto=$id_producto";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        $sentencia = null;
    }
    public function CrearHistorialProducto($datos)
    {
        switch ($_POST['tipo_movimiento']) {
            case 'Añadir':
                $id_tipo_movimiento = 1;
                break;
            case 'Quitar':
                $id_tipo_movimiento = 2;
                break;

            default:
                $id_tipo_movimiento = 3;
                break;
        }
        $sql = "INSERT INTO producto_historial
        (id_usuario,
        id_tipo_movimiento,
        id_producto,
        cantidadmovimiento_producto_historial,
        fecha_producto_historial,
        comentario_producto_historial)
        values(?,?,?,?,?,?)";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array(
            $datos['id_usuario'], $id_tipo_movimiento, $datos['id_producto'], $datos['cantidadmovimiento_producto_historial'],
            $datos['fecha_producto_historial'], $datos['comentario_producto_historial']
        ));
        $sentencia = null;
        return  $this->db->lastInsertId();
    }

    public function ActualizarStockProducto($datos, $id_producto)
    {

        $datos_array = array($datos['stock_producto'], $datos['fechacreacion_producto'], $id_producto);
        if (isset($datos['preciocosto_producto'])) {
            $sql_dato = "preciocosto_producto=?,";
            array_unshift($datos_array, $datos['preciocosto_producto']);
        } else {
            $sql_dato = "";
        }
        $sql = "UPDATE  producto  SET  $sql_dato stock_producto=?, fechacreacion_producto=?   where  id_producto=?";
        $sentencia = $this->db->prepare($sql);
        try {
            $sentencia->execute($datos_array);
        } catch (PDOException $e) {
            $this->db->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        }
        $sentencia = null;
        return  $this->db->lastInsertId();
    }
}
