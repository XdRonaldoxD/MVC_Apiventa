<?php
use Illuminate\Database\Eloquent\Model;
class Marcas extends Model
{
    protected $table = "marca";
    protected $primaryKey = 'id_marca';
    protected $fillable = [
        'id_marca',
        'glosa_marca',
        'vigente_marca',
    ];
    public $timestamps = false;
}
