<template>
  <div class="content">
    <div class="departament">
    
        <div class="md-layout">

          <div class="md-layout-item md-medium-size-50 md-small-size-100 md-xsmall-size-100"><NewDepartament @new="addDepartament"></NewDepartament></div>

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
          <TableDepartament
            v-for="(departament, index) in paginated"
            :key="departament.id"
            :departament="departament"
            :img="departament.logo"
            @delete="deleteDepartament(departament)"
            @actualizar="updateDepartament(departament)">
          </TableDepartament>
      </datatable>
      <br>
        <pagination :pagination="pagination" :client="true" :filtered="filteredProjects"
                    @prev="--pagination.currentPage"
                    @next="++pagination.currentPage">
        </pagination>
    </div>
  <md-dialog :md-active.sync="showDialog">
      <md-dialog-title class="text-center">Datos del Departamento<br><img :src="image" class="img-responsive"></md-dialog-title>
      
     <form novalidate class="md-layout" enctype="multipart/form-data" v-on:submit.prevent="editDepartament()">
          <div class="md-layout-item md-small-size-100 md-size-100">
            <md-field>
              <label>Nombre</label>
              <md-input v-model="nombre" type="text" required></md-input>
              <div class="error" v-if="!$v.nombre.required">Campo Obligatorio</div>
              <div class="error" v-if="!$v.nombre.minLength">El nombre debe tener a lo menos {{$v.nombre.$params.minLength.min}} letras.</div>
            </md-field>
          </div>

          <div class="md-layout-item md-small-size-100 md-size-100">
            <md-field>
              <label>Only images</label>
              <md-file v-model="image" id="imagen" accept="image/*" @change="onFileChange"/>
            </md-field>
          </div>
          <md-button class="md-primary close" @click="showDialog = false">Close</md-button>
          <md-button type="submit" class="md-raised md-success">Guardar</md-button>
        </form>
    </md-dialog>
</div>
</template>
<script>
import { required, minLength, between, email } from 'vuelidate/lib/validators';
import Datatable from '../Datatable.vue';
import Pagination from '../Pagination.vue';

import {
  TableDepartament,
  NewDepartament,
  OrderedTable
} from '@/components'

export default{
    name: 'DialogCustom',
    created(){
      this.getDepartaments();
    },
    data(){
        let sortOrders = {};

        let columns = [
            {width: '30%', label: 'Insignia', name: 'logo', type: 'image'},
            {width: '50%', label: 'Nombre del Departamento', name: 'nombre'},
            {width: '20%', label: 'Acciones', name: 'acciones'}
        ];

        columns.forEach((column) => {
           sortOrders[column.name] = -1;
        });
        return {
          departaments:[],
          departament:'',
          columns: columns,
          sortKey: '',
          sortOrders: sortOrders,
          length: 5,
          search: '',
          showDialog: false,
          id:0,
          nombre:'',
          image:null,
          extension:'',
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
        }
    },
  components: {
    OrderedTable,
    TableDepartament,
    datatable: Datatable, 
    pagination: Pagination,
    NewDepartament
  },
  methods: {
          onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
          createImage(file) {
                if(file.size < 512000){
                  this.extension = file.name.split(".")[1];
                  let reader = new FileReader();
                  let vm = this;
                  reader.onload = (e) => {
                  vm.image = e.target.result;
                };
                  reader.readAsDataURL(file);
                }else{
                  this.image = null;
                  this.$notify(
                    {
                      message: 'El tamaño del logo no debe ser mayor a 512 KB y debe ser en formato JPG',
                      icon: 'error',
                      horizontalAlign: 'right',
                      verticalAlign: 'top',
                      type: 'danger'
                    })
                }

        },
        addDepartament(departament) {
            this.departaments.push(departament);
        },
        deleteDepartament(departamento){
            var index = this.departaments.indexOf(departamento);
            this.departaments.splice(index, 1);
        },
        updateDepartament(departament){
          this.departament = departament;
          this.nombre = departament.nombre;
          this.image = departament.logo;
          this.id = departament.id;
          this.showDialog = true;
        },
        editDepartament(){
            if (!this.$v.$invalid) {
                const params = {
                nombre:this.nombre,
                image: this.image,
                extension: this.extension,
            };
            var index = this.departaments.indexOf(this.departament);
            axios.put(`/departaments/${this.id}`, params).then((response) => {
              this.departament = response.data;
              this.departaments[index] = this.departament;
              this.getDepartaments();
                if(this.departament.save){
                  this.nombre = "";
                  this.image = "";
                  this.extension = "";
                  this.showDialog = false;
                  this.$notify(
                    {
                      message: 'Se actualizó correctamente el departamento <b>' + this.departament.nombre + '</b>',
                      icon: 'done',
                      horizontalAlign: 'right',
                      verticalAlign: 'top',
                      type: 'success'
                    })
                }else{
                  this.$notify(
                    {
                      message: 'Ocurrió un error al intentar actualizar el departamento. Corrobore los datos e intente nuevamente',
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

            },
        getDepartaments(url = '/departaments') {
                axios.get(url, {params: this.tableData})
                    .then(response => {
                        this.departaments = response.data;
                        this.pagination.total = this.departaments.length;
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
            let departaments = this.departaments;
            if (this.search) {
                departaments = departaments.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                    })
                });
            }
            let sortKey = this.sortKey;
            let order = this.sortOrders[sortKey] || 1;
            if (sortKey) {
                departaments = departaments.slice().sort((a, b) => {
                    let index = this.getIndex(this.columns, 'nombre', sortKey);
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
            return departaments;
        },
        paginated() {
            return this.paginate(this.filteredProjects, this.length, this.pagination.currentPage);
        }
    }
};
</script>

<style lang="scss" scoped>
  .departament {
    padding-left: 20px;
    padding-right: 20px; 
  }
  .md-dialog{
        padding:20px;
  }

  .img-responsive{
        max-height: 150px;
        margin:0px auto;
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