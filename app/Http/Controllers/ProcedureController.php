<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Procedure,Subprocedure,Ficha_procedure,Ficha};

class ProcedureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'procedure' => 'required|string|min:3|max:50',
            'id' => 'required|numeric',
            'procedure_id' => 'required|numeric'
        ],[],['procedure' => 'procedimiento']);
        Ficha_procedure::create([
            'name' => $data['procedure'],
            'ficha_id' => $data['id'],
            'procedure_id' => $data['procedure_id'],
        ]);
        if ($data['procedure_id'] == 1) {
            Ficha::findOrFail($data['id'])->update(['date_check_pre' => null]);
        } elseif ($data['procedure_id'] == 2) {
            Ficha::findOrFail($data['id'])->update(['date_interconsultas' => null]);
        } elseif ($data['procedure_id'] == 3) {
            Ficha::findOrFail($data['id'])->update(['date_valoration_pre' => \Carbon::now()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $f = Ficha_procedure::findOrFail($id);
        return response()->json($f);
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
        $ficha = Ficha::findOrFail($id);
        $ficha->update([
            'observation_pre_operatorio' => $request->ficha['observation_pre_operatorio'],
            'observation_interconsultas' => $request->ficha['observation_interconsultas'],
            'consulta_pre_anestesia_id' => $request->ficha['consulta_pre_anestesia_id'],
            'cirugia_emergencia' => $request->ficha['cirugia_emergencia'],
            'apto' => $request->ficha['apto'],
            'observation_riesgo_anestesico' => $request->ficha['observation_riesgo_anestesico'],
        ]);
        if ($ficha['consulta_pre_anestesia_id'] > 0 == 1 && is_string($ficha['apto'])) {
            $ficha->update(['date_valoration_pre' => \Carbon::now()]);
        } else {
            $ficha->update(['date_valoration_pre' => null]);
        }
        foreach ($request->ficha_procedures as $f) {
            Ficha_procedure::findOrFail($f['id'])->update(['state' => $f['state']]);
        }
        $procedure = Procedure::get();
        foreach ($procedure as $p) {
            foreach ($request->ficha_procedures as $f) {
                $fi = Ficha_procedure::where('ficha_id', '=', $id)
                ->where('procedure_id', '=', $p->id)
                ->get();
                $state = true;
                foreach ($fi as $sub) {
                    if ($sub->state == 0) {$state = false;}
                }
            }
            if ($state && $p->id == 1) {Ficha::findOrFail($id)->update(['date_check_pre' => \Carbon::now()]); } elseif ($p->id == 1) { Ficha::findOrFail($id)->update(['date_check_pre' => null]); }
            if ($state && $p->id == 2) {Ficha::findOrFail($id)->update(['date_interconsultas' => \Carbon::now()]); } elseif ($p->id == 1) { Ficha::findOrFail($id)->update(['date_interconsultas' => null]); }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Ficha_procedure::findOrFail($id)->delete();
    }

    public function data(Request $request)
    {
        $procedures = Procedure::get(['id', 'name']);
        $ficha = Ficha::findOrFail($request->id);
        $procedures->each(function ($p) use ($ficha) {
            if ($p->id == 1) {$p->state = !!$ficha->date_check_pre; }
            if ($p->id == 2) {$p->state = !!$ficha->date_interconsultas; }
            if ($p->id == 3) {$p->state = !!$ficha->date_valoration_pre; }
        });
        $subprocedures = Subprocedure::where('procedure_id', '=', 3)->get();
        $ficha_procedures = Ficha_procedure::where('ficha_id', $request->id)
        ->get(['id', 'name', 'state', 'apply', 'ficha_id', 'procedure_id', 'subprocedure_id']);
        return response()->json(compact('procedures', 'ficha_procedures', 'subprocedures', 'ficha'));
    }
}
