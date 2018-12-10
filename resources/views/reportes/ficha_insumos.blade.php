<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<style>
	table {
		border: 0px solid rgba(204,204,204,.7);
		font-size: 10pt;
	}
	table th {
		background-color: rgba(204,204,204,.7);
		border: 1px solid rgba(204,204,204,.7);
		padding: 3px;
	}
	table td {
		padding: 3px;
		border: 1px solid rgba(204,204,204,1);
	}
</style>
</head>
<body>
	<div class="container" style="font-family: helvetica">
		<img src="{{  asset('/images/membrete.png') }}" style="width: 100%;height: 70px">
		<h2 style="text-align: center;"><u>Reporte de Fichas</u></h2>
		@foreach($fichas as $f)
		@if($f->insumos->where('state', 0)->count() > 0)
		<table id="ficha" width="100%" border="1" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<th>N° Historia</th>
					<td>{{ $f->num_history }}</td>
					<th>Nombre</th>
					<td>{{ $f->name }} {{ $f->last_name }}</td>
					<th>Especialidad</th>
					<td>{{ $f->speciality->name }}</td>
					<th>Registro</th>
					<td>{{ $f->created_at->format('Y-m-d') }}</td>
				</tr>
				<tr>
					<td colspan="9" style="padding: 0">
						<table id="insumos"  width="100%" border="1" cellpadding="0" cellspacing="0">
							<thead style="background: rgba(204,204,204,.7);">
								<tr>
									<th>Insumo</th>
									<th>Cantidad Requerida</th>
									<th>Origen</th>
									<th>Observación</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								@foreach($f->insumos->where('state', 0) as $i)
								{{-- @if($i->state == 0) --}}
								<tr>
									<td>{{ $i->insumo->name }}</td>
									<td>{{ $i->cant }}</td>
									<td>{{ $i->origen }}</td>
									<td>{{ $i->observation }}</td>
									<td>{{ ($i->state) ? 'Entregado' : 'No Entregado' }}</td>
								</tr>
								{{-- @endif --}}
								@endforeach
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<br>
		@endif
		@endforeach
	</div>
</body>
</html>