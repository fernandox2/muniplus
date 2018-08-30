<template>
  <div class="newSchedule">
    <md-dialog :md-active.sync="showDialog">
      <md-dialog-title>Datos del Horario</md-dialog-title>
      <form novalidate class="md-layout">
          <div class="md-layout-item md-small-size-100 md-size-100">
            <md-field>
              <label>Nombre del Horario</label>
              <md-input v-model="nombre" type="text" required></md-input>
              <div class="error" v-if="!$v.nombre.required">Campo Obligatorio</div>
              <div class="error" v-if="!$v.nombre.minLength">El nombre debe tener a lo menos {{$v.nombre.$params.minLength.min}} letras.</div>
            </md-field>
          </div>

          <div class="md-layout md-gutter">
            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Lunes</label>
              <md-chips  md-input-type="time" v-model="HorarioLunes" md-placeholder="Agrega horario" md-limit="4"></md-chips>
            </div>
            </div>

            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Martes</label>
              <md-chips  md-input-type="time" v-model="HorarioMartes" md-placeholder="Agrega horario" md-limit="4"></md-chips>
            </div>
            </div>
          </div>


          <div class="md-layout md-gutter">
            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Miércoles</label>
              <md-chips  md-input-type="time" v-model="HorarioMiercoles" md-placeholder="Agrega horario" md-limit="4"></md-chips>
            </div>
            </div>

            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Jueves</label>
              <md-chips  md-input-type="time" v-model="HorarioJueves" md-placeholder="Agrega horario" md-limit="4"></md-chips>
            </div>
            </div>
          </div>

          <div class="md-layout md-gutter">
            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Viernes</label>
              <md-chips  md-input-type="time" v-model="HorarioViernes" md-placeholder="Agrega horario" md-limit="4"></md-chips>
            </div>
            </div>

            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Sábado</label>
              <md-chips md-input-type="time" v-model="HorarioSabado" md-placeholder="Agrega horario" md-limit="4"></md-chips>
            </div>
            </div>
          </div>

          <div class="md-layout-item md-small-size-100 md-size-100">
            <div>
              <label>Domingo</label>
              <md-chips md-input-type="time" v-model="HorarioDomingo" md-placeholder="Agrega horario" md-limit="4"></md-chips>
            </div>
          </div>

          <md-button class="md-primary close" @click="showDialog = false">Close</md-button>
          <md-button type="button" @click="newSchedule" class="md-raised md-success">Guardar</md-button>
        </form>
    </md-dialog>
    <md-button class="md-primary" @click="showDialog = true"> Nuevo Horario</md-button>

  </div>
</template>

<script>
import { required, minLength, between, email } from 'vuelidate/lib/validators';

export default{
    data(){
      return {
          showDialog: false,
          nombre:'',
          HorarioLunes:[],
          HorarioMartes:[],
          HorarioMiercoles:[],
          HorarioJueves:[],
          HorarioViernes:[],
          HorarioSabado:[],
          HorarioDomingo:[],
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
        newSchedule(){
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

  .newSchedule{
    height:100% !important;
  }
</style>