<template>
    <div>
        <md-table>

                <md-table-head class="table-header text-center" v-for="column in columns" :key="column.name" @click="$emit('sort', column.name)">
                    {{column.label}}
                </md-table-head>

        <slot></slot>
    </md-table>
</div>
</template>

<script>
    export default {
        props: ['columns', 'sortKey', 'sortOrders']
    };
</script>

<style lang="scss" scoped>
  .table-header {
    padding-top:10px;
    padding-bottom:10px;
    background-color:purple;
    color: white !important;
  }

</style>