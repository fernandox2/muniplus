<template>
      <md-table-row class="text-center">
        <md-table-cell>{{ assistance.fecha }}</md-table-cell>
        <md-table-cell>{{ assistance.funcionario }}</md-table-cell>
        <md-table-cell>{{ assistance.entrada1 }}</md-table-cell>
        <md-table-cell>{{ assistance.salida1 }}</md-table-cell>
        <md-table-cell>{{ assistance.entrada2 }}</md-table-cell>
        <md-table-cell>{{ assistance.salida2 }}</md-table-cell>
        <md-table-cell>{{ assistance.horas_trabajadas }}</md-table-cell>
        <md-table-cell>{{ assistance.comentario }}</md-table-cell>
      </md-table-row>
</template>

<script>
export default {
        props: ['assistance'],
        data() {
            return {
                
            };
        },
        methods: {

        }
    };
</script>

<style lang="scss" scoped>

</style>