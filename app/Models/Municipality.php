<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ModelsTrait;

class Municipality extends Model
{
    use ModelsTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name', 'state_id', 'description'
    ];

    /**
     * Los atributos que deberían estar ocultos para las matrices.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
