<template>

  <header>
  </header>
  <v-card class="my-card">
    <v-layout>
      <v-navigation-drawer 
        expand-on-hover
        rail
      
        
      >
        <v-list>
          
          <v-list-item
            prepend-avatar="https://cdn3.iconfinder.com/data/icons/vector-icons-6/96/256-512.png"
            :title="userData.email" 
            :subtitle="userData.rol"

          ></v-list-item>
          
        </v-list>


        <v-list density="compact" nav>
          <v-list-item prepend-icon="mdi-home-city" title=" Inicio " @click="$router.push('/dashboard')"></v-list-item>

          <v-list-item prepend-icon="mdi-location-exit" title="Salir" @click="logout"></v-list-item>
          
        </v-list>
      </v-navigation-drawer>

    </v-layout>
  </v-card>
</template>


<style scoped>
.my-card {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  background-color: rgba(255, 255, 255, 0.9); /* Color de fondo semi-transparente */
  z-index: 9999; /* Coloca la tarjeta en la parte superior */
  /* Otros estilos de diseño de la tarjeta */
}
</style>

<script>
import Swal from 'sweetalert2';

export default {
  data() {
    return {
      userData: {
        nombre: '',
        rol: '',
        // Otros campos del usuario según la respuesta del servidor
      },
    };
  },
  created() {
    // Realiza una solicitud GET para obtener los datos del usuario
    axios.get('/api/user')
      .then(response => {
        this.userData = response.data;
      })
      .catch(error => {
        console.error('Error al obtener los datos del usuario:', error);
      });
  },
  methods: {
    logout() {
      axios.post('/api/logout')
        .then(response => {
          if (response) {
            Swal.fire({
              icon: 'success',
              title: '¡Hasta pronto!',
              text: 'Has cerrado sesión exitosamente.',
              showConfirmButton: false,
              timer: 2000 // Cambia el tiempo que deseas que aparezca la alerta
            });
            window.location.reload();
          }
          localStorage.removeItem('access_token');
        })
        .catch(error => {
          console.error('Error al cerrar sesión:', error);
        });
    },
  },
};
</script>