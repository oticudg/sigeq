<template>
  <div>
    <div class="box">
      <div class="box-header with-border text-center">
        <h3 class="box-title">Ficha: {{ data.name }} {{ data.last_name }}.</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="box-body">
        <div class="col-xs-6">
          <p class="lead">Datos personales</p>
          <div class="table-responsive">
            <table class="table table-condensed table-hover table-striped table-bordered">
              <tbody>
                <tr>
                  <th style="width:50%">Nombres:</th>
                  <td>{{ data.name }}</td>
                </tr>
                <tr>
                  <th>Apellidos</th>
                  <td>{{ data.last_name }}</td>
                </tr>
                <tr>
                  <th>Cedula:</th>
                  <td>{{ data.num_id }}</td>
                </tr>
                <tr>
                  <th>Telefono fijo:</th>
                  <td>{{ data.phone }}</td>
                </tr>
                <tr>
                  <th>Telefono movil:</th>
                  <td>{{ data.movil }}</td>
                </tr>
                <tr>
                  <th>Fecha de nacimiento:</th>
                  <td>{{ data.birthdate }}</td>
                </tr>
                <tr>
                  <th>Sexo:</th>
                  <td>{{ (data.sex) ? 'Masculino' : 'Femenino' }}</td>
                </tr>
                <tr>
                  <th>Estado:</th>
                  <td>{{ data.state }}</td>
                </tr>
                <tr>
                  <th>Municipio:</th>
                  <td>{{ data.municipality }}</td>
                </tr>
                <tr>
                  <th>Parroquia:</th>
                  <td>{{ data.parish }}</td>
                </tr>
                <tr>
                  <th>Direccion:</th>
                  <td>{{ data.address }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="col-xs-6">
          <p class="lead">Datos de Intervencion Quirurgica</p>
          <div class="table-responsive">
            <table class="table table-condensed table-hover table-striped table-bordered">
              <tbody>
                <tr>
                  <th style="width:50%">Tipo de paciente:</th>
                  <td>{{ data.type_patient_ }}</td>
                </tr>
                <tr>
                  <th>Clasificacion:</th>
                  <td>{{ data.classification }}</td>
                </tr>
                <tr>
                  <th>Tipo de intervencion:</th>
                  <td>{{ data.intervention }}</td>
                </tr>
                <tr>
                  <th>Especialidad:</th>
                  <td>{{ data.speciality }}</td>
                </tr>
                <tr>
                  <th>ASIC:</th>
                  <td>{{ data.asic }}</td>
                </tr>
                <tr>
                  <th>Medico tratante:</th>
                  <td>{{ data.doctor }}</td>
                </tr>
                <tr>
                  <th>Procedimiento quirurgico:</th>
                  <td>{{ data.surgical_procedure }}</td>
                </tr>
                <tr>
                  <th>N# Historia:</th>
                  <td>{{ data.num_history }}</td>
                </tr>
                <tr>
                  <th>Diagnostico:</th>
                  <td>{{ data.diagnosis }}</td>
                </tr>
                <tr>
                  <th>Apto para Intervención:</th>
                  <td>{{ data.apto }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="box" v-show="data.apto == 'si'">
      <div class="box-header with-border text-center">
        <h3 class="box-title">Histórico.</h3>
        <button type="button"
        class="btn btn-default btn-xs"
        data-tooltip="tooltip"
        title="Registrar"
        @click="openForm('create')"><span class="glyphicon glyphicon-plus"></span></button>
      </div>
      <div class="box-body">
        <v-table id="history" :tabla="tabla" uri="/history" :data="$route.params.id"></v-table>
      </div>
    </div>
    <div id="history-form" class="modal fade in">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Historia de: {{ data.name }} {{ data.last_name }}.</h4>
          </div>
          <div class="modal-body">
            <form action="">
              <div class="row">
                <div class="form-group col-md-4">
                  <label>Evento</label>
                  <select class="form-control" v-model="form.state" :disabled="cond == 'edit'">
                    <option value="">Seleccione un Eventos</option>
                    <option value="1">Cirugia</option>
                    <option value="2">Suspension/Omision</option>
                    <option value="3">Reprogramar</option>
                    <option value="4">Operado</option>
                  </select>
                  <small id="stateHelp" class="form-text text-muted"></small>
                </div>
                <div class="form-group col-md-4" v-if="form.state == 1 || form.state == 3">
                  <label>Cirujano</label>
                  <input type="text" class="form-control" placeholder="Cirujano" v-model="form.cirujano">
                  <small id="cirujanoHelp" class="form-text text-muted"></small>
                </div>
                <div class="form-group col-md-4" v-if="form.state == 1 || form.state == 3">
                  <label>Anestesiologo</label>
                  <input type="text" class="form-control" placeholder="Anestesiologo" v-model="form.anestesiologo">
                  <small id="anestesiologoHelp" class="form-text text-muted"></small>
                </div>
                <div class="form-group col-md-4" v-if="form.state == 1 || form.state == 3">
                  <label>Sala de pabellon</label>
                  <input type="text" class="form-control" placeholder="Sala de pabellon" v-model="form.sala_pabellon">
                  <small id="sala_pabellonHelp" class="form-text text-muted"></small>
                </div>
                <div class="form-group col-md-4">
                  <label>Fecha:</label>
                  <date-picker id="state_date" v-model="form.state_date" :config="{format: 'DD/MM/YYYY', useCurrent: false, showClear: true, showClose: true}"></date-picker>
                  <small id="state_dateHelp" class="form-text text-muted"></small>
                </div>
                <div class="form-group col-md-4" v-if="form.state == 2">
                  <label>Causa</label>
                  <select class="form-control" v-model="form.causa_id">
                    <option value="">Seleccione una causa</option>
                    <option v-for="c in causas" :value="c.id" v-text="c.name"></option>
                  </select>
                  <small id="causa_idHelp" class="form-text text-muted"></small>
                </div>
                <div class="form-group col-md-12">
                  <label>Observacion</label>
                  <textarea class="form-control" placeholder="Observaciones" v-model="form.observation"></textarea>
                  <small id="observationHelp" class="form-text text-muted"></small>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" @click="register">Guardar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Tabla from './../partials/table.vue';
  import DatePicker from 'vue-bootstrap-datetimepicker';
  import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css';

  export default {
    name: 'Show',
    components: {
      'v-table': Tabla,
      DatePicker,
    },
    data() {
      return {
        data: {},
        cond: '',
        causas: [],
        form: {},
        tabla: {
          columns: [
          { title: 'Evento', field: 'state', sortable: true },
          { title: 'Fecha', field: 'state_date', sortable: true },
          { title: 'Observaciones', field: 'observation', sortable: true },
          ],
          options: [
          { ico: 'fa fa-edit', class: 'btn-info', title: 'Editar', func: (id) => {this.openForm('edit', id)}, action: 'ficha.update'},
          ]
        }
      };
    },
    mounted() {
      this.get();
      axios.post('/get-history')
      .then(response => {
        this.causas = response.data.causa;
      });
    },
    methods: {
      get() {
        axios.get('/ficha/' + this.$route.params.id)
        .then(response => {
          this.data = response.data;
        });
      },
      openForm: function (cond, id) {
        this.cond = cond;
        if (cond == 'create') {
          this.form = {
            ficha_id: this.$route.params.id,
            state: '',
            state_date: '',
            cirujano: '',
            anestesiologo: '',
            sala_pabellon: '',
            anestesiologo: '',
            causa_id: '',
            observation: '',
          };
        } else if (cond == 'edit') {
          axios.get('/history/' + id)
          .then(response => {
            this.form = response.data;
          });
        }
        $('#history-form').modal('show');
      },
      register() {
        if (this.cond == 'create') {
          axios.post('/history', this.form)
          .then(response => {
            toastr.info('Registro exitoso');
            this.$children[0].get();
            $('#history-form').modal('hide');
          });
        } else {
          axios.put('/history/' + this.form.id, this.form)
          .then(response => {
            toastr.info('Actualización exitosa');
            this.$children[0].get();
            $('#history-form').modal('hide');
          });
        }
      },
    }
  }
</script>
