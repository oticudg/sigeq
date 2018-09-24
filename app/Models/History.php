<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ModelsTrait;

class History extends Model
{
    use ModelsTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'state',
    	'cirujano',
    	'anestesiologo',
    	'sala_pabellon',
    	'cirugia_date',
    	'causa_id',
    	'observation',
    	'state_date',
    	'ficha_id',
    ];

    /**
     * Los atributos que deberían estar ocultos para las matrices.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];
}
