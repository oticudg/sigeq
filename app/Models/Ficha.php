<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ModelsTrait;

class Ficha extends Model
{
    use ModelsTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'birthdate',
        'num_id',
        'date_insumo',
        'num_id_c',
        'num_history',
        'phone',
        'movil',
        'phone_c',
        'doctor',
        'last_name_c',
        'name_c',
        'relation_c',
        'operation',
        'address',
        'diagnosis',
        // 'observation',
        'email',
        'pathology',
        'sex',
        'classification_id',
        'asic',
        'intervention_id',
        'surgical_procedure',
        'parish_id',
        'weight',
        'parish',
        'fr',
        'imc',
        'pa',
        'size',
        'speciality_id',
        'type_patient',
        'date_check_pre',
        'date_interconsultas',
        'date_valoration_pre',
        'cirugia_emergencia', // CIRUGIA DE EMERGENCIA (E)
        'apto', // Apto para cirugía
        'consulta_pre_anestesia_id',
        'observation_pre_operatorio',
        'observation_interconsultas',
        'observation_riesgo_anestesico',
    ];
    
    /**
     * Los atributos que deberían estar ocultos para las matrices.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function classification()
    {
        return $this->belongsTo(Classification::class, 'classification_id');
    }

    public function type_patient_()
    {
        return $this->belongsTo(TypePatient::class, 'type_patient');
    }

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    public function speciality()
    {
        return $this->belongsTo(Specialty::class, 'speciality_id');
    }

    public function parish_()
    {
        return $this->belongsTo(Parish::class, 'parish_id');
    }

    public function asic_()
    {
        return $this->belongsTo(Asic::class, 'asic');
    }

    public function insumos()
    {
        return $this->hasMany(Ficha_insumo::class);
    }

}
