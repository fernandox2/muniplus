<template>

      <md-table-row class="text-center">

        <md-table-cell>{{ relationship.nameEmployee }}</md-table-cell>
        <md-table-cell>{{ relationship.nameDepartament }}</md-table-cell>
        <md-table-cell>{{ relationship.nameSchedule }}</md-table-cell>
        <md-table-cell v-if="relationship.turn === 1"><span class="badge badge-success"><i class="fa fa-check fa-2x" aria-hidden="true"></i></span></md-table-cell>
        <md-table-cell v-else><span class="badge badge-danger"><i class="fa fa-times fa-2x" aria-hidden="true"></i></span></md-table-cell>
        <md-table-cell>
          <md-button class="md-fab md-info" v-on:click.prevent="onClickEdit()"><md-icon>edit</md-icon></md-button> 
          <md-button class="md-fab md-danger" v-on:click.prevent="onClickDelete()"><md-icon>delete</md-icon></md-button>
        </md-table-cell>

      </md-table-row>

</template>

<script>

export default {
  props: ['relationship'],

  methods: {
    
    onClickDelete() {

      var mensaje = confirm("¿Confirma que desea eliminar la relación?");

      if (mensaje){

        axios.delete(`/relationships/${this.relationship.id}`).then(() => {

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

    onClickEdit(){

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