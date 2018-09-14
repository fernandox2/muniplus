<template>
  <div class="content">

  <div>
    <md-dialog :md-active.sync="showDialog" class="scrollbar">
      <md-dialog-title class="text-center">Datos de la relación <p>Asigna un horario y un departamento a un funcionario</p></md-dialog-title>

      <form novalidate class="md-layout" v-on:submit.prevent="newRelationship()">

        <div class="md-layout-item md-small-size-100 md-size-100">
          <div class="form-group">
             <label for="employee">Funcionario: </label><br>
             <select v-model="employee" class="form-control" v-on:change="">
             <option value="0">Seleccione una opción</option>
             <option v-for="option in employees" v-bind:value="option.id">
                 {{ option.nombre }}
             </option>
             </select>
             <NewEmployee @new="addEmployee"></NewEmployee>
          </div>
        </div>

        <div class="md-layout-item md-small-size-100 md-size-100">
          <div class="form-group">
             <label for="departament">Departamento: </label>
             <select v-model="departament" class="form-control" v-on:change="">
               <option value="0">Seleccione una opción</option>
               <option v-for="option in departaments" v-bind:value="option.id">
                   {{ option.nombre }}
               </option>            
             </select>
             <NewDepartament @new="addDepartament"></NewDepartament>
          </div>
        </div>

        <div class="md-layout-item md-small-size-100 md-size-100">
          <div class="form-group">
             <label for="schedule">Horario:</label>
             <select v-model="schedule" class="form-control" v-on:change="">
               <option value="0">Seleccione una opción</option>
               <option v-for="option in schedules" v-bind:value="option.id">
                   {{ option.nombre }}
               </option>
              </select>
              <NewSchedule @new="addSchedule"></NewSchedule>
          </div>
        </div>

        <div class="md-layout-item md-small-size-100 md-size-100">
          <div class="form-group">
            <label>¿Trabaja por turnos?:</label><br>
            <md-switch v-model="turn" class="md-primary"></md-switch>
          </div>
        </div>

          <md-button class="md-primary close" @click="showDialog = false">Cerrar</md-button>
          <md-button type="submit" class="md-raised md-success">Guardar</md-button>
        </form>
    </md-dialog>
  </div>
  
<md-button class="md-primary" @click="showDialog = true"> Nueva Relación</md-button>

  </div>
</template>

<script>
import NewSchedule  from '../Schedule/ScheduleNew.vue'
import NewDepartament  from '../Departament/DepartamentNew.vue'
import NewEmployee  from '../Employee/EmployeeNew.vue'
export default{
created(){
  axios.get('/employees').then(response => { this.employees = response.data; }).catch(errors => { console.log(errors); })
  axios.get('/schedules').then(response => { this.schedules = response.data; }).catch(errors => { console.log(errors); })
  axios.get('/departaments').then(response => { this.departaments = response.data; }).catch(errors => { console.log(errors); })
},
    data(){
      return {
          showDialog: false,
          employees:[],
          employee:0,
          schedules:[],
          schedule:0,
          departaments:[],
          departament:0,
          turn:false
        }
    },
  components: {
    NewSchedule,
    NewDepartament,
    NewEmployee
  },
      methods: {
        addEmployee(employee){

          this.employees.push(employee);
        },

        addDepartament(departament){

          this.departaments.push(departament);
        },

        addSchedule(schedule){

          this.schedules.push(schedule);

        },

        newRelationship(){

          const params = {
                employee:this.employee,
                departament: this.departament,
                schedule: this.schedule,
                turn: this.turn,
            };

            axios.post('/relationships',params).then((response) => {
                
                const relation = response.data;
                console.log(relation);
                if(relation.save)
                {
                  
                  this.employee = 0;
                  this.departament = 0;
                  this.schedule = 0;
                  this.turn = false;
                  this.showDialog = false;

                  this.$notify(
                    {
                      message: 'Se creo correctamente la nueva relación',
                      icon: 'done',
                      horizontalAlign: 'right',
                      verticalAlign: 'top',
                      type: 'success'
                    })

                    this.$emit('new', relation);

                }else{

                  this.$notify(
                    {
                      message: 'No se creó  la  relación, revise que: <br>' +
                                              '<ul><li>Todos los campos necesarios esten seleccionados correctamente.</li>' +
                                              '<li>No exista un funcionario relacionado con el departamento.</li>'+
                                              '<li>El funcionario ya tiene esas horas utilizadas en otro horario.</li></ul>',
                      icon: 'error',
                      horizontalAlign: 'right',
                      verticalAlign: 'top',
                      type: 'danger',
                    })
                }
                
            }).catch(errors => {
                    console.log(errors);
                });

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

.scrollbar{
  overflow: auto !important;
}

</style>