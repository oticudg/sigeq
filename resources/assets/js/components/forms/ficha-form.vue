<template>
	<div class="box">
		<div class="box-header text-center">
			<h1 class="box-title">Datos Personales <i class="fa fa-user"></i></h1><hr>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-3" v-for="input in entries.personales">
					<v-input :name="input" required="true"
					:type="input.type"
					v-model="data[input.id]"
					:msg="msg[input.id]"
					@input="data[input.id] = arguments[0]"></v-input>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label for="birthdate" class="control-label">
							<span class="fa fa-calendar"></span> Fecha de Nacimiento:
						</label>
						<date-picker id="birthdate" v-model="data.birthdate" :config="{format: 'DD/MM/YYYY', useCurrent: false, showClear: true, showClose: true} " required="required"></date-picker>
						<small id="birthdateHelp" class="form-text text-muted">
							<span v-text="msg.birthdate"></span>
						</small>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label for="sex" class="control-label">
							<span class="fa fa-venus-mars"></span> Sexo:
						</label>
						<select id="sex" class="form-control" v-model="data.sex">
							<option value="">Seleccione un sexo</option>
							<option :value="0">Femenino</option>
							<option :value="1">Masculino</option>
						</select>
						<small id="sexHelp" class="form-text text-muted">
							<span v-text="msg.sex"></span>
						</small>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label for="email" class="control-label">
							<span class="da da-email"></span> Email:
						</label>
						<input type="text" id="email" class="form-control" v-model="data.email">
						<small id="emailHelp" class="form-text text-muted">
							<span v-text="msg.email"></span>
						</small>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label for="state" class="control-label">
							<span class="glyphicon glyphicon-calendar"></span> Estado:
						</label>
						<v-multiselect v-model="state" :options="states_options" :close-on-select="true"></v-multiselect>
						<small id="stateHelp" class="form-text text-muted">
							<span v-text="msg.state"></span>
						</small>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label for="municipality" class="control-label">
							<span class="glyphicon glyphicon-calendar"></span> Municipio:
						</label>
						<v-multiselect v-model="municipality" :options="municipalities_options" :close-on-select="true"></v-multiselect>
						<small id="municipalityHelp" class="form-text text-muted">
							<span v-text="msg.municipality"></span>
						</small>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label for="parish" class="control-label">
							<span class="glyphicon glyphicon-calendar"></span> Parroquia:
						</label>
						<v-multiselect v-model="parish" :options="parishes_options" :close-on-select="true"></v-multiselect>
						<small id="parishHelp" class="form-text text-muted">
							<span v-text="msg.parish"></span>
						</small>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label for="address" class="control-label">
							<span class="fa fa-address-book"></span> Dirección:
						</label>
						<textarea id="address" class="form-control" cols="15" rows="5" v-model="data.address" style="resize: none;"></textarea>
						<small id="addressHelp" class="form-text text-muted">
							<span v-text="msg.address"></span>
						</small>
					</div>
				</div>
			</div>
		</div>

		<div class="box-header text-center">
			<h1 class="box-title">Persona de Contacto <i class="fa fa-users"></i></h1><hr>
		</div>
		<div class="box-body">
			<div class="row">

				<div class="col-md-3" v-for="input in entries.contacto">
					<v-input :name="input" required="true"
					:type="input.type"
					v-model="data[input.id]"
					:msg="msg[input.id]"
					@input="data[input.id] = arguments[0]"></v-input>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label for="relation" class="control-label">
							<span class="fa fa-users"></span> Relación:
						</label>
						<select id="relation" class="form-control" v-model="data.relation_c">
							<option value="">Seleccione el parentesco con el paciente</option>
							<option>Madre</option>
							<option>Padre</option>
							<option>Hij@</option>
							<option>Ti@</option>
							<option>Herman@</option>
							<option>Abuel@</option>
							<option>Espos@</option>
							<option>Otr@</option>
						</select>
						<small id="relationHelp" class="form-text text-muted">
							<span v-text="msg.relation"></span>
						</small>
					</div>
				</div>

			</div>
		</div>
		<!-- <div class="box-header text-center">
			<h1 class="box-title">AREA DE SALUD INTEGRAL COMUNITARIA <i class="fa fa-card"></i></h1><hr>
		</div>
		<div class="box-body">
			<div class="row">

				<div class="col-md-4">
					<div class="form-group">
						<label for="type_patient" class="control-label">
							<span class="glyphicon glyphicon-calendar"></span> Tipo de Paciente:
						</label>
						<input type="text" id="type_patient" class="form-control" v-model="data.type_patient">
						<small id="type_patientHelp" class="form-text text-muted">
							<span v-text="msg.type_patient"></span>
						</small>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="type_patient" class="control-label">
							<span class="glyphicon glyphicon-calendar"></span> Tipo de Paciente:
						</label>
						<select id="type_patient" class="form-control" v-model="data.type_patient">
							<option value="">Seleccione el tipo de paciente</option>
							<option v-for="t in type_patients" value="t.id" v-text="t.name"></option>
						</select>
						<small id="type_patientHelp" class="form-text text-muted">
							<span v-text="msg.type_patient"></span>
						</small>
					</div>
				</div>

			</div>
		</div> -->
		<div class="box-header text-center">
			<h1 class="box-title">Intervención Quirurgica<i class="fa fa-card"></i></h1><hr>
		</div>
		<div class="box-body">
			<div class="row">

				<div class="col-md-4">
					<div class="form-group">
						<label for="type_patient" class="control-label">
							<span class="fa fa-h-square"></span> Tipo de Paciente:
						</label>
						<select id="type_patient" class="form-control" v-model="data.type_patient">
							<option value="">Seleccione el tipo de paciente</option>
							<option v-for="t in type_patients" :value="t.id" v-text="t.name"></option>
						</select>
						<small id="type_patientHelp" class="form-text text-muted">
							<span v-text="msg.type_patient"></span>
						</small>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="classification_id" class="control-label">
							<span class="fa fa-th-list"></span> Clasificación:
						</label>
						<select id="classification_id" class="form-control" v-model="data.classification_id">
							<option value="">Seleccione el tipo de paciente</option>
							<option v-for="c in classifications" :value="c.id" v-text="c.name"></option>
						</select>
						<small id="classification_idHelp" class="form-text text-muted">
							<span v-text="msg.classification_id"></span>
						</small>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="intervention_id" class="control-label">
							<span class="fa fa-ambulance"></span> Tipo de Intervención:
						</label>
						<select id="intervention_id" class="form-control" v-model="data.intervention_id" @change="getspeciality">
							<option value="">Seleccione el tipo de paciente</option>
							<option v-for="i in interventions" :value="i.id" v-text="i.name"></option>
						</select>
						<small id="intervention_idHelp" class="form-text text-muted">
							<span v-text="msg.intervention_id"></span>
						</small>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="speciality_id" class="control-label">
							<span class="fa fa-universal-access"></span> Especialidad:
						</label>
						<select id="speciality_id" class="form-control" v-model="data.speciality_id">
							<option value="">Seleccione el tipo de paciente</option>
							<option v-for="s in specialities" :value="s.id" v-text="s.name"></option>
						</select>
						<small id="speciality_idHelp" class="form-text text-muted">
							<span v-text="msg.speciality_id"></span>
						</small>
					</div>
				</div>

				<div class="col-md-8">
					<div class="form-group">
						<label for="asic" class="control-label">
							<span class="glyphicon glyphicon-calendar"></span> ASIC:
						</label>
						<v-multiselect v-model="asic" :options="asics_options" :close-on-select="true"></v-multiselect>
						<small id="asicHelp" class="form-text text-muted">
							<span v-text="msg.asic"></span>
						</small>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="doctor" class="control-label">
							<span class="glyphicon glyphicon-calendar"></span> Médico Tratante:
						</label>
						<input type="text" id="doctor" class="form-control" v-model="data.doctor">
						<small id="doctorHelp" class="form-text text-muted">
							<span v-text="msg.doctor"></span>
						</small>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="surgical_procedure" class="control-label">
							<span class="glyphicon glyphicon-calendar"></span> Procedimiento Quirúrgico:
						</label>
						<input type="text" id="surgical_procedure" class="form-control" v-model="data.surgical_procedure">
						<!-- <select >
							<option value="">Seleccione el Procedimiento Quirúrgico</option>
							<option v-for="s in specialities" :value="s.id" v-text="s.name"></option>
						</select> -->
						<small id="surgical_procedureHelp" class="form-text text-muted">
							<span v-text="msg.surgical_procedure"></span>
						</small>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="num_history" class="control-label"><span class="fa fa-file-text"></span> N° Historia:
						</label>
						<input id="num_history" type="text" required="required" class="form-control" v-model="data.num_history">
						<small id="num_historyHelp" class="form-text text-muted">Numero de historia.</small>
					</div>
				</div>

				<!-- <div class="col-md-4">
					<div class="form-group">
						<label for="health_center" class="control-label">
							<span class="glyphicon glyphicon-calendar"></span> Centro de salud:
						</label>
						<v-multiselect v-model="health_center" :options="health_centers_options" :close-on-select="true"></v-multiselect>
						<small id="health_center" class="form-text text-muted">
							<span v-text="msg.health_center"></span>
						</small>
					</div>
				</div> -->

				<!-- <div class="col-md-4">
					<div class="form-group">
						<label for="pathology" class="control-label">
							<span class="glyphicon glyphicon-calendar"></span> Patologías:
						</label>
						<v-multiselect v-model="pathology" :options="pathologies_options" :close-on-select="true"></v-multiselect>
						<small id="pathology" class="form-text text-muted">
							<span v-text="msg.pathology"></span>
						</small>
					</div>
				</div> -->

				<!-- <div class="col-md-4">
					<div class="form-group">
						<label for="request" class="control-label">
							<span class="glyphicon glyphicon-calendar"></span> Solicitud:
						</label>
						<v-multiselect v-model="request" :options="requests_options" :close-on-select="true"></v-multiselect>
						<small id="request" class="form-text text-muted">
							<span v-text="msg.request"></span>
						</small>
					</div>
				</div> -->

				<div class="row">
					<div class="col-md-12">
						<div class="col-md-6">
							<div class="form-group">
								<label for="diagnosis" class="control-label">
									<span class="fa fa-file-o"></span> Diagnóstico:
								</label>
								<textarea id="diagnosis" class="form-control" cols="15" rows="5" v-model="data.diagnosis" style="resize: none;"></textarea>
								<small id="diagnosisHelp" class="form-text text-muted">
									<span v-text="msg.diagnosis"></span>
								</small>
							</div>
						</div>

						<!-- <div class="col-md-6">
							<div class="form-group">
								<label for="pathology" class="control-label">
									<span class="fa fa-pencil-square-o"></span> Patología:
								</label>
								<textarea id="pathology" class="form-control" cols="15" rows="5" v-model="data.pathology" style="resize: none;"></textarea>
								<small id="pathologyHelp" class="form-text text-muted">
									<span v-text="msg.pathology"></span>
								</small>
							</div>
						</div> -->
					</div>
				</div>

			</div>
		</div>

		<!-- <div class="box-header text-center">
			<h1 class="box-title">Historia Clínica <i class="fa fa-card"></i></h1><hr>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-3" v-for="input in entries.historia">
					<v-input :name="input" required="true"
					:type="input.type"
					v-model="data[input.id]"
					:msg="msg[input.id]"
					@input="data[input.id] = arguments[0]"></v-input>
				</div>
			</div>
		</div> -->
		<div class="box-footer text-right">
			<input type="submit" class="btn btn-primary" @click.prevent="registrar" value="Guardar">
		</div>
	</div>
</template>

<script>
	import Input from './../partials/input.vue';
	import DatePicker from 'vue-bootstrap-datetimepicker';
	import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css';
	import Multiselect from 'vue-multiselect';

	export default {
		name: 'ficha-form',
		components: {
			'v-input': Input,
			DatePicker,
			'v-multiselect': Multiselect,
		},
		data () {
			return {
				state: '',
				states: [],
				states_options: [],
				municipality: [],
				municipalities: [],
				municipalities_options: [],
				municipality: [],
				parish: '',
				parishes: [],
				parishes_options: [],
				health_center: '',
				health_centers: [],
				health_centers_options: [],
				pathology: '',
				pathologies_options: [],
				pathologies: [],
				request: '',
				requests: [],
				requests_options: [],
				type_patients: [],
				interventions: [],
				classifications: [],
				specialities: [],
				asics_options: [],
				asics: [],
				asic: '',
				data: {
					sex: '',
					type_patient: '',
					intervention_id: '',
					classification_id: '',
					speciality_id: '',
					relation_c: '',
					surgical_procedure_id: '',
					asic: ''
				},
				entries: {
					personales: [
					{label: 'Nombres', id: 'name', icon: 'fa fa-user-circle'},
					{label: 'Apellidos', id: 'last_name', icon: 'fa fa-user-circle-o'},
					{label: 'Cédula', id: 'num_id', icon: 'fa fa-id-card', type: 'number'},
					{label: 'Teléfono fijo', id: 'phone', icon: 'fa fa-phone-square'},
					{label: 'Telefono movil', id: 'movil', icon: 'fa fa-phone'},
					],
					contacto: [
					{label: 'Nombre', id: 'name_c', icon: 'fa fa-user-circle'},
					{label: 'Apellidos', id: 'last_name_c', icon: 'fa fa-user-circle-o'},
					{label: 'Cédula', id: 'num_id_c', icon: 'fa fa-id-card', type: 'number'},
					{label: 'Teléfono', id: 'phone_c', icon: 'fa fa-phone-square'},
					],
					// historia: [
					// {label: 'Peso', id: 'weight', icon: 'fa fa-balance-scale'},
					// {label: 'Talla', id: 'size', icon: 'fa fa-sort-numeric-asc'},
					// {label: 'IMC', id: 'imc', icon: 'fa fa-male'},
					// {label: 'PA', id: 'pa', icon: 'fa fa-heartbeat'},
					// {label: 'FR', id: 'fr', icon: 'fa fa-heart'},
					// ],
				},
				msg: {
					name: 'Nombres del paciente.',
					last_name: 'Apellidos del paciente.',
					num_id: 'Cedula del paciente.',
					phone: 'Numero telefonico fijo.',
					movil: 'Numero telefonico movil.',
					name_c: 'Nombres del acompañante.',
					last_name_c: 'Apellidos del acompañante.',
					num_id_c: 'Cedula del acompañante.',
					email: 'Correo electronico del paciente.',
					asic: 'Ingrese la ASIC proveniente del paciente',
					phone_c: 'Numero telefonico.',
					num_history: 'Numero de historia.',
					weight: 'Peso corporal.',
					size: 'Talla.',
					imc: 'Indice de masa corporal.',
					pa: 'Pulso.',
					fr: 'Frecuencia cardiaca.',
					doctor: 'Agregar el nombre del doctor Tratante',
					birthdate: 'Fecha de nacimiento.',
					sex: 'Sexo.',
					address: 'Direccion.',
					pathology: 'Patología del paciente',
					relation_c: 'Relacion del acompañante.',
					type_patient: 'Tipo de paciente.',
					classification_id: 'Clasificaciones.',
					intervention_id: 'Intervenciones.',
					speciality_id: 'Especialidad.',
					diagnosis: 'Diagnosis.',
					observation: 'Observacion.',
				}
			};
		},
		watch: {
			state: function (val) {
				for(let i in this.states) {
					if (this.states[i].name == val) {
						this.data.state = this.states[i].id;
						axios.post('/municipalities', { id: this.data.state })
						.then(response => {
							this.municipalities_options = [];
							this.municipalities = response.data.municipalities;
							this.municipality = '';
							for(let i in this.municipalities) {
								this.municipalities_options.push(this.municipalities[i].name);
							}
						});
						return;
					} else {this.municipality = '';}
				}
			},
			municipality: function (val) {
				for(let i in this.municipalities) {
					if (this.municipalities[i].name == val) {
						this.data.municipality = this.municipalities[i].id;
						axios.post('/parish', { id: this.data.municipality })
						.then(response => {
							this.parishes_options = [];
							this.parishes = response.data.parishes;
							this.parish = '';
							for(let i in this.parishes) {
								this.parishes_options.push(this.parishes[i].name);
							}
						});
						return;
					} else {this.parish = '';}
				}
			},
			parish: function (val) {
				for(let i in this.parishes) {
					if (this.parishes[i].name == val) {
						this.data.parish = this.parishes[i].id;
						return;
					} else {this.parish = '';}
				}
			},
			/*parish: function (val) {
				for(let i in this.parishes) {
					if (this.parishes[i].name == val) {
						this.data.parish = this.parishes[i].id;
						return;
					} else {this.parish = '';}
				}
			},*/
			health_center: function (val) {
				for(let i in this.health_centers) {
					if (this.health_centers[i].name == val) {
						this.data.health_center = this.health_centers[i].id;
						return;
					}
				}
			},
			pathology: function (val) {
				for(let i in this.pathologies) {
					if (this.pathologies[i].name == val) {
						this.data.pathology = this.pathologies[i].id;
						return;
					}
				}
			},
			asic: function (val) {
				for(let i in this.asics) {
					if (this.asics[i].name == val) {
						this.data.asic = this.asics[i].id;
						return;
					}
				}
			},
		},
		mounted() {
			axios.post('/get-ficha')
			.then(response => {
				this.type_patients = response.data.type_patients;
				this.interventions = response.data.interventions;
				this.classifications = response.data.classifications;

				this.asics_options = [];
				this.asics = response.data.asics;
				this.asic = '';
				for(let i in this.asics) {
					this.asics_options.push(this.asics[i].name);
				}

				this.states_options = [];
				this.states = response.data.states;
				for(let i in this.states) {
					this.states_options.push(this.states[i].name);
				}

				this.health_center = '';
				this.health_centers = response.data.health_centers;
				for(let i in this.health_centers) {
					this.health_centers_options.push(this.health_centers[i].name);
				}

				this.pathology = '';
				this.pathologies = response.data.pathologies;
				for(let i in this.pathologies) {
					this.pathologies_options.push(this.pathologies[i].name);
				}

				this.request = '';
				this.requests = response.data.requests;
				for(let i in this.requests) {
					this.requests_options.push(this.requests[i].name);
				}
			});
			if (this.$route.params.id) {
				axios.get('/ficha/' + this.$route.params.id)
				.then(response => {
					this.data = response.data;
					this.state = response.data.state;
					this.municipality = response.data.municipality;
					this.parish = response.data.parish;
					this.data.state = response.data.state_id;
					this.data.municipality = response.data.municipality_id;
					this.data.parish = response.data.parish_id;
					this.asic = response.data.asic;
					this.getspeciality();
				});
			}
		},
		methods: {
			getspeciality: function () {
				axios.post('/getspeciality', { id: this.data.intervention_id })
				.then(response => {
					this.specialities = response.data.specialties;
				});
			},
			registrar: function () {
				if (this.$route.params.id) {
					axios.put('/ficha/' + this.$route.params.id, this.data)
					.then(response => {
						toastr.success('Actualización Exitosa');
						window.location = '/fichas'
					});
				} else {
					this.restoreMsg(this.msg);
					axios.post('/ficha', this.data)
					.then(response => {
						toastr.success('Registro Exitoso');
						window.location = '/fichas'
					});
				}
			}
		}
	}
</script>
