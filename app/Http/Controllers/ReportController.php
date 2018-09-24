<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ficha;

class ReportController extends Controller
{
	public function ficha_insumos(Request $request)
	{
		if (!is_null($request->date_i) && $request->date_i != 'null') {
			$date_i = \Carbon::parse($request->date_i);
		} else {$date_i = '2018-01-01';}
		if (!is_null($request->date_f) && $request->date_f != 'null') {
			$date_f = \Carbon::parse($request->date_f);
		} else {$date_f = \Carbon::now();}

		$fichas = Ficha::where('date_insumo', null)
		->where('speciality_id', (($request->speciality_id) ? '=' : '!='), $request->speciality_id)
		->whereBetween('created_at', [$date_i, $date_f])
		->get();
		// return view('reportes.ficha_insumos', compact('fichas'));
		return $pdf = \PDF::loadView('reportes.ficha_insumos', compact('fichas'))
		->setPaper('a4', 'landscape')
		->download('Reporte SIGEQ - ' . \Carbon::now()->format('d-m-Y H:m') . '.pdf');
	}
}
