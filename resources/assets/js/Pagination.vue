<template>
<div id="paginacion">
    <span>{{pagination.from}} - {{pagination.to}} de {{filtered.length}}</span>
    <md-button class="md-icon-button md-dense pull-right md-primary" @click="$emit('next');"><md-icon>keyboard_arrow_right</md-icon></md-button>
    <md-button class="md-icon-button md-dense pull-right md-primary" @click="$emit('prev');"><md-icon>keyboard_arrow_left</md-icon></md-button>
</div>
</template>

<script>
    export default {
        props: ['pagination', 'client', 'filtered']
    };
</script>

