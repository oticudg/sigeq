<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ { State, Municipality, Parish, TypePatient, Intervention, Classification, Specialty, Asic, Ficha, Subprocedure, Ficha_procedure };

class FichasController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!is_null(request()->date_i) && request()->date_i != 'null') {
            $date_i = \Carbon::parse(request()->date_i);
        } else {$date_i = '2018-01-01';}
        if (!is_null(request()->date_f) && request()->date_f != 'null') {
            $date_f = \Carbon::parse(request()->date_f);
        } else {$date_f = \Carbon::now();}
        $select = ['id', 'name', 'last_name', 'created_at', 'num_history', 'date_insumo','date_check_pre', 'date_interconsultas', 'date_valoration_pre', 'speciality_id', 'operation', 'apto'];
        $ficha = Ficha::orderBy(request()->order?:'id', request()->dir?:'ASC')
        ->search(request()->search)
        ->whereIn('speciality_id', \Auth::user()->specialties->pluck('id')->toArray())
        ->where('speciality_id', ((request()->speciality_id) ? '=' : '!='), request()->speciality_id)
        ->whereBetween('created_at', [$date_i, $date_f])
        ->select($select)
        ->paginate(request()->num?:10);
        $ficha->each(function ($f) {
            if ($f->date_insumo && $f->date_check_pre && $f->date_interconsultas && $f->date_valoration_pre && $f->apto == 'si') {
                $f->operation = '<a href="' . url('/show/' . $f->id) . '">Registrar Fecha</a>';
            }
            $f->speciality_id = $f->speciality->intervention->name[0] . ' - ' . $f->speciality->name;
            $f->name = strtoupper($f->fullName());
            $f->created = $f->created_at->format('d-m-Y');
            unset($f->speciality);
        });
        return $this->dataWithPagination($ficha);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'address' => 'required|string|min:5|max:50',
            'asic' => 'nullable',
            'birthdate' => 'required',
            'email' => 'nullable|email',
            'classification_id' => 'required|numeric',
            'diagnosis' => 'required|string|min:5|max:50',
            'doctor' => 'nullable|string|min:3|max:30',
            'fr' => 'nullable|numeric',
            'imc' => 'nullable|numeric',
            'intervention_id' => 'required|numeric',
            'last_name' => 'required|string|min:3|max:50',
            'last_name_c' => 'nullable|string|min:3|max:50',
            'movil' => 'required|numeric|digits_between:6,14',
            'municipality' => 'required|numeric',
            'name' => 'required|string|min:3|max:50',
            'name_c' => 'nullable|string|min:3|max:50',
            'num_history' => 'required|numeric|digits_between:4,9',
            'num_id' => 'required|numeric|digits_between:6,13',
            'num_id_c' => 'nullable|numeric|digits_between:6,9',
            'pa' => 'nullable|numeric|digits_between:1,2',
            'parish' => 'required|numeric',
            // 'pathology' => 'required|string|max:150|min:3',
            'phone' => 'nullable|numeric|digits_between:6,14',
            'phone_c' => 'nullable|numeric|digits_between:6,14',
            'relation_c' => 'nullable|string',
            'sex' => 'required|boolean',
            'size' => 'nullable|numeric',
            'speciality_id' => 'required|numeric',
            'state' => 'required|numeric',
            'surgical_procedure' => 'required|string',
            'type_patient' => 'required|numeric',
            'weight' => 'nullable|numeric',
        ],[],[
            'address' => 'direccion',
            'asic' => 'asic',
            'birthdate' => 'fecha de nacimiento',
            'classification_id' => 'clasificacion',
            'diagnosis' => 'diagnostico',
            'doctor' => 'doctor',
            'fr' => 'frecuencia cardiaca',
            'imc' => 'indice de masa corporal',
            'intervention_id' => 'invencion',
            'last_name' => 'apellido',
            'last_name_c' => 'apellido de acompañante',
            'movil' => 'movil',
            'municipality' => 'municipio',
            'name' => 'nombre',
            'name_c' => 'nombre de acompañante',
            'num_history' => 'numero de historia',
            'num_id' => 'numero de cedula',
            'num_id_c' => 'numero de cedula acompañante',
            'pa' => 'pulso',
            'parish' => 'parroquia',
            // 'pathology' => 'patologia',
            'phone' => 'telefono fijo',
            'phone_c' => 'telefono de acompañante',
            'relation_c' => 'relacion de acompañante',
            'sex' => 'sexo',
            'size' => 'talla',
            'speciality_id' => 'especialidad',
            'state' => 'estado',
            'surgical_procedure' => 'procedimiento quirurgico',
            'type_patient' => 'tipo de paciente',
            'weight' => 'peso',
        ]);
        $data['birthdate'] = \Carbon::parse(str_replace('/', '-', $request->birthdate))->format('Y-m-d');
        $data['parish_id'] = $data['parish'];

        $ficha = Ficha::create($data);

        $subprocedures = Subprocedure::where('procedure_id', '<', 3)->get(['id', 'name', 'procedure_id']);
        $subprocedures->each(function ($s) use ($ficha) {
            Ficha_procedure::create([
                'name' => $s->name,
                'ficha_id' => $ficha->id,
                'procedure_id' => $s->procedure_id,
                'subprocedure_id' => $s->id,
            ]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ficha = Ficha::findOrFail($id);
        $ficha->birthdate = \Carbon::parse($ficha->birthdate)->format('d/m/Y');
        $ficha->parish = $ficha->parish_->name;
        $ficha->municipality_id = $ficha->parish_->municipality->id;
        $ficha->municipality = $ficha->parish_->municipality->name;
        $ficha->state = $ficha->parish_->municipality->state->name;
        $ficha->state_id = $ficha->parish_->municipality->state->id;
        $ficha->asic_id = $ficha->asic;
        $ficha->asic = $ficha->asic_->name . ' - ' . $ficha->asic_->description;
        $s = $ficha->speciality->name;
        $t = $ficha->type_patient_->name;
        $c = $ficha->classification->name;
        $i = $ficha->intervention->name;
        unset($ficha->parish_, $ficha->speciality, $ficha->type_patient_, $ficha->classification, $ficha->intervention);
        $ficha->speciality = $s;
        $ficha->type_patient_ = $t;
        $ficha->classification = $c;
        $ficha->intervention = $i;
        return response()->json($ficha);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->validate($request, [
            'address' => 'required|string|min:5|max:50',
            'asic' => 'required',
            'asic_id' => 'nullable|numeric',
            'birthdate' => 'required',
            'classification_id' => 'required|numeric',
            'diagnosis' => 'required|string|min:5|max:50',
            'doctor' => 'required|string|min:3|max:30',
            // 'fr' => 'required|numeric',
            // 'imc' => 'required|numeric',
            'intervention_id' => 'required|numeric',
            'last_name' => 'required|string|min:3|max:50',
            'last_name_c' => 'nullable|string|min:3|max:50',
            'movil' => 'required|numeric|digits_between:6,9',
            'municipality' => 'required|numeric',
            'name' => 'required|string|min:3|max:50',
            'name_c' => 'nullable|string|min:3|max:50',
            'num_history' => 'required|numeric|digits_between:4,9',
            'num_id' => 'required|numeric|digits_between:6,9',
            'num_id_c' => 'nullable|numeric|digits_between:6,9',
            // 'pa' => 'required|numeric|digits_between:1,2',
            'parish' => 'required|numeric',
            // 'pathology' => 'required|string|max:150|min:3',
            'phone' => 'nullable|numeric|digits_between:6,14',
            'phone_c' => 'nullable|numeric|digits_between:6,14',
            'relation_c' => 'nullable|string',
            'sex' => 'required|boolean',
            'size' => 'nullable|numeric',
            'speciality_id' => 'required|numeric',
            'state' => 'required|numeric',
            'surgical_procedure' => 'required|string',
            'type_patient' => 'required|numeric',
            'weight' => 'nullable|numeric',
        ],[],[
            'address' => 'direccion',
            'asic' => 'asic',
            'birthdate' => 'fecha de nacimiento',
            'classification_id' => 'clasificacion',
            'diagnosis' => 'diagnostico',
            'doctor' => 'doctor',
            'fr' => 'frecuencia cardiaca',
            'imc' => 'indice de masa corporal',
            'intervention_id' => 'invencion',
            'last_name' => 'apellido',
            'last_name_c' => 'apellido de acompañante',
            'movil' => 'movil',
            'municipality' => 'municipio',
            'name' => 'nombre',
            'name_c' => 'nombre de acompañante',
            'num_history' => 'numero de historia',
            'num_id' => 'numero de cedula',
            'num_id_c' => 'numero de cedula acompañante',
            'pa' => 'pulso',
            'parish' => 'parroquia',
            // 'pathology' => 'patologia',
            'phone' => 'telefono fijo',
            'phone_c' => 'telefono de acompañante',
            'relation_c' => 'relacion de acompañante',
            'sex' => 'sexo',
            'size' => 'talla',
            'speciality_id' => 'especialidad',
            'state' => 'estado',
            'surgical_procedure' => 'procedimiento quirurgico',
            'type_patient' => 'tipo de paciente',
            'weight' => 'peso',
        ]);
        $data['asic'] = $data['asic_id'];
        $data['birthdate'] = \Carbon::parse(str_replace('/', '-', $request->birthdate))->format('Y-m-d');
        $data['parish_id'] = $data['parish'];

        Ficha::findOrFail($id)->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Ficha::findOrFail($id)->delete();
    }

    public function data()
    {
        $states = State::get(['id', 'name']);
        $health_centers = [];
        $pathologies = [];
        $requests = [];
        $asics = Asic::get(['id', 'name', 'description']);
        $asics->each(function ($a) {
            $a->name = $a->name . ' - ' . $a->description;
        });
        $type_patients = TypePatient::get(['id', 'name']);
        $interventions = [];
        foreach (\Auth::user()->specialties as $s) {
            if (!in_array($s->intervention_id, $interventions)) $interventions[] = $s->intervention_id;
        }
        $interventions = Intervention::whereIn('id', $interventions)->get(['id', 'name']);
        $classifications = Classification::get(['id', 'name']);
        return response()->json(compact('states', 'health_centers', 'pathologies', 'requests', 'type_patients', 'interventions', 'classifications', 'asics'));
    }

    public function municipalities(Request $request)
    {
        $municipalities = Municipality::where('state_id', '=', $request->id)->get(['name', 'id']);
        return response()->json(compact('municipalities'));
    }

    public function parishes(Request $request)
    {
        $parishes = Parish::where('municipality_id', '=', $request->id)->get(['name', 'id']);
        return response()->json(compact('parishes'));
    }

    public function getspeciality(Request $request)
    {
        $specialties = Specialty::where('intervention_id', '=', $request->id)
        ->whereIn('id', \Auth::user()->specialties->pluck('id'))
        ->get(['name', 'id']);
        return response()->json(compact('specialties'));
    }

    public function specialities()
    {
        $specialty = Specialty::get(['id', 'name', 'intervention_id']);
        $specialty->each(function ($s) {
            $s->name = $s->intervention->name . ' - ' . $s->name;
            unset($s->intervention, $s->intervention_id);
        });
        return response()->json($specialty);
    }
}
