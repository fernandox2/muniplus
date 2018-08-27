<template>
  <div class="content">

  <div>
    <md-dialog :md-active.sync="showDialog">
      <md-dialog-title>Datos del Departamento</md-dialog-title>

      <form novalidate class="md-layout" v-on:submit.prevent="nuevoEmployee()">
          <div class="md-layout-item md-small-size-100 md-size-100">
            <md-field>
              <label>Nombre</label>
              <md-input v-model="nombre" type="text" required></md-input>
              <div class="error" v-if="!$v.nombre.required">Campo Obligatorio</div>
              <div class="error" v-if="!$v.nombre.minLength">El nombre debe tener a lo menos {{$v.nombre.$params.minLength.min}} letras.</div>
            </md-field>
          </div>

          <div class="md-layout-item md-small-size-100 md-size-100">
            <md-field>
              <FileUpload></FileUpload>
            </md-field>
          </div>

          <md-button class="md-primary close" @click="showDialog = false">Close</md-button>
          <md-button type="submit" class="md-raised md-success">Guardar</md-button>
        </form>
    </md-dialog>
  </div>

<md-button class="md-primary" @click="showDialog = true"><md-icon>business</md-icon> Nuevo Departamento</md-button>
  </div>
</template>

<script>
import { required, minLength } from 'vuelidate/lib/validators'
import FileUpload from '../FileUpload.vue'

export default{
    data(){
      return {
          showDialog: false,
          nombre:'',
          rut:'',
        }
    },
      validations: {
        nombre: {
          required,
          minLength: minLength(5)
        },
    },
      components: {
    FileUpload
  },
      methods: {
            onFileSelected (event) {
               const file = event.target.files[0];
               const formData = new FormData($("#my-form")[0]);
               //const formData = new FormData();
               //formData.append("my-file", file);
               Vue.http.post(`url-to-serve`, formData)
                .then(res => {
                    //todo ok
                },
                error => {
                    //todo mal :P
                })
            },
        nuevoEmployee(){
            if (!this.$v.$invalid) {
                const params = {
                nombre:this.nombre,
                rut: this.rut,
            };
            axios.post('/employees',params).then((response) => {
                const empleado = response.data;
                this.nombre = "";
                this.rut = "";
                this.correo = "";
                this.codigo = "";
                if(empleado.save)
                {
                  this.showDialog = false;
                  this.$notify(
                    {
                      message: 'Se creo correctamente el funcionario:<b> ' + empleado.nombre + '</b>',
                      icon: 'done',
                      horizontalAlign: 'right',
                      verticalAlign: 'top',
                      type: 'success'
                    })
                    this.$emit('new', empleado);
                }else{
                  this.$notify(
                    {
                      message: 'No se creo el funcionario. Revise los datos nuevamente',
                      icon: 'error',
                      horizontalAlign: 'right',
                      verticalAlign: 'top',
                      type: 'danger'
                    })
                }
                
            }).catch(errors => {
                    console.log(errors);
                });
            }else{
                  this.$notify(
                    {
                      message: 'Error en la validación del formulario. Revise los datos e intente nuevamente',
                      icon: 'error',
                      horizontalAlign: 'right',
                      verticalAlign: 'top',
                      type: 'danger'
                    })
            }

            }
        },
};
</script>

<style lang="scss" scoped>
  .md-layout {
    padding-left: 20px;
    padding-right: 20px; 
  }
  .md-dialog-actions{
        padding-right: 20px; 
  }

  .error{
    color: red !important;
  }
</style>