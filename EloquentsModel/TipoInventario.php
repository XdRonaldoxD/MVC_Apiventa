<?php
use Illuminate\Database\Eloquent\Model;
class TipoInventario extends Model
{
    protected $table = "tipo_inventario";
    protected $primaryKey = 'id_tipo_inventario';
    protected $fillable = [
        'id_tipo_inventario',
        'glosa_tipo_inventario',
        'ventaproducto_tipo_inventario',
        'orden_tipo_inventario',
        'vigente_tipo_inventario'
    ];
    public $timestamps = false;
}
