<template>
  <header>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <navegacion></navegacion>
  </header>
  <v-app>
    <appbar></appbar>
    <v-card-item class="bg-white">
      <v-theme-provider theme="light" with-background class="pa-5">
        <v-card
          title=" Administrador  PDF"
          prepend-icon="mdi-file-chart-outline"
          subtitle="Subir Pdf "
        ></v-card>
      </v-theme-provider>
    </v-card-item>
    <v-row justify="center">
      <v-col cols="7">
        <v-card color="white" theme="light" class="mt-4">
          <v-card-title class="bg-white text-h5 font-weight-regular">
            Subir archivos
          </v-card-title>
          <body>
            <form
              action="/subir-pdf"
              method="post"
              enctype="multipart/form-data"
            >
              <label
                for="dropzone-file"
                class="flex flex-col items-center w-full max-w-lg p-6 mx-auto text-center bg-white border-2 border-blue-400 border-dashed cursor-pointer rounded-xl"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-10 h-10 text-blue-500"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                  />
                </svg>

                <h2 class="mt-4 text-xl font-medium tracking-wide text-gray-700">
                  PDf
                </h2>

                <p class="mt-2 tracking-wide text-gray-500">
                  Subir pdf
                </p>
 <br><br><br>
                <input
                  id="dropzone-file"
                  type="file"
                  class="hidden"
                  @change="handleFileChange"
                />
              </label>

              <button
                class="relative w-48 h-12 overflow-hidden text-lg font-bold text-white bg-green-500 group rounded-2xl"
                type="submit"
                @click.prevent="subirPDF"
              >
                Subir PDF
              </button>
            </form>

            <div v-if="selectedFile">
              <p class="mt-4">
                Archivo seleccionado: {{ selectedFile.name }}
                <img src=".././assets/pdf.jpeg" alt="Logo PDF" class="w-6 h-6 ml-2" />              </p>
            </div>

            <div v-if="uploading">
              <p class="mt-4">Cargando archivo...</p>
            </div>

            <div v-if="uploadSuccess">
              <p class="mt-4 text-green-500">¡Archivo subido correctamente!</p>
            </div>

            <script>
              // Agrega el token CSRF a las solicitudes Axios
              axios.defaults.headers.common[
                "X-CSRF-TOKEN"
              ] = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            </script>
             <br>
          </body> <br><br>
          <Tabladescargas></Tabladescargas>
        </v-card>
      </v-col>
    </v-row>
  </v-app>
</template>


<style>
@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";
</style>

<script>
import axios from "axios";
import navegacion from "./barra_navegacion.vue";
import appbar from "./app_bar.vue";
import Tabladescargas from "./descargas/Tabladescargas.vue";
import Swal from "sweetalert2";

export default {
  data() {
  return {
    selectedFile: null, // Almacena el archivo seleccionado
    uploading: false, // Bandera para controlar el estado de la carga
    uploadSuccess: false, // Bandera para indicar si la carga fue exitosa
    visible: true, // Nuevo estado para manejar la visibilidad
  };
},
  methods: {
    actualizarVisibilidad() {
    // Aquí debes enviar una solicitud al servidor para actualizar la visibilidad
    axios
      .post("/contenidos/{id}/visibilidad", { visible: this.visible })
      .then((response) => {
        console.log(response);
        Swal.fire({
          icon: 'success',
          title: 'Visibilidad actualizada correctamente',
          showConfirmButton: true,
          timer: 3000
        });
      })
      .catch((error) => {
        console.error(error.response.data);
        Swal.fire({
          icon: 'error',
          title: 'Error al actualizar la visibilidad',
          showConfirmButton: true,
          timer: 3000
        });
      });
  },
    handleFileChange(event) {
      // Almacena el archivo seleccionado en la propiedad de datos
      this.selectedFile = event.target.files[0];
    },
    subirPDF() {
  // Validar si hay un archivo seleccionado
  if (!this.selectedFile) {
    Swal.fire({
              icon: 'error' ,
              title: 'Selecciona un archivo .PDF',
              showConfirmButton: true,
              timer: 3000 
              
            });    return;
  }

  // Crear un objeto FormData
  const formData = new FormData();

  // Adjuntar el archivo al FormData
  formData.append("archivo", this.selectedFile);

  // Habilitar la bandera de carga
  this.uploading = true;

  // Enviar la solicitud al servidor
 
  axios
      .post("/subir-pdf", formData, {})
      .then((response) => {
        console.log(response);
        Swal.fire({
          icon: 'success',
          title: 'Se ha subido exitosamente',
          showConfirmButton: true,
          timer: 3000
        });
        this.uploadSuccess = true;
        this.visible = response.data.visible;  // Actualiza el estado de visibilidad
      })
      .catch((error) => {
        console.error(error.response.data);
        Swal.fire({
          icon: 'error',
          title: 'Verifica el nombre sin espacios y que sea PDF ',
          showConfirmButton: true,
          timer: 3000
        });
      })
      .finally(() => {
        this.uploading = false;
      });
  },
  },
  components: {
    navegacion,
    appbar,
    Tabladescargas,
  },
};
</script>
