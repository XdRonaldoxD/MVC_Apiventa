<?php

use Illuminate\Database\Eloquent\Model;
class inventarioproducto extends Model
{
    protected $table = "inventario_producto";
    protected $primaryKey = 'id_tipo_inventario';
    protected $fillable = [
        'id_inventario',
        'id_usuario',
        'path_inventario',
        'fecha_creacion_inventario',
        'fecha_inventario'
    ];
    public $timestamps = false;
}
