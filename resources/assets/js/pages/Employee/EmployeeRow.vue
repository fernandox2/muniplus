<template>
    <tr>
          <td>
            <p>{{ consejo.nombre }}</p>
        </td>
        <td>
            <p>{{ consejo.nombreDirector }}</p>
        </td>
        <td>
            <p>{{ consejo.nombreDocente }}</p>
        </td>
        <td>
            <p>{{ consejo.nombreAsistente }}</p>
        </td>
        <td>
            <p>{{ consejo.nombreApoderado }}</p>
        </td>
        <td>
            <p>{{ consejo.nombreEstudiante }}</p>
        </td>
        <td>
            <a href="#" v-on:click.prevent="onClickEdit()"><img src="/img/edit.png" width="32"></a>
            <a href="#" v-on:click.prevent="onClickDelete()"><img src="/img/delete.png" width="32"></a>
        </td>
    </tr>
</template>

<script>
export default {
        props: ['consejo'],
        data() {
            return {
                
            };
        },
        mounted() {
            //console.log('Componente escuela montado.')
        },
        methods: {
            onClickDelete() {
                var mensaje = confirm("¿Confirma que desea eliminar el consejo?");
                if (mensaje) 
                {
                    axios.delete(`/consejo/${this.consejo.id}`).then(() => {
                    toastr.success('Se eliminó correctamente el consejo escolar');
                    this.$emit('delete');
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