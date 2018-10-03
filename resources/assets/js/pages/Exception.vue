<template>
  <div class="content">
    <div class="exception">

        <div class="md-layout">

          <div class="md-layout-item md-medium-size-50 md-small-size-100 md-xsmall-size-100">
            <nuevo @new="addException"></nuevo>
          </div>

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
          <TableException
            v-for="(exception, index) in paginated"
            :key="exception.id"
            :exception="exception"
            @delete="deleteException(exception)"
            @actualizar="actualizarException(exception)">
          </TableException>
      </datatable>
      <br>
        <pagination :pagination="pagination" :client="true" :filtered="filteredProjects"
                    @prev="--pagination.currentPage"
                    @next="++pagination.currentPage">
        </pagination>
  </div>
       
</div>
</template>
<script>

import Datatable from '../Datatable.vue';
import Pagination from '../Pagination.vue';

import {
  NewException,
  TableException,
  OrderedTable
} from '@/components'

export default{
    name: 'Exceptions',
    created(){
      this.getExcepciones();
    },
    data(){
        let sortOrders = {};

        let columns = [
            {width: '20%', label: 'Funcionario', name: 'nombreFuncionario'},
            {width: '15%', label: 'Tipo Excepcion', name: 'nombreTipo'},
            {width: '10%', label: 'Inicio', name: 'fecha_inicio'},
            {width: '10%', label: 'Fin', name: 'fecha_fin'},
            {width: '33%', label: 'Glosa', name: 'glosa'},
            {width: '7%', label: 'Acciones', name: 'acciones'}
        ];

        columns.forEach((column) => {
           sortOrders[column.name] = -1;
        });
        return {
          exceptions:[],
          exception:'',
          columns: columns,
          sortKey: '',
          sortOrders: sortOrders,
          length: 5,
          search: '',
          showDialog: false,
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
    TableException,
    datatable: Datatable, 
    pagination: Pagination,
    nuevo: NewException
  },
  methods: {
        addException(exception) {
            this.exceptions.push(exception);
        },
        deleteException(exception){
            var index = this.exceptions.indexOf(exception);
            this.exceptions.splice(index, 1);
        },
        actualizarexception(exceptiono){
          this.exception = exceptiono;
          this.schedule = exceptiono.schedule_id;
          this.relationship = exceptiono.relationship_id;
          this.inicio = exceptiono.inicio;
          this.fin = exceptiono.fin;
          this.id = exceptiono.id;
          this.showDialog = true;
        },
        editexception(){
          const params = {
          id: this.id,
          relationship: this.relationship,
          schedule: this.schedule,
          inicio: this.inicio,
          fin: this.fin,
        };
          var index = this.exceptions.indexOf(this.exception);
          axios.put(`/exceptions/${this.id}`, params).then((response) => {
              this.exception = response.data;
              this.exceptions[index] = this.exception;
              this.getexceptions();
              this.id = 0;
              this.schedule = 0;
              this.relationship = 0;
              this.inicio = null;
              this.fin = null;
              this.showDialog = false;
              if(this.exception.save){
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
        getExcepciones(url = '/exceptions') {
                axios.get(url, {params: this.tableData})
                    .then(response => {
                        this.exceptions = response.data;
                        this.pagination.total = this.exceptions.length;
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
            let exceptions = this.exceptions;
            if (this.search) {
                exceptions = exceptions.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                    })
                });
            }
            let sortKey = this.sortKey;
            let order = this.sortOrders[sortKey] || 1;
            if (sortKey) {
                exceptions = exceptions.slice().sort((a, b) => {
                    let index = this.getIndex(this.columns, 'created_at', sortKey);
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
            return exceptions;
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