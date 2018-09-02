<template>
  <footer class="footer">
    <div class="container">
        <nav>
            <ul>
              <li>
                <router-link :to="{path:'/dashboard'}">Panel de Control</router-link>
              </li>
                <li>
                    <a href="#">
                        Relaciones
                    </a>
                </li>
                <li>
                    <a href="#">
                        Empleados
                    </a>
                </li>
                <li>
                    <a href="#">
                        Horarios
                    </a>
                </li>
            </ul>
        </nav>
        <div class="copyright text-center">
          &copy; {{ new Date().getFullYear() }} <a href="https://www.facebook.com/r0mer02" target="_blank">Fernando</a>, Desarrollado con <i class="fa fa-heart heart"></i> para el mundo =)
        </div>
    </div>
  </footer>

</template>
<script>
export default {}

</script>
<style>

</style>
