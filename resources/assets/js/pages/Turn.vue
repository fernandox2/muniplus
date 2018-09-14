<template>
  <div class="content">
    <div class="turn">
      <nuevo @new="addturn"></nuevo>

        <form class="form-inline md-form form-sm">       
            <i class="fa fa-search" aria-hidden="true"></i>
            <input class="form-control form-control-sm ml-3 w-75 search" type="text" v-model="search" placeholder="Buscar ..." aria-label="Search" accept=""input="resetPagination()">
            <select v-model="length" @change="resetPagination()" class="form-control browser-default select">
              <option value="5">5</option>
              <option value="10">10</option>
              <option value="20">20</option>
          </select>
        </form>

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
          id:0,
          nombre:'',
          rut:'',
          correo:'',
          codigo:'',
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
        addturn(empleado) {
            this.turns.push(empleado);
        },
        deleteturn(empleado){
            var index = this.turns.indexOf(empleado);
            this.turns.splice(index, 1);
        },
        actualizarturn(empleado){
          this.turn = empleado;
          this.nombre = empleado.nombre;
          this.rut = empleado.rut;
          this.correo = empleado.correo;
          this.codigo = empleado.codigo;
          this.id = empleado.id;
          this.showDialog = true;
        },
        editturn(){
          const params = {
          id: this.id,
          nombre: this.nombre,
          rut: this.rut,
          correo: this.correo,
          codigo: this.codigo,
        };
          var index = this.turns.indexOf(this.turn);
          axios.put(`/turns/${this.id}`, params).then((response) => {
              this.turn = response.data;
              this.turns[index] = this.turn;
              this.getturns();
              this.id = 0;
              this.nombre = "";
              this.rut = "";
              this.correo = "";
              this.codigo = "";
              this.showDialog = false;
              if(this.turn.save){
                this.$notify(
                  {
                    message: 'Se actualizó correctamente el funcionario:<b> ' + this.turn.nombre + '</b>',
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
  .turn {
    padding-left: 20px;
    padding-right: 20px; 
  }
  .md-dialog{
        padding:20px;
  }

  .search{
    max-width:350px;
    display:inline-block !important; 
  }

  .select{
    max-width:30px;
    display:inline-block !important; 
  }

</style>