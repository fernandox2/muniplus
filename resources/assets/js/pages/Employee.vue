<template>
  <div class="content">
    <div class="employee">
      

        <div class="md-layout">

          <div class="md-layout-item md-medium-size-50 md-small-size-100 md-xsmall-size-100"><nuevo @new="addEmployee"></nuevo></div>

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
          <TableEmployee
            v-for="(employee, index) in paginated"
            :key="employee.id"
            :employee="employee"
            @delete="deleteEmployee(employee)"
            @actualizar="actualizarEmployee(employee)">
          </TableEmployee>
      </datatable>
      <br>
        <pagination :pagination="pagination" :client="true" :filtered="filteredProjects"
                    @prev="--pagination.currentPage"
                    @next="++pagination.currentPage">
        </pagination>
  </div>
  <md-dialog :md-active.sync="showDialog">
      <md-dialog-title>Datos del Funcionario</md-dialog-title>

      <form novalidate class="md-layout" v-on:submit.prevent="editEmployee()">
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
          <md-button type="submit" class="md-raised md-success right">Guardar</md-button>
        </form>
      <md-dialog-actions>
        <md-button class="md-primary close" @click="showDialog = false">Close</md-button>
      </md-dialog-actions>
    </md-dialog>
</div>
</template>
<script>
import { required, minLength, between, email } from 'vuelidate/lib/validators';
import Datatable from '../Datatable.vue';
import Pagination from '../Pagination.vue';

import {
  TableEmployee,
  NewEmployee,
  OrderedTable
} from '@/components'

export default{
    name: 'DialogCustom',
    created(){
      this.getEmployees();
    },
    data(){
        let sortOrders = {};

        let columns = [
            {width: '15%', label: 'RUN', name: 'rut'},
            {width: '30%', label: 'Nombre Completo', name: 'nombre'},
            {width: '30%', label: 'Correo Electrónico', name: 'correo'},
            {width: '15%', label: 'Código', name: 'codigo'},
            {width: '10%', label: 'Acciones', name: 'acciones'}
        ];

        columns.forEach((column) => {
           sortOrders[column.name] = -1;
        });
        return {
          employees:[],
          employee:'',
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
  components: {
    OrderedTable,
    TableEmployee,
    datatable: Datatable, 
    pagination: Pagination,
    nuevo: NewEmployee
  },
  methods: {
        addEmployee(empleado) {
            this.employees.push(empleado);
        },
        deleteEmployee(empleado){
            var index = this.employees.indexOf(empleado);
            this.employees.splice(index, 1);
        },
        actualizarEmployee(empleado){
          this.employee = empleado;
          this.nombre = empleado.nombre;
          this.rut = empleado.rut;
          this.correo = empleado.correo;
          this.codigo = empleado.codigo;
          this.id = empleado.id;
          this.showDialog = true;
        },
        editEmployee(){
          const params = {
          id: this.id,
          nombre: this.nombre,
          rut: this.rut,
          correo: this.correo,
          codigo: this.codigo,
        };
          var index = this.employees.indexOf(this.employee);
          axios.put(`/employees/${this.id}`, params).then((response) => {
              this.employee = response.data;
              this.employees[index] = this.employee;
              this.getEmployees();
              this.id = 0;
              this.nombre = "";
              this.rut = "";
              this.correo = "";
              this.codigo = "";
              this.showDialog = false;
              if(this.employee.save){
                this.$notify(
                  {
                    message: 'Se actualizó correctamente el funcionario:<b> ' + this.employee.nombre + '</b>',
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
        getEmployees(url = '/employees') {
                axios.get(url, {params: this.tableData})
                    .then(response => {
                        this.employees = response.data;
                        this.pagination.total = this.employees.length;
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
            let employees = this.employees;
            if (this.search) {
                employees = employees.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                    })
                });
            }
            let sortKey = this.sortKey;
            let order = this.sortOrders[sortKey] || 1;
            if (sortKey) {
                employees = employees.slice().sort((a, b) => {
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
            return employees;
        },
        paginated() {
            return this.paginate(this.filteredProjects, this.length, this.pagination.currentPage);
        }
    }
};
</script>

<style lang="scss" scoped>
  .employee {
    padding-left: 20px;
    padding-right: 20px; 
  }
  .md-dialog{
        padding:20px;
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

</style>