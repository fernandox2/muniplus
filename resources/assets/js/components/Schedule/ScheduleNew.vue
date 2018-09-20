<template>
  <div class="newSchedule">
    <md-dialog :md-active.sync="showDialog">
      <md-dialog-title class="text-center">Datos del Horario <p>Formato 24 Hrs.</p></md-dialog-title>
      <form novalidate class="md-layout">
          <div class="md-layout-item md-small-size-100 md-size-100">
            <md-field>
              <label>Nombre del Horario</label>
              <md-input v-model="nombre" type="text" required></md-input>
              <div class="error" v-if="!$v.nombre.minLength">El nombre debe tener a lo menos {{$v.nombre.$params.minLength.min}} letras.</div>
            </md-field>
          </div>

          <div class="md-layout md-gutter">
            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Lunes</label>
              <md-chips  md-input-type="text" v-model="HorarioLunes" :md-format="validarHora" :md-limit="4"></md-chips>
            </div>
            </div>

            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Martes</label>
              <md-chips  md-input-type="text" v-model="HorarioMartes" :md-format="validarHora" :md-limit="4"></md-chips>
            </div>
            </div>
          </div>
          <div class="md-layout md-gutter">
            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Miércoles</label>
              <md-chips  md-input-type="text" v-model="HorarioMiercoles" :md-format="validarHora" :md-limit="4"></md-chips>
            </div>
            </div>

            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Jueves</label>
              <md-chips  md-input-type="text" v-model="HorarioJueves" :md-format="validarHora" :md-limit="4"></md-chips>
            </div>
            </div>
          </div>

          <div class="md-layout md-gutter">
            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Viernes</label>
              <md-chips  md-input-type="text" v-model="HorarioViernes" :md-format="validarHora" :md-limit="4"></md-chips>
            </div>
            </div>

            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Sábado</label>
              <md-chips md-input-type="text" v-model="HorarioSabado" :md-format="validarHora" :md-limit="4"></md-chips>
            </div>
            </div>
          </div>

          <div class="md-layout-item md-small-size-100 md-size-100">
            <div>
              <label>Domingo</label>
              <md-chips md-input-type="text" v-model="HorarioDomingo" :md-format="validarHora" :md-limit="4"></md-chips>
            </div>
          </div>

          <md-button class="md-primary" @click="showDialog = false">Cerrar</md-button>
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
          todos:[]
        }
    },
      validations: {
        nombre: {
          required,
          minLength: minLength(5)
        },
    },
      methods: {
        isNumber(num){
          return !isNaN(parseFloat(num)) && isFinite(num);
        },
        validarHora(tiempo){

          var hora = tiempo.split(":");
          var h = hora[0];
          var m = hora[1];

          if(this.isNumber(h) && this.isNumber(m) && h < 24 && m < 60)            
            return tiempo;
        },
        newSchedule(){
            if (!this.$v.$invalid) {
              // Añadir Todos Los Horarios A Un Array
              this.todos=[
                this.HorarioLunes.sort(),
                this.HorarioMartes.sort(),
                this.HorarioMiercoles.sort(),
                this.HorarioJueves.sort(),
                this.HorarioViernes.sort(),
                this.HorarioSabado.sort(),
                this.HorarioDomingo.sort()
              ];
                const params = {
                nombre:this.nombre,
                HorarioLunes: this.HorarioLunes.sort(),
                HorarioMartes: this.HorarioMartes.sort(),
                HorarioMiercoles: this.HorarioMiercoles.sort(),
                HorarioJueves: this.HorarioJueves.sort(),
                HorarioViernes: this.HorarioViernes.sort(),
                HorarioSabado: this.HorarioSabado.sort(),
                HorarioDomingo: this.HorarioDomingo.sort(),
                todos: this.todos
            };
            axios.post('/schedules',params).then((response) => {
                const schedule = response.data;
                if(schedule.save)
                {
                    this.nombre = "";
                    this.HorarioLunes = []; 
                    this.HorarioMartes = [];
                    this.HorarioMiercoles = [];
                    this.HorarioJueves = [];
                    this.HorarioViernes = [];
                    this.HorarioSabado = [];
                    this.HorarioDomingo = [];
                    this.todos = [];
                  this.showDialog = false;
                  this.$notify(
                    {
                      message: 'Se creo correctamente el horario <b>' + schedule.nombre,
                      icon: 'done',
                      horizontalAlign: 'right',
                      verticalAlign: 'top',
                      type: 'success'
                    })
                    this.$emit('new', schedule);
                }else{
                  this.$notify(
                    {
                      message: 'No se creo el horario. Revise los datos nuevamente',
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

  form{
    overflow: auto !important;
  }
</style>