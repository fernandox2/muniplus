<template>

  <div class="content">

    <div class="relationship">

        <div class="md-layout">

          <div class="md-layout-item md-medium-size-50 md-small-size-100 md-xsmall-size-100"><nuevo @new="addRelationship"></nuevo></div>

          <div class="md-layout-item md-medium-size-50 md-small-size-100 md-xsmall-size-100">
            
            <md-field class="largo pull-right">
              <md-select v-model="length" @change="resetPagination()">
                <md-option value="5">5</md-option>
                <md-option value="10">10</md-option>
                <md-option value="20">20</md-option>
                <md-option value="30">30</md-option>
              </md-select>
            </md-field>

            <md-field class="buscar pull-right">
              <label>Busca Aquí</label>
              <md-input v-model="search" v-on:change="resetPagination"></md-input>
            </md-field>

          </div>

        </div>

      <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">

        <TableRelationship
          v-for="(relationship, index) in paginated"
          :key="relationship.id"
          :relationship="relationship"
          @delete="deleterelationship(relationship)"
          @actualizar="actualizarrelationship(relationship)">
        </TableRelationship>

      </datatable>

      <br>

      <pagination :pagination="pagination" :client="true" :filtered="filteredProjects"
      @prev="--pagination.currentPage"
      @next="++pagination.currentPage">
      </pagination>

    <md-dialog :md-active.sync="showDialog" class="scrollbar">
      <md-dialog-title class="text-center">Datos de la relación <p>Asigna un horario y un departamento a un funcionario</p></md-dialog-title>

      <form novalidate class="md-layout" v-on:submit.prevent="editrelationship()">

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


          <md-button class="md-primary" @click="showDialog = false">Cerrar</md-button>
          <md-button type="submit" class="md-raised md-success">Guardar</md-button>
        </form>
    </md-dialog>

    </div>

  </div>

</template>

<script>

import Datatable from '../Datatable.vue';
import Pagination from '../Pagination.vue';
import { TableRelationship, NewRelationship, OrderedTable, NewSchedule, NewDepartament, NewEmployee } from '@/components';

export default{
  name: 'Relationship',
  
  created(){
    this.getrelationships();
    axios.get('/employees').then(response => { this.employees = response.data; }).catch(errors => { console.log(errors); })
    axios.get('/schedules').then(response => { this.schedules = response.data; }).catch(errors => { console.log(errors); })
    axios.get('/departaments').then(response => { this.departaments = response.data; }).catch(errors => { console.log(errors); })
  },
  
  data(){

    let sortOrders = {};

    let columns = [
    {width: '30%', label: 'Funcionario', name: 'nameEmployee'},
    {width: '30%', label: 'Departamento', name: 'nameDepartament'},
    {width: '30%', label: 'Horario', name: 'nameSchedule'},
    {width: '5%', label: 'Turno', name: 'turn'},
    {width: '5%', label: 'Acciones', name: 'acciones'}];

    columns.forEach((column) => {
       sortOrders[column.name] = -1;
    });

    return {

    relationships:[],
    relationship:'',
    columns: columns,
    sortKey: '',
    sortOrders: sortOrders,
    length: 5,
    search: '',
    showDialog: false,
    id:0,
    employees:[],
    employee:0,
    schedules:[],
    schedule:0,
    departaments:[],
    departament:0,
    turn:false,
    tableData: { client: true },
    pagination: { currentPage: 1, total: '', nextPage: '', prevPage: '', from: '', to: '' }  

    }

  },
  
  components: {

    OrderedTable,
    TableRelationship,
    datatable: Datatable, 
    pagination: Pagination,
    nuevo: NewRelationship,
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

    addRelationship(relation) {

      this.relationships.push(relation);
      this.getrelationships();

    },

    deleterelationship(relationship){

      var index = this.relationships.indexOf(relationship);
      this.relationships.splice(index, 1);

    },

    actualizarrelationship(relationship){

      this.relationship = relationship;

      this.employee = relationship.employee_id;
      this.departament = relationship.departament_id;
      this.schedule = relationship.schedule_id;
      if(relationship.turn == 1){
        this.turn = "on";
      }
      if(relationship.turn == 0){
        this.turn = "off";
      }    
      this.id = relationship.id;

      this.showDialog = true;
    
    },

    editrelationship(){

      const params = {
        id: this.id,
        employee:this.employee,
        departament: this.departament,
        schedule: this.schedule,
        turn: this.turn,
        };

        var index = this.relationships.indexOf(this.relationship);

        axios.put(`/relationships/${this.id}`, params).then((response) => {
              
              this.relationship = response.data;

              console.log(this.relationship);
              
              this.relationships[index] = this.relationship;
              
              this.getrelationships();
              
              this.id = 0;
              this.employee = 0;
              this.departament = 0;
              this.schedule = 0;
              this.turn = false;
              
              this.showDialog = false;
              
              if(this.relationship.save){
                
                this.$notify(
                  {
                    message: 'El registro fue modificado correctamente', 
                    icon: 'done',
                    horizontalAlign: 'right',
                    verticalAlign: 'top',
                    type: 'success'
                  })
              
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
                    type: 'danger'
                  })
              }

          });
        },

        getrelationships(url = '/relationships') {
                axios.get(url, {params: this.tableData})
                    .then(response => {
                        this.relationships = response.data;
                        this.pagination.total = this.relationships.length;
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
        paginate(array, length, pageNumber) {
                this.pagination.from = array.length ? ((pageNumber - 1) * length) + 1 : ' ';
                this.pagination.to = pageNumber * length > array.length ? array.length : pageNumber * length;
                this.pagination.prevPage = pageNumber > 1 ? pageNumber : '';
                this.pagination.nextPage = array.length > this.pagination.to ? pageNumber + 1 : '';
                return array.slice((pageNumber - 1) * length, pageNumber * length);
            },
        resetPagination() {
                this.pagination.currentPage = 1;
                this.pagination.prevPage = '';
                this.pagination.nextPage = '';
            },
        sortBy(key) {
                this.resetPagination();
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
            },
        getIndex(array, key, value) {
                return array.findIndex(i => i[key] == value);
            },

        },
    computed: {
        filteredProjects() {
            let relationships = this.relationships;
            if (this.search) {
                relationships = relationships.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                    })
                });
            }
            let sortKey = this.sortKey;
            let order = this.sortOrders[sortKey] || 1;
            if (sortKey) {
                relationships = relationships.slice().sort((a, b) => {
                    let index = this.getIndex(this.columns, 'name', sortKey);
                    a = String(a[sortKey]).toLowerCase();
                    b = String(b[sortKey]).toLowerCase();
                    if (this.columns[index].type && this.columns[index].type === 'date') {
                        return (a === b ? 0 : new Date(a).getTime() > new Date(b).getTime() ? 1 : -1) * order;
                    } else if (this.columns[index].type && this.columns[index].type === 'number') {
                        return (+a === +b ? 0 : +a > +b ? 1 : -1) * order;
                    } else {
                        return (a === b ? 0 : a > b ? 1 : -1) * order;
                    }
                });
            }
            return relationships;
        },
        paginated() {
            return this.paginate(this.filteredProjects, this.length, this.pagination.currentPage);
        }
    }
};
</script>

<style lang="scss" scoped>
  .relationship {
    padding-left: 20px;
    padding-right: 20px; 
  }

  .md-dialog{
        padding:20px;
  }

  .md-layout{
    width:100% !important;
  }


  .largo{
    width:50px !important;
    display:inline-block !important;
    margin-left:10px !important;
  }

  .buscar{
    width:350px !important;
    display:inline-block !important;
  }

  .scrollbar{
  overflow: auto !important;
  }

</style>