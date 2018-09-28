<template>
  <div class="content">

  <div>
    <md-dialog :md-active.sync="showDialog">
      <md-dialog-title></md-dialog-title>

      <form novalidate class="md-layout" v-on:submit.prevent="newException">

        <div class="md-layout-item md-small-size-100 md-size-100">
          <div class="form-group">
             <label for="relationship">Relación Existente: </label><br>
             <select v-model="relationship" class="form-control" v-on:change="">
             <option value="0">Seleccione una opción</option>
             <option v-for="option in relationships" v-bind:value="option.employee_id">
                 {{ option.nameEmployee }} - {{ option.nameDepartament }}
             </option>
             </select>
          </div>
        </div>

        <div class="md-layout-item md-small-size-100 md-size-100">
          <div class="form-group">
             <label for="tipo">Tipo de Excepción: </label><br>
             <select v-model="tipo" class="form-control" v-on:change="">
             <option value="0">Seleccione una opción</option>
             <option v-for="option in tipos" v-bind:value="option.id">
                 {{ option.name }}
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

          <div class="md-layout-item md-small-size-100 md-size-100">
            <md-field>
              <label>Glosa</label>
              <md-input v-model="glosa" type="text" required></md-input>
              <div class="error" v-if="!$v.glosa.required">Campo Obligatorio</div>
              <div class="error" v-if="!$v.glosa.minLength">El glosa debe tener a lo menos {{$v.glosa.$params.minLength.min}} letras.</div>
            </md-field>
          </div>

          <md-button class="md-primary" @click="showDialog = false">Cerrar</md-button>
          <md-button type="submit" class="md-raised md-success">Guardar</md-button>

        </form>
    </md-dialog>
  </div>
  
<md-button class="md-primary" @click="showDialog = true"> Nueva Excepción</md-button>

  </div>
</template>

<script>
import { required, minLength, between, email } from 'vuelidate/lib/validators';

export default{
  created(){
    axios.get('/relationships').then(response => { this.relationships = response.data; }).catch(errors => { console.log(errors); })
    axios.get('/tiposdeexcepciones').then(response => { this.tipos = response.data; }).catch(errors => { console.log(errors); })
  },
    data(){
      return {
          showDialog: false,
          glosa:'',
          relationship: 0,
          tipo: 0,
          relationships:[],
          tipos:[],
          inicio:'',
          fin:'',
        }
    },
      validations: {
        glosa: {
          required,
          minLength: minLength(10)
        }
    },
      methods: {
        newException(){
            if (!this.$v.$invalid) {

              const params = {
                
                relationship:this.relationship,
                tipo: this.tipo,
                inicio: this.inicio,
                fin: this.fin,
                glosa: this.glosa

              };

              axios.post('/exceptions',params).then((response) => {
                
                const exception = response.data;

                this.glosa = "";
                this.inicio = null;
                this.fin = null;
                this.relationship = 0;
                this.tipo = 0;

                if(exception.save)
                {
                  this.showDialog = false;
                  this.$notify(
                    {
                      message: 'Se creo correctamente el funcionario:<b> ' + exception.nombre + '</b>',
                      icon: 'done',
                      horizontalAlign: 'right',
                      verticalAlign: 'top',
                      type: 'success'
                    })
                    this.$emit('new', exception);
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

  .error{
    color: red !important;
  }

  label{
    font-weight: bold;
  }
</style>