<template>
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Valoración Pre-operatoria</h3>
    </div>
    <div class="box-body">
      <div class="col-md-12">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li :class="(show == p.id) ? 'active' : ''" v-for="p in procedures"><a :href="'#check'+p.id" data-toggle="tab" aria-expanded="true">{{ p.name }} <i :class="'fa fa-' + ((p.state) ? 'check bg-success' : 'close bg-danger')"></i></a></li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane active" id="check1">
              <form class="form-horizontal">
                <div class="box-body">
                  <ul class="todo-list ui-sortable">
                    <li v-for="f in ficha_procedures" v-if="f.procedure_id == 1">
                      <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <input type="checkbox" v-model="f.state">
                      <span class="text" v-text="f.name"></span>
                      <div class="pull-right">
                        <button type="button" class="btn btn-danger btn-xs" @click="deleted('/procedure/'+f.id, get, 'name')">
                          <span class="fa fa-close"></span>
                        </button>
                        <span class="text">N/A</span>
                      </div>
                    </li>
                    <li>
                      <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <span class="text">Observación</span><br>
                      <input type="text" class="form-control" v-model="ficha.observation_pre_operatorio">
                    </li>
                    <li>
                      <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <span class="text">Otro</span><br>
                      <span class="fa fa-pencil"></span>
                      <label>Especifique el procedimiento y presione enter.</label>
                      <input type="text" class="form-control" placeholder="Enter ..." v-model="procedure" @keypress.enter.prevent="register(1)">
                      <button type="button" class="btn btn-primary" @click="register(1)">
                        <i class="fa fa-plus"></i>
                      </button>
                    </li>
                  </ul>
                </div>
                <div class="box-footer clearfix">
                  <button type="button" class="btn btn-primary btn-flat pull-right" @click="save"><i class="fa fa-plus"></i> Guardar</button>
                </div>
              </form>
            </div>

            <div class="tab-pane" id="check2">
             <form class="form-horizontal">
              <div class="box-body">
                <ul class="todo-list ui-sortable">
                  <li v-for="f in ficha_procedures" v-if="f.procedure_id == 2">
                    <span class="handle ui-sortable-handle">
                      <i class="fa fa-ellipsis-v"></i>
                      <i class="fa fa-ellipsis-v"></i>
                    </span>
                    <input type="checkbox" v-model="f.state">
                    <span class="text" v-text="f.name"></span>
                    <div class="pull-right">
                      <button type="button" class="btn btn-danger btn-xs" @click="deleted('/procedure/'+f.id, get, 'name')">
                        <span class="fa fa-close"></span>
                      </button>
                      <span class="text">N/A</span>
                    </div>
                  </li>
                  <li>
                    <span class="handle ui-sortable-handle">
                      <i class="fa fa-ellipsis-v"></i>
                      <i class="fa fa-ellipsis-v"></i>
                    </span>
                    <span class="text">Observación</span><br>
                    <input type="text" class="form-control" v-model="ficha.observation_interconsultas">
                  </li>
                  <li>
                    <span class="handle ui-sortable-handle">
                      <i class="fa fa-ellipsis-v"></i>
                      <i class="fa fa-ellipsis-v"></i>
                    </span>
                    <span class="text">Otro</span><br>
                    <span class="fa fa-pencil"></span>
                    <label>Especifique el procedimiento y presione enter.</label>
                    <input type="text" class="form-control" placeholder="Enter..." v-model="procedure" @keypress.enter.prevent="register(2)">
                    <button type="button" class="btn btn-primary" @click="register(2)">
                        <i class="fa fa-plus"></i>
                      </button>
                  </li>
                </ul>
              </div>
              <div class="box-footer clearfix">
                <button type="button" class="btn btn-primary btn-flat pull-right" @click="save"><i class="fa fa-plus"></i> Guardar</button>
              </div>
            </form>
          </div>

          <div class="tab-pane" id="check3">
            <form class="form-horizontal">
              <div class="box-body">
                <ul class="todo-list ui-sortable">
                  <li v-for="f in pre_anestesia">
                    <span class="handle ui-sortable-handle">
                      <i class="fa fa-ellipsis-v"></i>
                      <i class="fa fa-ellipsis-v"></i>
                    </span>
                    <input type="radio" name="consulta_pre_anestesia_id" :value="f.id" v-model="ficha.consulta_pre_anestesia_id">
                    <span class="text" v-text="f.name"></span>
                  </li>
                  <li>
                    <span class="handle ui-sortable-handle">
                      <i class="fa fa-ellipsis-v"></i>
                      <i class="fa fa-ellipsis-v"></i>
                    </span>
                    <input type="checkbox" v-model="ficha.cirugia_emergencia">
                    <span class="text">Cirugia de emergencia (e)</span>
                  </li>
                  <li>
                    <span class="handle ui-sortable-handle">
                      <i class="fa fa-ellipsis-v"></i>
                      <i class="fa fa-ellipsis-v"></i>
                    </span>
                    <span class="text">Apto para cirugía</span>
                    <br>
                    <input type="radio" v-model="ficha.apto" value="si">Si
                    <input type="radio" v-model="ficha.apto" value="no">No
                  </li>
                  <li>
                    <span class="handle ui-sortable-handle">
                      <i class="fa fa-ellipsis-v"></i>
                      <i class="fa fa-ellipsis-v"></i>
                    </span>
                    <span class="text">Observación</span><br>
                    <input type="text" class="form-control" v-model="ficha.observation_riesgo_anestesico">
                  </li>
                  <!-- <li>
                    <span class="handle ui-sortable-handle">
                      <i class="fa fa-ellipsis-v"></i>
                      <i class="fa fa-ellipsis-v"></i>
                    </span>
                    <span class="text">Otro</span><br>
                    <span class="fa fa-pencil"></span>
                    <label>Especifique el procedimiento y presione enter.</label>
                    <input type="text" class="form-control" placeholder="Enter ..." v-model="procedure" @keypress.enter.prevent="register(3)">
                  </li> -->
                </ul>
              </div>
              <div class="box-footer clearfix">
                <button type="button" class="btn btn-primary btn-flat pull-right" @click="save"><i class="fa fa-plus"></i> Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
  export default {
    name: 'Procedures',
    data() {
      return {
        id: null,
        show: 1,
        ficha: [],
        procedure: '',
        procedures: [],
        ficha_procedures: [],
        pre_anestesia: [],
        data: {},
      };
    },
    mounted() {
      this.get();
    },
    methods: {
      get() {
        axios.post('/get-procedures', { id: this.$route.params.id })
        .then(response => {
          this.pre_anestesia = response.data.subprocedures;
          this.procedures = response.data.procedures;
          this.ficha = response.data.ficha;
          this.ficha_procedures = response.data.ficha_procedures;
          if (this.ficha_procedures.length == 0) this.$router.push({name: 'ficha.index'});
        });
      },
      register(procedure_id) {
        axios.post('/procedure', {
          procedure: this.procedure,
          id: this.$route.params.id,
          procedure_id: procedure_id
        })
        .then(response => {
          toastr.success('Registro Exitoso');
          this.procedure = '';
          this.get();
        });
      },
      save() {
        let data = [];
        axios.put('/procedure/' + this.$route.params.id, {
          ficha_procedures: this.ficha_procedures,
          ficha: this.ficha
        })
        .then(response => {
          toastr.success('Datos Guardados');
          this.$router.push({name: 'ficha.index'});
        });
      }
    }
  }
</script>
