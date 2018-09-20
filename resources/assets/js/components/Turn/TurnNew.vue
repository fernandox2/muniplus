<template>
  <div class="content">

  <div>
    <md-dialog :md-active.sync="showDialog">
      <md-dialog-title>Datos del Turno</md-dialog-title>

      <form novalidate class="md-layout" v-on:submit.prevent="newTurn()">
          
        <div class="md-layout-item md-small-size-100 md-size-100">
          <div class="form-group">
             <label for="relationship">Relación Existente: </label><br>
             <select v-model="relationship" class="form-control" v-on:change="">
             <option value="0">Seleccione una opción</option>
             <option v-for="option in relationships" v-bind:value="option.id">
                 {{ option.nameEmployee }} - {{ option.nameDepartament }}
             </option>
             </select>
          </div>
        </div>

        <div class="md-layout-item md-small-size-100 md-size-100">
          <div class="form-group">
             <label for="schedule">Horario: </label><br>
             <select v-model="schedule" class="form-control" v-on:change="">
             <option value="0">Seleccione una opción</option>
             <option v-for="option in schedules" v-bind:value="option.id">
                 {{ option.nombre }}
             </option>
             </select>
          </div>
        </div>

          <div class="md-layout-item md-small-size-100 md-size-100">
            <label for="inicio">Fecha Inicio</label>
            <md-field>
              <md-input v-model="inicio" type="date"></md-input>
            </md-field>
          </div>

          <div class="md-layout-item md-small-size-100 md-size-100">
            <label for="fin">Fecha Fin</label>
            <md-field>
              <md-input v-model="fin" type="date"></md-input>
            </md-field>
          </div>

          <md-button class="md-primary" @click="showDialog = false">Cerrar</md-button>
          <md-button type="submit" class="md-success">Guardar</md-button>

        </form>
    </md-dialog>
  </div>
  
<md-button class="md-primary" @click="showDialog = true"> Nuevo Turno </md-button>

  </div>
</template>

<script>


export default{
  created(){
    axios.get('/RelacionesConTurnos').then(response => { this.relationships = response.data; }).catch(errors => { console.log(errors); })
    axios.get('/schedules').then(response => { this.schedules = response.data; }).catch(errors => { console.log(errors); })
  },
    data(){
      return {
          showDialog: false,
          schedules:[],
          schedule:0,
          relationships:[],
          relationship:0,
          inicio:null,
          fin:null
        }
    },
      methods: {
        newTurn(){

            const params = {
              schedule:this.schedule,
              relationship: this.relationship,
              inicio: this.inicio,
              fin: this.fin,
            };

            axios.post('/turns',params).then((response) => {
                
                const turno = response.data;

                if(turno.save)
                {
                  this.showDialog = false;
                  this.inicio = null;
                  this.fin = null;
                  this.relationship = 0;
                  this.schedule = 0;

                  this.$notify(
                    {
                      message: 'Se creo correctamente el nuevo turno',
                      icon: 'done',
                      horizontalAlign: 'right',
                      verticalAlign: 'top',
                      type: 'success'
                    })
                    this.$emit('new', turno);
                }else{

                  this.$notify(
                    {
                      message: 'No se creo el turno. Revise los datos e intente nuevamente',
                      icon: 'error',
                      horizontalAlign: 'right',
                      verticalAlign: 'top',
                      type: 'danger'
                    })
                }
                
            }).catch(errors => {
                    console.log(errors);
                });


            },

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