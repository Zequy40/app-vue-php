<script setup>
import { ref, defineProps, onMounted } from "vue";
import axios from "axios";

defineProps({
  msg: String,
});

const cliente = ref([]);
const apiUrl = "http://localhost:8080/";

const cargarClientes = async () => {
  const respuesta = await axios.get(apiUrl);
  cliente.value = respuesta.data;
};
const newClient = ref({ name: "", lastname: "", mail: "" });
const createClient = async () => {
  try {
    const resp = await axios.post(apiUrl, newClient.value);
    cliente.value.push(resp.data);
    alert(`El Cliente fue creado exitosamente 🥳🥳`);
    newClient.value = { name: "", lastname: "", mail: "" };
    cargarClientes();
  } catch (error) {
    console.log("Error al crear el Cliente");
    alert("Ocurrio un error al crear el Cliente");
  }
};
const isEditing = ref(false);
const editedClient = ref(null);
const updateClient = async (id) => {
  isEditing.value = true;
  if  (!isEditing.value) return;
  editedClient.value = cliente.value.find((item) => item.id === id);
  //console.log(editedClient.value);
};
const cancelEdition = () => {
  isEditing.value= false;
  editedClient.value = null;
};
const saveChanges = async () =>{
  try {
    await axios.put(`${apiUrl}/${editedClient.value.id}`, editedClient.value);
    isEditing.value = false;
    editedClient.value = null;
    cargarClientes();
    alert('Se han guardado los cambios');
  }catch(err){
    console.log(err);
    alert('No se pudieron guardar los cambios');
  }
  
};
const deleteClient = async (id) => {
  let confirmacion = confirm("¿Desea eliminar este registro?");
  if (!confirmacion) return;
  else {
    try {
      await axios.delete(`${apiUrl}${id}`);
      cargarClientes();
    } catch (error) {
      console.log("No se pudo eliminar el Cliente");
      alert("No se pudo eliminar el Cliente");
    }
  }
};

onMounted(() => {
  cargarClientes();
});
</script>

<template>
  <h1>{{ msg }}</h1>

  <div id="app">
    <div class="container">
      <div class="row">
        <div class="col-6">
          <div class="card">
            <div class="card-header">Clientes</div>
            <div class="card-body">
              <form
                @submit.prevent="isEditing ? saveChanges() : createClient()"
              >
                Nombre:
                <input
                  class="form-control"
                  type="text"
                  name="name"
                  :value="
                    isEditing ? editedClient.name : newClient.name
                  "
                  @input="
                    isEditing
                      ? (editedClient.name = $event.target.value)
                      : (newClient.name = $event.target.value)
                  "
                  id="name"
                  required
                />
                Apellidos:
                <input
                  class="form-control"
                  type="text"
                  name="lastname"
                  :value="
                    isEditing ? editedClient.lastname : newClient.lastname
                  "
                  @input="
                    isEditing
                      ? (editedClient.lastname = $event.target.value)
                      : (newClient.lastname = $event.target.value)
                  "
                  id="lastname"
                  required
                />
                Email:
                <input
                  class="form-control"
                  type="email"
                  name="email"
                  :value="
                    isEditing ? editedClient.mail : newClient.mail
                  "
                  @input="
                    isEditing
                      ? (editedClient.mail = $event.target.value)
                      : (newClient.mail = $event.target.value)
                  "
                  id="email"
                  required
                /><br /><br />
                <button class="btn btn-success" type="submit">
                  {{ isEditing ? "Actualizar Cliente" : "Crear Cliente" }}
                </button>&nbsp;
                <button v-if="isEditing" class="btn btn-danger" type="button" @click="cancelEdition()">Cancelar</button>
              </form>
            </div>
            <div class="card-footer">ZekiDev</div>
          </div>
        </div>
        <div class="col-6">
          <div class="card">
            <div class="card-header">Lista de los clientes</div>
            <div class="card-body">
              <ul class="list-group mt-3">
                <li
                  class="list-group-item d-flex align-items-center"
                  v-for="clientes in cliente"
                  :key="clientes.id"
                >
                  <button
                    class="btn btn-danger btn-sm"
                    @click="deleteClient(clientes.id)"
                  >
                    Eliminar</button
                  >&nbsp;
                  <button
                    class="btn btn-warning btn-sm"
                    @click="updateClient(clientes.id)"
                  >
                    Editar
                  </button>
                  &nbsp; &nbsp;
                  {{ clientes.name }} {{ clientes.lastname }}
                  &nbsp;
                  <span> - Email: {{ clientes.mail }}</span>
                </li>
              </ul>
            </div>
            <div class="card-footer text-muted"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.read-the-docs {
  color: #888;
}
</style>
