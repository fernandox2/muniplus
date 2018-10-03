<template>
      <md-table-row class="text-center">
        <md-table-cell>{{ exception.nombreFuncionario }}</md-table-cell>
        <md-table-cell>{{ exception.nombreTipo }}</md-table-cell>
        <md-table-cell>{{ exception.fecha_inicio }}</md-table-cell>
        <md-table-cell>{{ exception.fecha_fin }}</md-table-cell>
        <md-table-cell>{{ exception.glosa }}</md-table-cell>
        <md-table-cell><p class="centrar">
        <button v-on:click.prevent="onClickDelete()" type="button" class="md-button md-just-icon md-simple md-danger md-theme-default"><div class="md-ripple"><div class="md-button-content"><i class="md-icon md-icon-font md-theme-default">close</i> </div> <span></span></div></button></p>
        </md-table-cell>
      </md-table-row>
</template>

<script>
export default {
        props: ['exception'],
        data() {
            return {
                
            };
        },
        methods: {
            onClickDelete() {
                var mensaje = confirm("¿Confirma que desea eliminar la excepcion?");
                if (mensaje) 
                {
                    axios.delete(`/exceptions/${this.exception.id}`).then(() => {
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
            },
        }
    };
</script>

<style lang="scss" scoped>
  .md-icon {
    margin-right:20px;
  }

  .centrar{
    max-width:100px;
    margin:0px auto !important;
  }
</style>