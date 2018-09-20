<template>
  <div class="content">
    <div class="schedule">
      
        <div class="md-layout">

          <div class="md-layout-item md-medium-size-50 md-small-size-100 md-xsmall-size-100"><nuevo @new="addSchedule"></nuevo></div>

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
          <TableSchedule
            v-for="(schedule, index) in paginated"
            :key="schedule.id"
            :schedule="schedule"
            @delete="deleteSchedule(schedule)"
            @actualizar="actualizarSchedule(schedule)">
          </TableSchedule>
      </datatable>
      <br>
        <pagination :pagination="pagination" :client="true" :filtered="filteredProjects"
                    @prev="--pagination.currentPage"
                    @next="++pagination.currentPage">
        </pagination>

        <md-dialog :md-active.sync="showDialog" class="scrollbar">
      <md-dialog-title class="text-center">Datos del Horario <p>Formato 24 Hrs.</p></md-dialog-title>
      <form novalidate class="md-layout">
          <div class="md-layout-item md-small-size-100 md-size-100">
            <md-field>
              <label>Nombre del Horario</label>
              <md-input v-model="nombre" type="text" required></md-input>
              <div class="error" v-if="!$v.nombre.minLength">El nombre debe tener a lo menos {{$v.nombre.$params.minLength.min}} letras.</div>
            </md-field>
          </div>

          <div class="md-layout md-gutter">
            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Lunes</label>
              <md-chips  md-input-type="text" v-model="HorarioLunes" :md-format="validarHora" :md-limit="4"></md-chips>
            </div>
            </div>

            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Martes</label>
              <md-chips  md-input-type="text" v-model="HorarioMartes" :md-format="validarHora" :md-limit="4"></md-chips>
            </div>
            </div>
          </div>
          <div class="md-layout md-gutter">
            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Miércoles</label>
              <md-chips  md-input-type="text" v-model="HorarioMiercoles" :md-format="validarHora" :md-limit="4"></md-chips>
            </div>
            </div>

            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Jueves</label>
              <md-chips  md-input-type="text" v-model="HorarioJueves" :md-format="validarHora" :md-limit="4"></md-chips>
            </div>
            </div>
          </div>

          <div class="md-layout md-gutter">
            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Viernes</label>
              <md-chips  md-input-type="text" v-model="HorarioViernes" :md-format="validarHora" :md-limit="4"></md-chips>
            </div>
            </div>

            <div class="md-layout-item md-small-size-100">
            <div>
              <label>Sábado</label>
              <md-chips md-input-type="text" v-model="HorarioSabado" :md-format="validarHora" :md-limit="4"></md-chips>
            </div>
            </div>
          </div>

          <div class="md-layout-item md-small-size-100 md-size-100">
            <div>
              <label>Domingo</label>
              <md-chips md-input-type="text" v-model="HorarioDomingo" :md-format="validarHora" :md-limit="4"></md-chips>
            </div>
          </div>

          <md-button class="md-primary" @click="showDialog = false">Cerrar</md-button>
          <md-button type="button" @click="editSchedule" class="md-raised md-success">Guardar</md-button>
        </form>

    </md-dialog>
  </div>
 </div>
</template>
<script>
import { required, minLength, between, email } from 'vuelidate/lib/validators';
import Datatable from '../Datatable.vue';
import Pagination from '../Pagination.vue';

import {
  TableSchedule,
  NewSchedule,
  OrderedTable
} from '@/components'

export default{
    name: 'DialogCustom',
    created(){
      this.getSchedules();
    },
    data(){
        let sortOrders = {};

        let columns = [
            {width: '80%', label: 'Nombre del Horario', name: 'nombre'},
            {width: '20%', label: 'Acciones', name: 'acciones'}
        ];

        columns.forEach((column) => {
           sortOrders[column.name] = -1;
        });
        return {
          schedules:[],
          schedule:'',
          columns: columns,
          sortKey: '',
          sortOrders: sortOrders,
          length: 5,
          search: '',
          showDialog: false,
          id:0,
          nombre:'',
          HorarioLunes:[],
          HorarioMartes:[],
          HorarioMiercoles:[],
          HorarioJueves:[],
          HorarioViernes:[],
          HorarioSabado:[],
          HorarioDomingo:[],
          todos:[],
          horarios:null,
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
    TableSchedule,
    datatable: Datatable, 
    pagination: Pagination,
    nuevo: NewSchedule
  },
  methods: {
        isNumber(num){
          return !isNaN(parseFloat(num)) && isFinite(num);
        },
        validarHora(tiempo){

          var hora = tiempo.split(":");
          var h = hora[0];
          var m = hora[1];

          if(this.isNumber(h) && this.isNumber(m) && h < 24 && m < 60)            
            return tiempo;
        },
        addSchedule(schedule) {
            this.schedules.push(schedule);
        },
        deleteSchedule(schedule){
            var index = this.schedules.indexOf(schedule);
            this.schedules.splice(index, 1);
        },
        getSchedules(url = '/schedules') {
                axios.get(url, {params: this.tableData})
                    .then(response => {
                        this.schedules = response.data;
                        this.pagination.total = this.schedules.length;
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
        editSchedule(){
              // Añadir Todos Los Horarios A Un Array
            this.todos=[
                this.HorarioLunes.sort(),
                this.HorarioMartes.sort(),
                this.HorarioMiercoles.sort(),
                this.HorarioJueves.sort(),
                this.HorarioViernes.sort(),
                this.HorarioSabado.sort(),
                this.HorarioDomingo.sort()
              ];
                const params = {
                id: this.id,
                nombre:this.nombre,
                HorarioLunes: this.HorarioLunes.sort(),
                HorarioMartes: this.HorarioMartes.sort(),
                HorarioMiercoles: this.HorarioMiercoles.sort(),
                HorarioJueves: this.HorarioJueves.sort(),
                HorarioViernes: this.HorarioViernes.sort(),
                HorarioSabado: this.HorarioSabado.sort(),
                HorarioDomingo: this.HorarioDomingo.sort(),
                todos: this.todos
            };
          var index = this.schedules.indexOf(this.schedule);
          axios.put(`/schedules/${this.id}`, params).then((response) => {
              this.schedule = response.data;
              this.schedules[index] = this.schedule;
              this.getSchedules();
              this.id = 0;
              this.nombre = "";
              this.HorarioLunes = []; 
              this.HorarioMartes = [];
              this.HorarioMiercoles = [];
              this.HorarioJueves = [];
              this.HorarioViernes = [];
              this.HorarioSabado = [];
              this.HorarioDomingo = [];
              this.todos = [];
              this.showDialog = false;
              if(this.schedule.save){
                this.$notify(
                  {
                    message: 'Se actualizó correctamente el horario:<b> ' + this.schedule.nombre + '</b>',
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
        getPrograms(id) {
            axios.get(`/schedules/programs/${id}`).then(response => { 
            this.horario = response.data;
            var i;
            var entrada1;
            var salida1;
            var entrada2;
            var salida2;
            for (i = 0; i < this.horario.length; i++) { 
                  // Quitar ultimos digitos despues del :
                  if(this.horario[i].entrada1 != null && this.horario[i].salida1 != null){
                    entrada1 = this.horario[i].entrada1.split(":")[0] + ":" + this.horario[i].entrada1.split(":")[1];
                    salida1 = this.horario[i].salida1.split(":")[0] + ":" + this.horario[i].salida1.split(":")[1];
                  }

                  if(this.horario[i].entrada2 != null && this.horario[i].salida2 != null){
                    entrada2 = this.horario[i].entrada2.split(":")[0] + ":" + this.horario[i].entrada2.split(":")[1];
                    salida2 = this.horario[i].salida2.split(":")[0] + ":" + this.horario[i].salida2.split(":")[1];
                  }

                  switch(this.horario[i].dia_id) {
                          case 1:
                          this.HorarioLunes = [
                            entrada1,
                            salida1,
                            entrada2,
                            salida2,
                          ];
                          this.HorarioLunes = this.HorarioLunes.filter(Boolean);
                              break;
                          case 2:
                          this.HorarioMartes = [
                            entrada1,
                            salida1,
                            entrada2,
                            salida2,
                          ];
                          this.HorarioMartes = this.HorarioMartes.filter(Boolean);
                              break;
                          case 3:
                          this.HorarioMiercoles = [
                             entrada1,
                            salida1,
                            entrada2,
                            salida2,
                          ];
                          this.HorarioMiercoles = this.HorarioMiercoles.filter(Boolean);
                              break;
                          case 4:
                          this.HorarioJueves = [
                            entrada1,
                            salida1,
                            entrada2,
                            salida2,
                          ];
                          this.HorarioJueves = this.HorarioJueves.filter(Boolean);
                              break;
                          case 5:
                          this.HorarioViernes = [
                            entrada1,
                            salida1,
                            entrada2,
                            salida2,
                          ];
                          this.HorarioViernes = this.HorarioViernes.filter(Boolean);
                              break;
                          case 6:
                          this.HorarioSabado = [
                            entrada1,
                            salida1,
                            entrada2,
                            salida2,
                          ];
                          this.HorarioSabado = this.HorarioSabado.filter(Boolean);
                              break;
                          case 7:
                          this.HorarioDomingo = [
                            entrada1,
                            salida1,
                            entrada2,
                            salida2,
                          ];
                          this.HorarioDomingo = this.HorarioDomingo.filter(Boolean);
                              break;
                          default:
                              break;
                      }
            }

          }).catch(errors => { console.log(errors); });
            },
        actualizarSchedule(schedule){
          this.schedule = schedule;
          this.nombre = schedule.nombre;
          this.id = schedule.id;
          this.HorarioLunes = []; 
          this.HorarioMartes = [];
          this.HorarioMiercoles = [];
          this.HorarioJueves = [];
          this.HorarioViernes = [];
          this.HorarioSabado = [];
          this.HorarioDomingo = [];
          this.todos = [];
          this.getPrograms(this.id);
          this.showDialog = true;
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
            let schedules = this.schedules;
            if (this.search) {
                schedules = schedules.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                    })
                });
            }
            let sortKey = this.sortKey;
            let order = this.sortOrders[sortKey] || 1;
            if (sortKey) {
                schedules = schedules.slice().sort((a, b) => {
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
            return schedules;
        },
        paginated() {
            return this.paginate(this.filteredProjects, this.length, this.pagination.currentPage);
        }
    }
};
</script>

<style lang="scss" scoped>
  .schedule {
    padding-left: 20px;
    padding-right: 20px; 
  }
  .md-dialog{
        padding:20px;
  }

  .scrollbar{
    overflow:auto !important;
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