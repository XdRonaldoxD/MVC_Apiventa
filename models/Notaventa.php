<?php


class Notaventa
{


    private $db;
    public function __construct()
    {
        $this->db = database::conectar();
    }

    private $id_nota_venta;
    private $id_usuario;
    private $id_apertura_caja;
    private $numero_venta;
    private $valor_venta;
    private $descuento_negocio_venta;
    private $porcentajedescuento_venta;
    private $pagocliente_venta;
    private $cambiocliente_venta;
    private $cierre_venta;
    private $fecha_creacion_venta;
    private $hora_creacion_venta;
    private $path_nota_venta;
    private $path_nota_venta_pdf;
    
    public function getId_nota_venta()
    {
        return $this->id_nota_venta;
    }


    public function setId_nota_venta($id_nota_venta)
    {
        $this->id_nota_venta = $id_nota_venta;

        return $this;
    }


    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }


    public function getId_apertura_caja()
    {
        return $this->id_apertura_caja;
    }

    public function setId_apertura_caja($id_apertura_caja)
    {
        $this->id_apertura_caja = $id_apertura_caja;

        return $this;
    }

    public function getNumero_venta()
    {
        return $this->numero_venta;
    }

    public function setNumero_venta($numero_venta)
    {
        $this->numero_venta = $numero_venta;

        return $this;
    }

    public function getValor_venta()
    {
        return $this->valor_venta;
    }


    public function setValor_venta($valor_venta)
    {
        $this->valor_venta = $valor_venta;

        return $this;
    }


    public function getDescuento_negocio_venta()
    {
        return $this->descuento_negocio_venta;
    }


    public function setDescuento_negocio_venta($descuento_negocio_venta)
    {
        $this->descuento_negocio_venta = $descuento_negocio_venta;

        return $this;
    }


    public function getPorcentajedescuento_venta()
    {
        return $this->porcentajedescuento_venta;
    }


    public function setPorcentajedescuento_venta($porcentajedescuento_venta)
    {
        $this->porcentajedescuento_venta = $porcentajedescuento_venta;

        return $this;
    }


    public function getPagocliente_venta()
    {
        return $this->pagocliente_venta;
    }


    public function setPagocliente_venta($pagocliente_venta)
    {
        $this->pagocliente_venta = $pagocliente_venta;

        return $this;
    }


    public function getCambiocliente_venta()
    {
        return $this->cambiocliente_venta;
    }


    public function setCambiocliente_venta($cambiocliente_venta)
    {
        $this->cambiocliente_venta = $cambiocliente_venta;

        return $this;
    }


    public function getCierre_venta()
    {
        return $this->cierre_venta;
    }

    public function setCierre_venta($cierre_venta)
    {
        $this->cierre_venta = $cierre_venta;

        return $this;
    }


    public function getFecha_creacion_venta()
    {
        return $this->fecha_creacion_venta;
    }


    public function setFecha_creacion_venta($fecha_creacion_venta)
    {
        $this->fecha_creacion_venta = $fecha_creacion_venta;

        return $this;
    }

    public function getHora_creacion_venta()
    {
        return $this->hora_creacion_venta;
    }


    public function setHora_creacion_venta($hora_creacion_venta)
    {
        $this->hora_creacion_venta = $hora_creacion_venta;

        return $this;
    }


    public function getPath_nota_venta()
    {
        return $this->path_nota_venta;
    }

    public function setPath_nota_venta($path_nota_venta)
    {
        $this->path_nota_venta = $path_nota_venta;

        return $this;
    }

    public function getpath_nota_venta_pdf()
    {
        return $this->path_nota_venta_pdf;
    }

    public function setpath_nota_venta_pdf($path_nota_venta_pdf)
    {
        $this->path_nota_venta_pdf = $path_nota_venta_pdf;

        return $this;
    }

    public function ListarProductoVenta($id_productos)
    {
        if (count($id_productos) > 0) {
            $not_in = '';
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
                where producto.vigente_producto=1 and  producto.stock_producto>0
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



    public function Traer_ultimo_registro()
    {
        $sql = "SELECT * from nota_venta order by id_nota_venta DESC LIMIT 1";
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetch(PDO::FETCH_ASSOC);
        $sentencia = null;
    }

    public function CrearVenta()
    {
        $datos = array(
            $this->getId_usuario(), $this->getId_apertura_caja(), $this->getNumero_venta(), $this->getValor_venta(), $this->getDescuento_negocio_venta(),
            $this->getPagocliente_venta(), $this->getCambiocliente_venta(), $this->getFecha_creacion_venta(), $this->getHora_creacion_venta()
        );
      
        $consulta = "INSERT INTO nota_venta(id_usuario,id_apertura_caja,numero_venta,valor_venta,descuento_negocio_venta,
        pagocliente_venta,cambiocliente_venta,fecha_creacion_venta,hora_creacion_venta)
        values(?,?,?,?,?,?,?,?,?)";
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

    public function actualizarPath()
    {
        $datos = array($this->getPath_nota_venta(), $this->getId_nota_venta());
        $consulta = "UPDATE nota_venta SET path_nota_venta=? where id_nota_venta=?";
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
