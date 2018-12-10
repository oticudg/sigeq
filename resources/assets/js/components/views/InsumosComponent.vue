<template>
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Registro de Insumos Requeridos:</h3>
      <button type="button"
      class="btn btn-default btn-xs"
      data-tooltip="tooltip"
      title="Registrar Insumo"
      @click="openForm('create')"><span class="glyphicon glyphicon-plus"></span></button>
    </div>
    <div class="box-body" v-if="form.ready">
      <h3>{{ form.title }}</h3>
      <div class="row">
        <div class="col-md-12">
          <div class="col-md-6 col-md-offset-1">
            <div class="form-group">
              <label for="insumo_id" class="control-label">
                <span class="fa fa-calendar"></span> Insumo:
              </label>
              <v-multiselect v-model="insumo" :options="insumos_options" :close-on-select="true"></v-multiselect>
              <small id="insumo_idHelp" class="form-text text-muted">
                <span v-text="msg.insumo_id"></span>
              </small>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label for="cant" class="control-label">
                <span class="fa fa-calendar"></span> Cantidad:
              </label>
              <input type="text" class="form-control" v-model="form.data.cant">
              <small id="cantHelp" class="form-text text-muted">
                <span v-text="msg.cant"></span>
              </small>
            </div>
          </div>
          <div class="col-md-2" style="padding-top: 28px;">
            <button type="button" class="btn btn-primary" data-tooltip="tooltip" title="Guardar" @click="register"><i class="glyphicon glyphicon-saved"></i></button>
            <button type="button" class="btn btn-danger" data-tooltip="tooltip" title="Cancelar" @click="openForm"><i class="fa fa-close"></i></button>
          </div>
        </div>
        <div class="col-md-12">
          <div class="col-md-4">
            <div class="form-group">
              <label for="state" class="control-label">
                <span class="fa fa-calendar"></span> Entregado:
              </label>
              <select id="state" class="form-control" v-model="form.data.state">
                <option value="0">No</option>
                <option value="1">Si</option>
              </select>
              <small id="stateHelp" class="form-text text-muted">
                <span v-text="msg.state"></span>
              </small>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="origen" class="control-label">
                <span class="fa fa-calendar"></span> origen:
              </label>
              <select id="origen" class="form-control" v-model="form.data.origen">
                <option value="">Seleccione un origen</option>
                <option value="1">PARTICULAR</option>
                <option value="2">DONACION</option>
                <option value="3">INTITUCIONAL</option>
                <option value="4">OTRO</option>
              </select>
              <!-- <input type="text"> -->
              <small id="origenHelp" class="form-text text-muted">
                <span v-text="msg.origen"></span>
              </small>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="observation" class="control-label">
                <span class="fa fa-calendar"></span> Observación:
              </label>
              <input type="text" class="form-control" v-model="form.data.observation">
              <small id="observationHelp" class="form-text text-muted">
                <span v-text="msg.observation"></span>
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="box-body">
      <v-table id="insumos" :tabla="tabla" :uri="'/insumo?id=' + this.$route.params.id" @output="id = arguments[0]"></v-table>
    </div>
    <div class="box-footer">
      <div class="col-md-6">Insumos Completados: <b>{{ (state) ? 'Si':'No' }}.</b></div>
      <div class="col-md-6" v-if="ficha">Fecha: <b>{{ ficha.date_insumo }}.</b></div>
    </div>
  </div>
</template>

<script>
  import Tabla from './../partials/table.vue';
  import Multiselect from 'vue-multiselect';

  export default {
    name: 'Insumos',
    components: {
      'v-table': Tabla,
      'v-multiselect': Multiselect,
    },
    data() {
      return {
        ficha: null,
        state: null,
        insumo: '',
        insumos: [],
        insumos_options: [],
        form: {
          ready: false,
          title: '',
          cond: '',
          data: {},
        },
        msg: {},
        tabla: {
          columns: [
          { title: 'Insumo', field: 'insumo_id', sortable: true },
          { title: 'Cantidad Requerida', field: 'cant', sortable: true },
          { title: 'Origen', field: 'origen', sortable: true },
          { title: 'Observación', field: 'observation', sortable: true },
          { title: 'Entregado', field: 'state', sortable: true },
          ],
          options: [
          { ico: 'fa fa-edit', class: 'btn-info', title: 'Editar Insumo', func: (id) => {
            this.openForm('edit', id);
          }, action: 'insumo.update'},
          { ico: 'fa fa-close', class: 'btn-danger', title: 'Borrar Insumo', func: (id) => {
            this.deleted('/insumo/'+id, this.$children, 'insumo'); }, action: 'insumo.destroy'},
            ]
          }
        };
      },
      watch: {
        insumo: function (val) {
          for(let i in this.insumos) {
            if (this.insumos[i].name == val) {
              this.form.data.insumo_id = this.insumos[i].id;
              return;
            }
          }
        },
      },
      mounted() {
        this.get();
      },
      methods: {
        get: function () {
          axios.post('/get-insumo', { id: this.$route.params.id })
          .then(response => {
            this.$children[0].get();
            this.ficha = response.data.ficha;
            this.state = response.data.state
            this.insumos = response.data.insumos;
            for(let i in this.insumos) {
              this.insumos_options.push(this.insumos[i].name);
            }
          });
        },
        openForm: function (cond, id) {
          this.form.cond = cond;
          this.form.ready = false;
          if (cond == 'create') {
            this.form.ready = true;
            this.form.title = 'Registrar Insumo';
            this.insumo = '';
            this.form.data = {
              insumo_id: '',
              ficha_id: this.$route.params.id,
              cant: '',
              origen: '',
              observation: '',
              state: 0,
            };
          } else if (cond == 'edit') {
            axios.get('/insumo/' + id)
            .then(response => {
              this.form.title = 'Editar Insumo: ' + response.data.insumo;
              this.insumo = response.data.insumo;
              this.form.data = response.data;
              this.form.ready = true;
            });
          } else {this.form.ready = false;}
          $('[data-tooltip="tooltip"]').tooltip();
        },
        register: function () {
          if (this.form.cond == 'create') {
            axios.post('/insumo', this.form.data)
            .then(response => {
              this.form.ready = false;
              toastr.info('Registro Guardado');
              this.$children[0].get();
              this.get();
            });
          } else {
            axios.put('/insumo/' + this.form.data.id, this.form.data)
            .then(response => {
              this.$children[0].get();
              toastr.info('Registro Guardado');
              this.form.ready = false;
              this.get();
            });
          }
        }
      }
    }
  </script>
