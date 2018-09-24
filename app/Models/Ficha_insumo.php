<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ModelsTrait;

class Ficha_insumo extends Model
{
	use ModelsTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'insumo_id',
        'ficha_id',
        'cant',
        'origen',
        'state',
        'observation',
    ];

    /**
     * Los atributos que deberían estar ocultos para las matrices.
     *
     * @var array
     */
    protected $hidden = [
    	'created_at', 'updated_at', 'deleted_at'
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }
}
