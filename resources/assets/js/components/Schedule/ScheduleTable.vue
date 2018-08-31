<template>
      <md-table-row class="text-center">
        <md-table-cell>{{ schedule.nombre }}</md-table-cell>
        <md-table-cell>
        <md-button class="md-fab md-info" v-on:click.prevent="onClickEdit()"><md-icon>edit</md-icon></md-button> 
        <md-button class="md-fab md-danger" v-on:click.prevent="onClickDelete()"><md-icon>delete</md-icon></md-button>
        </md-table-cell>
      </md-table-row>
</template>

<script>
export default {
        props: ['schedule'],
        data() {
            return {
                
            };
        },
        methods: {
            onClickDelete() {
                var mensaje = confirm("¿Confirma que desea eliminar el horario?");
                if (mensaje) 
                {
                    axios.delete(`/schedules/${this.schedule.id}`).then(() => {
                    this.$notify(
                    {
                      message: 'Registro eliminado correctamente',
                      icon: 'done',
                      horizontalAlign: 'right',
                      verticalAlign: 'top',
                      type: 'success'
                    })
                    //this.$emit('delete');
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
</style>