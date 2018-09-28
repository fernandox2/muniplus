<template>
  <div class="wrapper">
    <notifications></notifications>

    <side-bar>
      <mobile-menu slot="content"></mobile-menu>
      <sidebar-link to="/dashboard">
        <md-icon>dashboard</md-icon>
        <p>Panel de Control</p>
      </sidebar-link>
      <sidebar-link to="/departament">
        <md-icon>business</md-icon>
        <p>Departamentos</p>
      </sidebar-link>
      <sidebar-link to="/employee">
        <md-icon>person</md-icon>
        <p>Funcionarios</p>
      </sidebar-link>
      <sidebar-link to="/exception">
        <md-icon>warning</md-icon>
        <p>Excepciones</p>
      </sidebar-link>
      <sidebar-link to="/schedule">
        <md-icon>schedule</md-icon>
        <p>Horarios</p>
      </sidebar-link>
      <sidebar-link to="/relationship">
        <md-icon>swap_horiz</md-icon>
        <p>Relaciones</p>
      </sidebar-link>
      <sidebar-link to="/turn">
        <md-icon>group</md-icon>
        <p>Turnos</p>
      </sidebar-link>
      <sidebar-link to="/assistance">
        <md-icon>fingerprint</md-icon>
        <p>Registros de Asistencia</p>
      </sidebar-link>
    </side-bar>

    <div class="main-panel">
      <top-navbar></top-navbar>

      <dashboard-content>

      </dashboard-content>

      <content-footer v-if="!$route.meta.hideFooter"></content-footer>
    </div>
  </div>
</template>
<style lang="scss">
</style>
<script>
import TopNavbar from './TopNavbar.vue'
import ContentFooter from './ContentFooter.vue'
import DashboardContent from './Content.vue'
import MobileMenu from '@/pages/Layout/MobileMenu.vue'
export default {
  components: {
    TopNavbar,
    DashboardContent,
    ContentFooter,
    MobileMenu
  }
}
</script>