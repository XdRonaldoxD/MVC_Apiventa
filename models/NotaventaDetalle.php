<?php


class NotaventaDetalle
{


    private $db;
    public function __construct()
    {
        $this->db = database::conectar();
    }

    private $id_nota_venta_detalle;
    private $id_nota_venta;
    private $id_producto;
    private $fechacreacion_venta_detalle;
    private $valor_venta;
    private $valor_venta_producto;
    private $cantidad_venta_detalle;
    private $porcentadescuento_negocio;
    private $orden_venta_detalle;





    /**
     * Get the value of id_nota_venta_detalle
     */ 
    public function getId_nota_venta_detalle()
    {
        return $this->id_nota_venta_detalle;
    }

    /**
     * Set the value of id_nota_venta_detalle
     *
     * @return  self
     */ 
    public function setId_nota_venta_detalle($id_nota_venta_detalle)
    {
        $this->id_nota_venta_detalle = $id_nota_venta_detalle;

        return $this;
    }

    /**
     * Get the value of id_nota_venta
     */ 
    public function getId_nota_venta()
    {
        return $this->id_nota_venta;
    }

    /**
     * Set the value of id_nota_venta
     *
     * @return  self
     */ 
    public function setId_nota_venta($id_nota_venta)
    {
        $this->id_nota_venta = $id_nota_venta;

        return $this;
    }

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
     * Get the value of fechacreacion_venta_detalle
     */ 
    public function getFechacreacion_venta_detalle()
    {
        return $this->fechacreacion_venta_detalle;
    }

    /**
     * Set the value of fechacreacion_venta_detalle
     *
     * @return  self
     */ 
    public function setFechacreacion_venta_detalle($fechacreacion_venta_detalle)
    {
        $this->fechacreacion_venta_detalle = $fechacreacion_venta_detalle;

        return $this;
    }

    /**
     * Get the value of valor_venta
     */ 
    public function getValor_venta()
    {
        return $this->valor_venta;
    }

    /**
     * Set the value of valor_venta
     *
     * @return  self
     */ 
    public function setValor_venta($valor_venta)
    {
        $this->valor_venta = $valor_venta;

        return $this;
    }

    /**
     * Get the value of valor_venta_producto
     */ 
    public function getValor_venta_producto()
    {
        return $this->valor_venta_producto;
    }

    /**
     * Set the value of valor_venta_producto
     *
     * @return  self
     */ 
    public function setValor_venta_producto($valor_venta_producto)
    {
        $this->valor_venta_producto = $valor_venta_producto;

        return $this;
    }

    /**
     * Get the value of cantidad_venta_detalle
     */ 
    public function getCantidad_venta_detalle()
    {
        return $this->cantidad_venta_detalle;
    }

    /**
     * Set the value of cantidad_venta_detalle
     *
     * @return  self
     */ 
    public function setCantidad_venta_detalle($cantidad_venta_detalle)
    {
        $this->cantidad_venta_detalle = $cantidad_venta_detalle;

        return $this;
    }

    /**
     * Get the value of porcentadescuento_negocio
     */ 
    public function getPorcentadescuento_negocio()
    {
        return $this->porcentadescuento_negocio;
    }

    /**
     * Set the value of porcentadescuento_negocio
     *
     * @return  self
     */ 
    public function setPorcentadescuento_negocio($porcentadescuento_negocio)
    {
        $this->porcentadescuento_negocio = $porcentadescuento_negocio;

        return $this;
    }

    /**
     * Get the value of orden_venta_detalle
     */ 
    public function getOrden_venta_detalle()
    {
        return $this->orden_venta_detalle;
    }

    /**
     * Set the value of orden_venta_detalle
     *
     * @return  self
     */ 
    public function setOrden_venta_detalle($orden_venta_detalle)
    {
        $this->orden_venta_detalle = $orden_venta_detalle;

        return $this;
    }

    public function ListarProductoVenta($id_productos)
    {
        if (count($id_productos) > 0) {
            $not_in='';
            foreach ($id_productos as $key => $value) {
                $not_in .= $value . ',';
            }
            $not_in = rtrim($not_in, ",");
            $not_ins = "($not_in)";
            $consulta = " and producto.id_producto NOT IN $not_ins";
        } else {
            $consulta = '';
        }
        $sql = "SELECT * FROM producto left join tipo_producto  on producto.id_tipo_producto=tipo_producto.id_tipo_producto
                left join tipo_inventario  on tipo_inventario.id_tipo_inventario=producto.id_tipo_inventario
                left join marca   on marca.id_marca=producto.id_marca
                where producto.vigente_producto=1 and  producto.stock_producto!=0
                $consulta ";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        $sentencia = null;
    }

    public function Traer_Nota_Venta()
    {
        $sql = "SELECT * FROM nota_venta where id_nota_venta=?";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->getId_nota_venta()));
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }

    public function Traer_Nota_Venta_Detalle()
    {
        $sql = "SELECT * FROM nota_venta_detalle inner join producto ON producto.id_producto=nota_venta_detalle.id_producto where id_nota_venta=?";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->getId_nota_venta()));
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        $sentencia = null;
    }

    public function Traer_ultimo_registro()
    {
        $sql = "SELECT top 1 * from nota_venta order by id desc";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute(array($this->getId_nota_venta()));
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }

    public function CrearVentaDetalle()
    {
        $datos = array($this->getId_nota_venta(), $this->getId_producto(), $this->getFechacreacion_venta_detalle(), $this->getValor_venta(), $this->getCantidad_venta_detalle());
        $consulta = "INSERT INTO nota_venta_detalle(id_nota_venta,id_producto,fechacreacion_venta_detalle,valor_venta,cantidad_venta_detalle) values(?,?,?,?,?)";
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





}
