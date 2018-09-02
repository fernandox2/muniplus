<template>
      <md-table-row class="text-center">
        <md-table-cell><img v-bind:src="img" class="thumbnail" /></md-table-cell>
        <md-table-cell>{{ departament.nombre }}</md-table-cell>
        <md-table-cell><p class="centrar">
        <button v-on:click.prevent="onClickEdit()" type="button" class="md-button md-just-icon md-simple md-primary md-theme-default"><div class="md-ripple"><div class="md-button-content"><i class="md-icon md-icon-font md-theme-default">edit</i> </div> <span></span></div></button>
        <button v-on:click.prevent="onClickDelete()" type="button" class="md-button md-just-icon md-simple md-danger md-theme-default"><div class="md-ripple"><div class="md-button-content"><i class="md-icon md-icon-font md-theme-default">close</i> </div> <span></span></div></button></p>
        </md-table-cell>
      </md-table-row>
</template>

<script>
export default {
        props: ['departament','img'],
        data() {
            return {
              
            };
        },
        methods: {
            onClickDelete() {
                var mensaje = confirm("¿Confirma que desea eliminar el departamento?");
                if (mensaje) 
                {
                    axios.delete(`/departaments/${this.departament.id}`).then(() => {
                    this.$notify(
                    {
                      message: 'Registro eliminado correctamente',
                      icon: 'done',
                      horizontalAlign: 'right',
                      verticalAlign: 'top',
                      type: 'success'
                    })
                    this.$emit('delete');
                });

                }
                else 
                {
                    
                }
            },
            onClickEdit() {
                this.$emit('actualizar');
            },
        }
    };
</script>

<style lang="scss" scoped>
  .md-icon {
    margin-right:20px;
  }
  .thumbnail{
    max-height:100px;
  }

  .centrar{
    max-width:100px;
    margin:0px auto !important;
  }

</style>