<template>
  <div class="content">

      <div>
    <md-dialog :md-active.sync="showDialog">
      <md-dialog-title>Datos del Funcionario</md-dialog-title>

      <form novalidate class="md-layout" v-on:submit.prevent="nuevoEmployee()">
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
              <md-input v-model="rut" type="text" oninput="checkRut(this)" onblur="formato_rut(this)"></md-input>
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
<md-button class="md-primary" @click="showDialog = true"> Nuevo </md-button>
<br>
    <div class="md-layout">
      

        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
          <TableEmployee
            v-for="(employee, index) in paginated"
            :key="employee.id"
            :employee="employee"
            @delete="deleteConsejo(employee)"
            @actualizar="actualizarConsejo(employee)">
        </TableEmployee>
        </datatable>


    </div>

  </div>
</template>

<script>
import { required, minLength, between, email } from 'vuelidate/lib/validators';
import Datatable from '../../Datatable.vue';
import Pagination from '../../Pagination.vue';

import {
  TableEmployee,
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
          columns: columns,
          establecimiento: '',
          sortKey: '',
          sortOrders: sortOrders,
          length: 10,
          search: '',
          showDialog: false,
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
    pagination: Pagination
  },
          methods: {
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
            nuevoEmployee(){
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
                    //this.$emit('new', empleado);
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
            }

            }
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
  .md-layout {
    padding-left: 20px;
    padding-right: 20px; 
  }
  .md-dialog-actions{
        padding-right: 20px; 
  }

  .close{
    display:none;
  }

  .error{
    color:red;
  }
</style>