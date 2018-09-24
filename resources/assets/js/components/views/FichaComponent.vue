<template>
	<div class="box">
		<div class="box-header">
			<h3 class="box-title">Tabla de Fichas: </h3>
			<router-link :to="{ name: 'ficha.store' }"
			class="btn btn-default btn-xs"
			data-tooltip="tooltip"
			title="Registrar Ficha"><span class="glyphicon glyphicon-plus"></span></router-link>
			<button type="button"
			class="btn btn-success btn-xs"
			@click="show = !show"
			data-tooltip="tooltip"
			title="Buscador Avanzado"><span class="glyphicon glyphicon-search"></span></button>
			<a :href="'/report?speciality_id=' + data.speciality_id + '&date_i=' + data.date_i + '&date_f=' + data.date_f"
			class="btn btn-default btn-xs"
			data-tooltip="tooltip"
			title="Generar Reporte"><span class="glyphicon glyphicon-file"></span></a>
			<div class="row" v-show="show">
				<div class="col-md-4">
					<div class="form-group">
						<label for="speciality_id" class="control-label"> Especialidades: </label>
						<v-multiselect v-model="speciality" :options="specialities_options" :close-on-select="true"></v-multiselect>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="date_i" class="control-label"> Fecha de Inicio: </label>
						<date-picker v-model="data.date_i" :config="{format: 'YYYY-MM-DD', useCurrent: true, locale: 'es'}"></date-picker>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="date_f" class="control-label"> Fecha de Final: </label>
						<date-picker v-model="data.date_f" :config="{format: 'YYYY-MM-DD', useCurrent: true, locale: 'es'}"></date-picker>
					</div>
				</div>
				<div class="col-md-2">
					<button class="btn btn-primary btn-xs" style="margin-top: 33px;" @click="search"><i class="fa fa-search"></i></button>
					<button class="btn btn-warning btn-xs" style="margin-top: 33px;" @click="refresh"><i class="fa fa-refresh"></i></button>
				</div>
			</div>
		</div>
		<div class="box-body">
			<v-table id="ficha" :tabla="tabla" uri="/ficha" :data="'1&speciality_id=' + data.speciality_id + '&date_i=' + data.date_i + '&date_f=' + data.date_f"></v-table>
		</div>
	</div>
</template>

<script>
	import Tabla from './../partials/table.vue';
	import Multiselect from 'vue-multiselect';
	import datePicker from 'vue-bootstrap-datetimepicker';
	import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css';

	export default {
		name: 'Fichas',
		components: {
			'v-table': Tabla,
			'v-multiselect': Multiselect,
			datePicker
		},
		data() {
			return {
				s: '',
				show: false,
				data: {
					speciality_id: '',
					date_i: '',
					date_f: '',
				},
				speciality: '',
				specialities: [],
				specialities_options: [],
				form: {
					ready: true,
					title: '',
					url: '',
					ico: '',
					cond: '',
					data: {}
				},
				tabla: {
					columns: [
					{ title: 'Nombre', field: 'name', sortable: true },
					{ title: 'N° Historia', field: 'num_history', sortable: true },
					{ title: 'Especialidad', field: 'speciality_id', sortable: true },
					{ title: 'Registro', field: 'created', sort: 'created_at', sortable: true },
					{ title: 'Insumos', field: 'date_insumo', sortable: true },
					{ title: 'Preoperatorio', field: 'date_check_pre', sortable: true },
					{ title: 'Interconsultas', field: 'date_interconsultas', sortable: true },
					{ title: 'Pre-anestesia', field: 'date_valoration_pre', sortable: true },
					{ title: 'Operación', field: 'operation', sortable: true },
					],
					options: [
					{ ico: 'fa fa-eye', class: 'btn-primary', title: 'Ver Detalles', func: (id) => {this.$router.push({ name: 'ficha.show', params: { id: id }})}, action: 'ficha.show'},
					{ ico: 'fa fa-edit', class: 'btn-info', title: 'Editar', func: (id) => {this.$router.push({ name: 'ficha.update', params: { id: id }})}, action: 'ficha.update'},
					{ ico: 'fa fa-medkit', class: 'btn-warning', title: 'Insumos', func: (id) => {this.$router.push({ name: 'ficha.insumos', params: { id: id }})}, action: 'ficha.insumos'},
					{ ico: 'fa fa-user-md', class: 'btn-success', title: 'Procedimiento', func: (id) => {this.$router.push({ name: 'ficha.procedures', params: { id: id }})}, action: 'ficha.procedures'},
					{ ico: 'fa fa-close', class: 'btn-danger', title: 'Borrar', func: (id) => {this.deleted('/ficha/'+id, this.$children[4].get, 'name'); }, action: 'ficha.destroy'},
					]
				}
			};
		},
		watch: {
			speciality: function (val) {
				for(let i in this.specialities) {
					if (this.specialities[i].name == val) {
						this.data.speciality_id = this.specialities[i].id;
						return;
					} else {this.data.speciality_id = '';}
				}
			}
		},
		mounted() {
			axios.post('/get-specialities')
			.then(response => {
				this.specialities = response.data;
				this.specialities_options = [];
				for(let i in response.data) {
					this.specialities_options.push(response.data[i].name);
				}
			});
		},
		methods: {
			refresh: function () {
				this.speciality = '';
				this.data.speciality_id = '';
				this.data.date_i = '';
				this.data.date_f = '';
				setTimeout(() => {
					this.$children[4].get();
				},300);
			},
			search: function () {
				this.$children[4].get();
			},
			report: function () {
				// axios.get('/report?speciality_id=' + this.data.speciality_id + '&date_i=' + this.data.date_i + '&date_f=' + this.data.date_f);
			}
		}
	}
</script>
