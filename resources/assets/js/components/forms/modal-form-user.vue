<template>
  <modal id="user-form" w="lg">

    <template slot="modal-title">
      <span :class="'glyphicon glyphicon-' + formData.ico"></span>
      {{ formData.title }}
    </template>

    <template slot="modal-body">
      <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
          <form class="form-horizontal" @keyup.enter="registrar">

            <spinner v-if="!formData.ready"></spinner>
            <div v-else>
              <div class="col-sm-5">
                <template v-for="input in entries.izq">
                  <v-input :name="input" required="true"
                          v-model="formData.user[input.id]"
                          :msg="msg[input.id]"
                          @input="formData.user[input.id] = arguments[0]">
                  </v-input>
                </template>
              </div>

              <div class="col-sm-5 col-sm-offset-2">
                <template v-for="input in entries.der">
                  <v-input :name="input" required="true"
                          type="password"
                          v-model="formData.user[input.id]"
                          :msg="msg[input.id]"
                          @input="formData.user[input.id] = arguments[0]">
                  </v-input>
                </template>

                <div class="form-group">
                  <label for="roles" class="control-label">
                    <span class="glyphicon glyphicon-compressed"></span> Roles:
                  </label>
                  <v-multiselect v-model="formData.user.roles" :options="roles" :multiple="true" :hide-selected="true" :close-on-select="false"></v-multiselect>
                  <small id="rolesHelp" class="form-text text-muted">
                    <span v-text="msg.roles"></span>
                  </small>
                </div>

                <div class="form-group">
                  <label for="speciality_id" class="control-label">
                    <span class="glyphicon glyphicon-compressed"></span> Especialidades:
                  </label>
                  <v-multiselect v-model="formData.user.specialities" :options="select2_options(specialties)" :multiple="true" :hide-selected="true" :close-on-select="false"></v-multiselect>
                  <small id="speciality_idHelp" class="form-text text-muted">
                    <span v-text="msg.speciality_id"></span>
                  </small>
                </div>


              </div>
            </div>

          </form>
        </div>
      </div>
    </template>

    <template slot="modal-btn">
      <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> Cerrar</button>
      <button type="button" class="btn btn-primary" @click="registrar"><span class="glyphicon glyphicon-saved"></span> Guardar</button>
    </template>

  </modal>
</template>

<script>
  import Modal from './../partials/modal.vue';
  import Input from './../partials/input.vue';
  import Multiselect from 'vue-multiselect';

  export default {
    name: 'modal-form-user',
    components: {
      'modal': Modal,
      'v-input': Input,
      'v-multiselect': Multiselect,
    },
    props: ['formData'],
    data () {
      return {
        entries: {
          izq: [
          {label: 'Nombre', id: 'name', icon: 'fa fa-user'},
          {label: 'Apellido', id: 'last_name', icon: 'fa fa-user-o'},
          {label: 'Cédula', id: 'num_id', icon: 'fa fa-id-card-o'},
          {label: 'E-Mail', id: 'email', icon: 'fa fa-envelope'},
          ],
          der: [
          {label: 'Contraseña', id: 'password', icon: 'fa fa-lock'},
          {label: 'Confirmación de Contraseña', id: 'password_confirmation', icon: 'fa fa-lock'},
          ],
        },
        modules: [],
        roles: [],
        specialties: [],
        msg: {
          user: 'Usuario unico.',
          name: 'Nombre del usuario.',
          last_name: 'Apellido del usuario.',
          num_id: 'Cedula de identidad.',
          email: 'Correo electronico.',
          password: 'Contraseña.',
          password_confirmation: 'Confirmación de Contraseña.',
          roles: 'Rol a desempeñar.',
          specialities: 'Seleccione las Especialidades'
        }
      };
    },
    mounted: function () {
      axios.post('/admin/get-data-users')
      .then(response => {
        this.roles = response.data.roles;
        this.specialties = response.data.specialties;
      });
    },
    methods: {
      registrar: function (el) {
        this.restoreMsg(this.msg);
        this.formData.user.speciality_id = this.select2_search(this.specialties, this.formData.user.specialities);
        if (this.formData.cond === 'create') {
          axios.post(this.formData.url, this.formData.user)
          .then(response => {
            toastr.success('Registro Creado');
            this.$emit('input');
            $('#user-form').modal('hide');
          });
        } else {
          axios.put(this.formData.url, this.formData.user)
          .then(response => {
            toastr.success('Registro Editado');
            this.$emit('input');
            $('#user-form').modal('hide');
          });
        }
      }
    }
  }
</script>