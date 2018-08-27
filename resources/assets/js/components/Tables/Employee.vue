<template>

      <md-table-row>
        <md-table-cell>{{ employee.rut }}</md-table-cell>
        <md-table-cell>{{ employee.nombre }}</md-table-cell>
        <md-table-cell>{{ employee.correo }}</md-table-cell>
        <md-table-cell>{{ employee.codigo }}</md-table-cell>
        <md-table-cell>
        <md-button class="md-fab md-warning" v-on:click.prevent="onClickEdit()"><md-icon>edit</md-icon></md-button>
        <md-button class="md-fab md-danger" v-on:click.prevent="onClickDelete()"><md-icon>delete</md-icon></md-button>
        </md-table-cell>
      </md-table-row>
</template>

<script>
export default {
        props: ['employee'],
        data() {
            return {
                
            };
        },
        methods: {
            onClickDelete() {
                var mensaje = confirm("¿Confirma que desea eliminar el employee?");
                if (mensaje) 
                {
                    axios.delete(`/employees/${this.employee.id}`).then(() => {
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
                    toastr.error('No se eliminó el establecimiento');
                }
            },
            onClickEdit() {
                //this.editMode = true;
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