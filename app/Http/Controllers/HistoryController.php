<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ { History, Causa, Ficha };

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $history = History::orderBy($request->order?:'id', $request->dir?:'ASC')
        ->search($request->search)
        ->where('ficha_id', '=', $request->data)
        ->paginate($request->num?:10);
        $history->each(function ($h) {
            if ($h->state == 1) {
                $h->state = 'Cirugia';
            } elseif ($h->state == 2) {
                $h->state = 'Suspension/Omision';
            } elseif ($h->state == 3) {
                $h->state = 'Reprogramar';
            } elseif ($h->state == 4) {
                $h->state = 'Operado';
            }
        });
        return $this->dataWithPagination($history);
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
            'causa_id' => 'nullable|numeric',
            'ficha_id' => 'required|numeric',
            'observation' => 'nullable',
            'anestesiologo' => 'required_if:state,1',
            'cirujano' => 'required_if:state,1',
            'sala_pabellon' => 'required_if:state,1',
            'state' => 'required|numeric',
            'state_date' => 'required'
        ],[],[
            'causa_id' => 'causa',
            'ficha_id' => 'ficha',
            'observation' => 'observación',
            'sala_pabellon' => 'sala/pabellón',
            'state' => 'evento',
        ]);
        $data['state_date'] = str_replace('/', '-', $data['state_date']);
        $data['state_date'] = \Carbon::parse($data['state_date']);
        History::create($data);
        if ($request->state == 4) {
            Ficha::findOrFail($request->ficha_id)->update(['operation' => \Carbon::now()]);
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
        $history = History::findOrFail($id);
        $history->state_date = \Carbon::parse($history->state_date)->format('d/m/Y');
        return response()->json($history);
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
            'causa_id' => 'nullable|numeric',
            'ficha_id' => 'required|numeric',
            'observation' => 'nullable',
            'anestesiologo' => 'required_if:state,1',
            'cirujano' => 'required_if:state,1',
            'sala_pabellon' => 'required_if:state,1',
            'state_date' => 'required'
        ],[],[
            'causa_id' => 'causa',
            'ficha_id' => 'ficha',
            'observation' => 'observación',
            'sala_pabellon' => 'sala/pabellón',
        ]);
        $data['state_date'] = str_replace('/', '-', $data['state_date']);
        $data['state_date'] = \Carbon::parse($data['state_date']);
        $history = History::findOrFail($id)->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function data()
    {
        $causa = Causa::get(['id', 'name']);
        return response()->json(compact('causa'));
    }
}
