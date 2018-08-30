<template>
  <div class="content">

    <md-dialog :md-active.sync="showDialog">
      <md-dialog-title class="text-center">Datos del Departamento<br><img :src="image" class="img-responsive"></md-dialog-title>
      
     <form novalidate class="md-layout" enctype="multipart/form-data" v-on:submit.prevent="newDepartament()">
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

<md-button class="md-primary" @click="showDialog = true">Nuevo Departamento</md-button>
  </div>
</template>
<style scoped>
    img{
        max-height: 150px;
        margin:0px auto;
    }

      .card-expansion {
    height: 480px;
  }

  .md-card {
    width: 320px;
    margin: 4px;
    display: inline-block;
    vertical-align: top;
  }
</style>
<script>
import { required, minLength } from 'vuelidate/lib/validators'
export default{
    data(){
      return {
          showDialog: false,
          nombre:'',
          image: null,
          extension: '',
        }
    },
      validations: {
        nombre: {
          required,
          minLength: minLength(5)
        },
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
        newDepartament(){
            if (!this.$v.$invalid) {
                const params = {
                nombre:this.nombre,
                image: this.image,
                extension: this.extension,
            };
            axios.post('/departaments',params).then((response) => {
                const res = response.data;
                if(res.save){
                  this.nombre = "";
                  this.image = "";
                  this.extension = "";
                  this.showDialog = false;
                  this.$notify(
                    {
                      message: 'Se creo correctamente el nuevo departamento <b>' + res.nombre + '</b>',
                      icon: 'done',
                      horizontalAlign: 'right',
                      verticalAlign: 'top',
                      type: 'success'
                    })
                  this.$emit('new', res);
                }else{
                  this.$notify(
                    {
                      message: 'Ocurrió un error al intentar almacenar el departamento. Corrobore los datos e intente nuevamente',
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
</style>