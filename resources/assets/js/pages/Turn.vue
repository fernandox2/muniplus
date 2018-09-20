<template>
  <div class="content">
    <div class="turn">

        <div class="md-layout">

          <div class="md-layout-item md-medium-size-50 md-small-size-100 md-xsmall-size-100"><nuevo @new="addTurn"></nuevo></div>

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
          <TableTurn
            v-for="(turn, index) in paginated"
            :key="turn.id"
            :turn="turn"
            @delete="deleteTurn(turn)"
            @actualizar="actualizarTurn(turn)">
          </TableTurn>
      </datatable>
      <br>
        <pagination :pagination="pagination" :client="true" :filtered="filteredProjects"
                    @prev="--pagination.currentPage"
                    @next="++pagination.currentPage">
        </pagination>
  </div>
       <md-dialog :md-active.sync="showDialog">
      <md-dialog-title>Datos del Turno</md-dialog-title>

      <form novalidate class="md-layout" v-on:submit.prevent="editTurn()">
          
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
</template>
<script>

import Datatable from '../Datatable.vue';
import Pagination from '../Pagination.vue';

import {
  TableTurn,
  NewTurn,
  OrderedTable
} from '@/components'

export default{
    name: 'DialogCustom',
    created(){
      this.getTurns();
      axios.get('/RelacionesConTurnos').then(response => { this.relationships = response.data; }).catch(errors => { console.log(errors); })
      axios.get('/schedules').then(response => { this.schedules = response.data; }).catch(errors => { console.log(errors); })
    },
    data(){
        let sortOrders = {};

        let columns = [
            {width: '30%', label: 'Nombre Funcionario', name: 'nameEmployee'},
            {width: '30%', label: 'Departamento', name: 'nameDepartament'},
            {width: '19%', label: 'Horario', name: 'schedule'},
            {width: '7%', label: 'Fecha Inicio', name: 'inicio'},
            {width: '7%', label: 'Fecha Fin', name: 'fin'},
            {width: '7%', label: 'Acciones', name: 'acciones'}
        ];

        columns.forEach((column) => {
           sortOrders[column.name] = -1;
        });
        return {
          turns:[],
          turn:'',
          columns: columns,
          sortKey: '',
          sortOrders: sortOrders,
          length: 5,
          search: '',
          showDialog: false,
          schedules:[],
          schedule:0,
          relationships:[],
          relationship:0,
          inicio:null,
          fin:null,
          id:0,
          tableData: {
            client: true,
          },
          pagination: {
                currentPage: 1,
                total: '',
                nextPage: '',
                prevPage: '',
                from: '',
                to: ''
            },
        }
    },
  components: {
    OrderedTable,
    TableTurn,
    datatable: Datatable, 
    pagination: Pagination,
    nuevo: NewTurn
  },
  methods: {
        addTurn(turno) {
            this.turns.push(turno);
        },
        deleteTurn(turno){
            var index = this.turns.indexOf(turno);
            this.turns.splice(index, 1);
        },
        actualizarTurn(turno){
          this.turn = turno;
          this.schedule = turno.schedule_id;
          this.relationship = turno.relationship_id;
          this.inicio = turno.inicio;
          this.fin = turno.fin;
          this.id = turno.id;
          this.showDialog = true;
        },
        editTurn(){
          const params = {
          id: this.id,
          relationship: this.relationship,
          schedule: this.schedule,
          inicio: this.inicio,
          fin: this.fin,
        };
          var index = this.turns.indexOf(this.turn);
          axios.put(`/turns/${this.id}`, params).then((response) => {
              this.turn = response.data;
              this.turns[index] = this.turn;
              this.getTurns();
              this.id = 0;
              this.schedule = 0;
              this.relationship = 0;
              this.inicio = null;
              this.fin = null;
              this.showDialog = false;
              if(this.turn.save){
                this.$notify(
                  {
                    message: 'El registro se modificó correctamente',
                    icon: 'done',
                    horizontalAlign: 'right',
                    verticalAlign: 'top',
                    type: 'success'
                  })
              }else{
                this.$notify(
                  {
                    message: 'El registro no pudo ser actualizado. Por favor revise si los datos ingresados son correctos.',
                    icon: 'error',
                    horizontalAlign: 'right',
                    verticalAlign: 'top',
                    type: 'danger'
                  })
              }

          });
        },
        getTurns(url = '/turns') {
                axios.get(url, {params: this.tableData})
                    .then(response => {
                        this.turns = response.data;
                        this.pagination.total = this.turns.length;
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
            let turns = this.turns;
            if (this.search) {
                turns = turns.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                    })
                });
            }
            let sortKey = this.sortKey;
            let order = this.sortOrders[sortKey] || 1;
            if (sortKey) {
                turns = turns.slice().sort((a, b) => {
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
            return turns;
        },
        paginated() {
            return this.paginate(this.filteredProjects, this.length, this.pagination.currentPage);
        }
    }
};
</script>

<style lang="scss" scoped>


  .largo{
    width:50px !important;
    display:inline-block !important;
    margin-left:10px !important;
  }

  .buscar{
    width:350px !important;
    display:inline-block !important;
  }

</style>