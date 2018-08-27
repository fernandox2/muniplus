<template>
  <div class="content">

  <div>
    <md-dialog :md-active.sync="showDialog">
      <md-dialog-title>Datos del Funcionario</md-dialog-title>

      <form novalidate class="md-layout" v-on:submit.prevent="nuevoEmployee()">
          <div class="md-layout-item md-small-size-100 md-size-100">
            <md-field>
              <label>Nombre Completo</label>
              <md-input v-model="nombre" type="text" required></md-input>
              <div class="error" v-if="!$v.nombre.required">Campo Obligatorio</div>
              <div class="error" v-if="!$v.nombre.minLength">El nombre debe tener a lo menos {{$v.nombre.$params.minLength.min}} letras.</div>
            </md-field>
          </div>

          <div class="md-layout-item md-small-size-100 md-size-100">
            <md-field>
              <label>RUT</label>
              <md-input v-model="rut" type="text" oninput="checkRut(this)"></md-input>
              <div class="error" v-if="!$v.rut.required">Campo Obligatorio</div>
              <div class="error" v-if="!$v.rut.minLength">El nombre debe tener a lo menos {{$v.rut.$params.minLength.min}} letras.</div>
            </md-field>
          </div>

          <div class="md-layout-item md-small-size-100 md-size-100">
            <md-field>
              <label>Correo Electrónico</label>
              <md-input v-model="correo" type="email"></md-input>
            <span class="error" v-if="!$v.correo.required">Campo requerido</span>
            <span class="error" v-else-if="!$v.correo.email">Correo inválido</span>
            </md-field>
          </div>

          <div class="md-layout-item md-small-size-100 md-size-100">
            <md-field>
              <label>Código</label>
              <md-input v-model="codigo" type="email"></md-input>
              <div class="error" v-if="!$v.codigo.required">Campo Obligatorio</div>
              <div class="error" v-if="!$v.codigo.between">Sólo debe contener números</div>
            </md-field>
          </div>
          <md-button class="md-primary close" @click="showDialog = false">Close</md-button>
          <md-button type="submit" class="md-raised md-success">Guardar</md-button>
        </form>
    </md-dialog>
  </div>
  
<md-button class="md-primary" @click="showDialog = true"> Nuevo Empleado</md-button>

  </div>
</template>

<script>
import { required, minLength, between, email } from 'vuelidate/lib/validators';

export default{
    data(){
      return {
          showDialog: false,
          nombre:'',
          rut:'',
          correo:'',
          codigo:'',
        }
    },
      validations: {
        nombre: {
          required,
          minLength: minLength(10)
        },
        rut: {
          required,
          minLength: minLength(3)
        },
        codigo: {
          required,
          between: between(1, 9999999999)
        },
        correo: {
          required,
          email
        }
    },
      methods: {
        nuevoEmployee(){
            if (!this.$v.$invalid) {
                const params = {
                nombre:this.nombre,
                rut: this.rut,
                correo: this.correo,
                codigo: this.codigo,
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