<template>
  <div>
    <md-table v-model="eventsError" :table-header-color="tableHeaderColor">
      <md-table-row slot="md-table-row" slot-scope="{ item }">
        <md-table-cell md-label="#">{{ item.id }}</md-table-cell>
        <md-table-cell md-label="Evento">{{ item.evento }}</md-table-cell>
        <md-table-cell md-label="Fecha - Hora">{{ item.created_at }}</md-table-cell>
      </md-table-row>
    </md-table>
  </div>
</template>

<script>
export default {
  name: 'table-event-info',
  props: {
    tableHeaderColor: {
      type: String,
      default: ''
    }
  },
  created(){
    this.getFiveLastInfo();
  },
  data () {
    return {
      selected: [],
      eventsError: []
    }
  },
  methods:{
    getFiveLastInfo(){
      axios.get('/marks/info/five').then(response => { this.eventsError = response.data; }).catch(errors => { console.log(errors); })
    },
    actualizar: function () {
      setInterval(this.getFiveLastInfo, 10000);
   },
    mounted(){
    this.actualizar()
  }
  }
}
</script>
