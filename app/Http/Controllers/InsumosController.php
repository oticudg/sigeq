<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ { Ficha_insumo, Insumo, Ficha };

class InsumosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ficha = Ficha_insumo::orderBy($request->order?:'id', $request->dir?:'ASC')
        ->search($request->search)
        ->where('ficha_id', '=', $request->id)
        ->select(['*'])
        ->paginate($request->num?:10);

        $ficha->each(function ($f) {
            $f->insumo_id = $f->insumo->code . ' - ' . $f->insumo->name;
            $f->state = ($f->state) ? 
            '<i class="glyphicon glyphicon-check text-center"></i>' : 
            '<i class="glyphicon glyphicon-unchecked text-center"></i>';
            if ($f->origen == 1) {
                $f->origen = 'PARTICULAR';
            } elseif ($f->origen == 2) {
                $f->origen = 'DONACIÓN';
            } elseif ($f->origen == 3) {
                $f->origen = 'INTITUCIONAL';
            } elseif ($f->origen == 4) {
                $f->origen = 'OTRO';
            }
            unset($f->insumo);
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
            'cant' => 'nullable|string|max:25',
            'ficha_id' => 'required|numeric',
            'insumo_id' => 'required|numeric',
            'observation' => 'nullable|string|max:50',
            'origen' => 'required_if:state,1|max:30',
            'state' => 'required|numeric',
        ],[
            'origen.required_if' => 'el campo es requerido'
        ],[
            'cant' => '',
            'state' => 'estado',
            'ficha_id' => '',
            'insumo_id' => '',
            'observation' => '',
            'origen' => '',
        ]);
        $f = Ficha_insumo::create($data);

        if ($request->state == 0) {
            Ficha::findOrFail($request->ficha_id)->update(['date_insumo' => null]);
        } else {
            $ficha = Ficha::findOrFail($request->ficha_id);
            $in = $ficha->insumos;
            $state = true;
            if ($in->count() == 0) {
                $state = false;
            } else {
                foreach ($in as $i) {
                    if ($i->state == 0) {
                        $state = false;
                    }
                }
                if ($state) Ficha::findOrFail($request->ficha_id)->update(['date_insumo' => \Carbon::now()]);
            }
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
        $ficha_insumo = Ficha_insumo::findOrFail($id);
        $insumo = $ficha_insumo->insumo->code . ' - ' . $ficha_insumo->insumo->name;
        unset($ficha_insumo->insumo);
        $ficha_insumo->insumo = $insumo;
        return response()->json($ficha_insumo);
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
            'cant' => 'nullable|string|max:25',
            'insumo_id' => 'required|numeric',
            'id' => 'required',
            'observation' => 'nullable|string|max:50',
            'origen' => 'required_if:state,1|max:30',
            'state' => 'required|numeric',
        ],[
            'origen.required_if' => 'el campo es requerido'
        ],[
            'state' => 'estado',
            'cant' => 'cantidad',
            'insumo_id' => 'insumo',
            'observation' => 'observacion',
            'origen' => 'origen',
        ]);
        $insu = Ficha_insumo::findOrFail($id);
        if ($insu->state == 1) return response()->json(['message' => 'Ya fue entregado este insumo'], 401);
        $insu->update($data);
        if ($request->state == 0) {
            Ficha::findOrFail($request->ficha_id)->update(['date_insumo' => null]);
        } else {
            $ficha = Ficha::findOrFail($request->ficha_id);
            $in = $ficha->insumos;
            $state = true;
            foreach ($in as $i) {
                if ($i->state == 0) {
                    $state = false;
                }
            }
            if ($state) $ficha->update(['date_insumo' => \Carbon::now()]);
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
        $a = Ficha_insumo::findOrFail($id);
        if ($a->state == 1) return response()->json(['message' => 'No se puede modificar este insumo'], 401);
        $f = $a->ficha_id;
        $a->delete($id);
        $ficha = Ficha::findOrFail($f);
        $in = $ficha->insumos;
        $state = true;
        foreach ($in as $i) {
            if ($i->state == 0) {
                $state = false;
            }
        }
        if ($state) $ficha->update(['date_insumo' => \Carbon::now()]);
    }

    public function data(Request $request)
    {
        $insumos = Insumo::get(['id', 'code', 'name']);
        $insumos->each(function ($i) {
            $i->name = /*$i->code . ' - ' . */$i->name;
        });
        $ficha = Ficha::findOrFail($request->id);
        $in = $ficha->insumos;
        $state = true;
        if ($in->count() == 0) {
            $state = false;
        } else {
            foreach ($in as $i) {
                if ($i->state == 0) {
                    $state = false;
                }
            }
        }
        return response()->json(compact('insumos', 'state', 'ficha'));
    }
}
