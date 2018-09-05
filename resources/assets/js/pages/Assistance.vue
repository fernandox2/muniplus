<template>
  <div class="content">
    
    <div class="row">

      <div class="col">
          <md-button class="md-raised md-info pull-right filtrar" @click="createPDF">Exportar</md-button>
          <md-button class="md-raised md-primary pull-right filtrar" @click="filtrar">Filtrar</md-button>
          <md-field class="pull-right search">
             <select v-model="employee" class="form-control">
             <option value="0">Seleccione un funcionario</option>
             <option v-for="option in employees" v-bind:value="option.id">
                 {{ option.nombre }}
             </option>
             </select>
          </md-field>

          <md-field class="pull-right search">
             <select v-model="departament" class="form-control" @change="funcionarioPorDepartamento">
             <option value="0">Seleccione un departamento</option>
             <option v-for="option in departaments" v-bind:value="option.id">
                 {{ option.nombre }}
             </option>
             </select>
          </md-field>

        <div class="pull-left">
          
          <md-field>
            
            <div>
              <span><md-icon>event</md-icon> Inicio</span>
              <md-input type="date" v-model="inicio"></md-input>
            </div>

            <div>
              <span><md-icon>event</md-icon> Fin</span>
            <md-input type="date" v-model="fin"></md-input>
          </div>
          
          </md-field>

        </div>

      </div>

    </div>


    <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
          <TableAssistance
            v-for="(assistance, index) in paginated"
            :key="assistance.id"
            :assistance="assistance">
          </TableAssistance>
      </datatable>

      <br>

      <pagination :pagination="pagination" :client="true" :filtered="filteredProjects"
                    @prev="--pagination.currentPage"
                    @next="++pagination.currentPage">
      </pagination>

  
</div>
</template>
<script>

import Datatable from '../Datatable.vue';
import Pagination from '../Pagination.vue';
import { TableAssistance, OrderedTable } from '@/components'
import jsPDF from 'jspdf'
import autotable from 'jspdf-autotable'
export default{
    name: 'DialogCustom',
    created(){
      this.getassistances();
      axios.get('/employees').then(response => { this.employees = response.data; }).catch(errors => { console.log(errors); })
      axios.get('/departaments').then(response => { this.departaments = response.data; }).catch(errors => { console.log(errors); })
    },
    data(){

        let sortOrders = {};

        let columns = [
            {width: '5%', label: 'Fecha', name: 'fecha'},
            {width: '25%', label: 'Funcionario', name: 'funcionario'},
            {width: '5%', label: 'Entrada 1', name: 'entrada1'},
            {width: '5%', label: 'Salida 1', name: 'salida1'},
            {width: '5%', label: 'Entrada 2', name: 'entrada2'},
            {width: '5%', label: 'Salida 2', name: 'salida2'},
            {width: '5%', label: 'Hrs. Trabajadas', name: 'horas_trabajadas'},
            {width: '30%', label: 'Detalle', name: 'comentario'},

        ];

        columns.forEach((column) => {
           sortOrders[column.name] = -1;
        });

        return {
          inicio:null,
          fin:null,
          employee:0,
          employees:[],
          departament:0,
          departaments:[],
          assistances:[],
          assistance:'',
          columns: columns,
          sortKey: '',
          sortOrders: sortOrders,
          length: 25,
          search: '',
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
    TableAssistance,
    datatable: Datatable, 
    pagination: Pagination,
    jsPDF
  },
  methods: {
    funcionarioPorDepartamento(){
      if(this.departament != 0){
        axios.get(`/employees/departament/${this.departament}`).then(response => { this.employees = response.data; }).catch(errors => { console.log(errors); })
      }else{
        axios.get('/employees').then(response => { this.employees = response.data; }).catch(errors => { console.log(errors); })
      }
    },
    createPDF () {
      var columns = ["Fecha", "Funcionario", "Entrada 1", "Salida 1", "Entrada 2", "Salida 2", "Hrs. Trabajadas", "Detalle"];
      var rows = [];
      var i;
      var arr = [];

      for (i = 0; i < this.assistances.length; i++) { 
          arr = [];
          arr.push(this.assistances[i].fecha);
          arr.push(this.assistances[i].funcionario);
          arr.push(this.assistances[i].entrada1);
          arr.push(this.assistances[i].salida1);
          arr.push(this.assistances[i].entrada2);
          arr.push(this.assistances[i].salida2);
          arr.push(this.assistances[i].horas_trabajadas);
          if(this.assistances[i].comentario == null){
            arr.push("");
          }else{
            arr.push(this.assistances[i].comentario);
          }
          
          rows.push(arr);
      }


// Only pt supported (not mm or in)
var doc = new jsPDF('l', 'pt', 'letter');
doc.autoTable(columns, rows, {
    styles: {fillColor: [100, 180, 180]},

    margin: {top: 60},
    addPageContent: function(data) {
      doc.text("INFORME DE ASISTENCIA", 40, 45);
    }
});
var timeStampInMs = window.performance && window.performance.now && window.performance.timing && window.performance.timing.navigationStart ? window.performance.now() + window.performance.timing.navigationStart : Date.now();
doc.save(timeStampInMs +'.pdf');
      },
        getassistances(url = '/assistances') {
                axios.get(url, {params: this.tableData})
                    .then(response => {
                        this.assistances = response.data;
                        this.pagination.total = this.assistances.length;
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
        },

    filtrar(){

      if(this.inicio != null && this.fin != null && this.employee != 0 && this.departament != 0){

        axios.get(`/assistances/todos/${this.employee}/${this.inicio}/${this.fin}/${this.departament}`).then(response => {
                  this.assistances = response.data;                 
                  this.pagination.total = this.assistances.length;
              });
          }else if(this.inicio != null && this.fin != null && this.employee != 0){
              axios.get(`/assistances/fechasemployee/${this.employee}/${this.inicio}/${this.fin}`).then(response => {
                        this.assistances = response.data;                 
                        this.pagination.total = this.assistances.length;
              });
          }else if(this.inicio != null && this.fin != null && this.departament != 0){
            axios.get(`/assistances/fechasdepartament/${this.inicio}/${this.fin}/${this.departament}`).then(response => {
                        this.assistances = response.data;                 
                        this.pagination.total = this.assistances.length;
              });
          }else if(this.departament != 0){
            axios.get(`/assistances/departamento/${this.departament}`).then(response => {
                        this.assistances = response.data;                 
                        this.pagination.total = this.assistances.length;
              });
          }else if(this.employee != 0){
            axios.get(`/assistances/employee/${this.employee}`).then(response => {
                        this.assistances = response.data;                 
                        this.pagination.total = this.assistances.length;
              });
          }else if(this.inicio != null && this.fin != null){
            axios.get(`/assistances/fechas/${this.inicio}/${this.fin}`).then(response => {
                        this.assistances = response.data;                 
                        this.pagination.total = this.assistances.length;
              });
          }else{
            this.getassistances();
          }
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
            let assistances = this.assistances;
            if (this.search) {
                assistances = assistances.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                    })
                });
            }
            let sortKey = this.sortKey;
            let order = this.sortOrders[sortKey] || 1;
            if (sortKey) {
                assistances = assistances.slice().sort((a, b) => {
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
            return assistances;
        },
        paginated() {
            return this.paginate(this.filteredProjects, this.length, this.pagination.currentPage);
        }
    }
};
</script>

<style lang="scss" scoped>
  .assistance {
    padding-left: 20px;
    padding-right: 20px; 
  }
  .md-dialog{
        padding:20px;
  }

  .search{
    max-width:350px;
    margin-top:30px;
  }

  .fechas{
    max-width:150px;
    display:inline-block !important;
  }

  .content{
    padding-left:20px;
    padding-right:20px;
  }
.filtrar{
  margin-top:40px;
  margin-left:10px;
}


</style>