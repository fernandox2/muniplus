<style lang="scss">
.pagination {
    justify-content: flex-end !important;
    .page-stats {
        align-items: center;
        margin-right: 5px;
    }
    i {
        color: #3273dc !important;
    }
}
</style>

<template>
<nav aria-label="Page navigation example">
          <span class="page-stats">
            {{pagination.from}} - {{pagination.to}} de {{filtered.length}}
            <!--<span v-if="filtered.length < pagination.total">(Filtrado de {{pagination.total}} registros totales)</span>-->
        </span>
  <ul class="pagination">
    <li class="page-item"><a v-if="pagination.prevPage" class="page-link" @click="$emit('prev');">Anterior</a></li>
    <li class="page-item"><a v-if="pagination.nextPage" class="page-link" @click="$emit('next');">Siguiente</a></li>
  </ul>
</nav>
</template>

<script>
    export default {
        props: ['pagination', 'client', 'filtered']
    }
</script>