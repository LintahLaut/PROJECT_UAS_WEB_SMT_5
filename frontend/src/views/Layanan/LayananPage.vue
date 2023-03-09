<template>
  <v-card color="#1C232C" max-width="150">
    <v-list-item>
      <v-list-item-content>
        <v-list-item-title class="headline mt-5 mb-5">
          <h2 class="text-white" disabled>Layanan</h2>
          <!-- <span class="text-grey">Tabel Data Reservasi</span><br /> -->
        </v-list-item-title>
      </v-list-item-content>
    </v-list-item>
  </v-card>

  <v-card color="#1C232C">
    <v-list-item>
      <v-row class="">
        <v-col cols="12" sm="6">
          <v-text-field v-model="search" label="Search" variant="underlined" class="text-yellow"
            append-icon="mdi-magnify" hide details></v-text-field>
        </v-col>
        <v-col cols="12" sm="6" class="mt-2">
          <v-btn @click="state.dialog = true" tile dark class="text-yellow mt-2" variant="outlined">Tambah Layanan
          </v-btn>
        </v-col>
      </v-row>

      <v-spacer></v-spacer>

      <!-- tabel  -->
      <v-table theme="dark">
        <thead>
          <tr>
            <th class="text-left">Nama Layanan</th>
            <th class="text-left">Harga</th>
            <th class="text-left">Deskripsi</th>
            <th class="text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in state.todos" :key="item.nama">
            <td>{{ item.nama }}</td>
            <td>{{ item.harga }}</td>
            <td>{{ item.deskripsi }}</td>
            <td>
              <!-- Edit Item -->
              <v-btn class="ma-2" outlined small color="success" @click="editItem(item)">
                <v-icon>mdi-pencil</v-icon>
              </v-btn>

              <!-- Delete Item  -->
              <v-btn class="ma-2" outlined small color="error" @click="deleteItem(item)">
                <v-icon>mdi-delete</v-icon>
              </v-btn>
            </td>
          </tr>
        </tbody>
      </v-table>
    </v-list-item>
  </v-card>

  <v-dialog v-model="state.deleteDialog" persistent max-width="400px">
    <v-card>
      <v-card-title style="background: transparent">
        <span class="headline text-white"><strong>Are you sure to Delete ?</strong></span>
      </v-card-title>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="red darken-1" text @click="deleteItemConfirm">YES</v-btn>
        <v-btn color="blue darken-1" text @click="cancel">No</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <!-- Tambah Data -->
  <v-dialog v-model="state.dialog" persistent max-width="600px">
    <v-card color="black">
      <v-toolbar color="#1C232C" dark class="headline">
        <h2 class="text-yellow mx-5">Tambah Layanan</h2>
      </v-toolbar>
      <v-card-text>
        <v-container>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-text-field v-model="state.formTodo.nama" label="Nama Layanan" persistent-hint variant="underlined"
              color="#FBDF7E" required :rules="namaRules"></v-text-field>
            <v-text-field v-model="state.formTodo.harga" label="Harga" persistent-hint variant="underlined"
              color="#FBDF7E" required :rules="hargaRules"></v-text-field>
            <v-textarea v-model="state.formTodo.deskripsi" label="Deskripsi" persistent-hint variant="underlined"
              color="#FBDF7E" required :rules="deskripsiRules"></v-textarea>
          </v-form>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="cancel"> Cancel</v-btn>
        <v-btn color="blue darken-1" text @click="validateSave"> Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <!-- Edit Data -->
  <v-dialog v-model="state.dialogEdit" persistent max-width="600px">
    <v-card color="black">
      <v-toolbar color="#1C232C" dark class="headline">
        <h2 class="text-yellow mx-5">Edit Layanan</h2>
      </v-toolbar>
      <v-card-text>
        <v-container>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-text-field v-model="state.formTodo.nama" label="Nama Layanan" persistent-hint variant="underlined"
              color="#FBDF7E" required :rules="namaRules"></v-text-field>
            <v-text-field v-model="state.formTodo.harga" label="Harga" persistent-hint variant="underlined"
              color="#FBDF7E" required :rules="hargaRules"></v-text-field>
            <v-textarea v-model="state.formTodo.deskripsi" label="Deskripsi" persistent-hint variant="underlined"
              color="#FBDF7E" required :rules="deskripsiRules"></v-textarea>
          </v-form>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="cancelUpdate"> Cancel </v-btn>
        <v-btn color="blue darken-1" text @click="validateSaveEdit">
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <v-dialog width="300px" :bottom="true" v-model="state.dialogLoading" :scrim="false" persistent>
    <v-card color="white">
      <v-card-text>
        Retrieve Data..
        <v-progress-linear indeterminate color="yellow" class="mb-0"></v-progress-linear>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
<script>
import { onMounted, reactive } from "vue";
import axios from "axios";
const access_token = localStorage.getItem("token");
export default {
  nama: "LayananList",
  // setup all
  setup() {
    const state = reactive({
      dialog: false,
      dialogEdit: false,
      valid: true,
      deleteDialog: false,
      todos: [],
      editedIndex: -1,
      deleteIndex: -1,
      dialogLoading: false,
      editedItem: {
        nama: "",
        harga: "",
        deskripsi: "",
      },
      defaultItem: {
        nama: "",
        harga: "",
        deskripsi: "",
      },
      formTodo: {
        nama: "",
        harga: "",
        deskripsi: "",
      },
      namaRules: [
        (v) => !!v || "Nama Layanan is required",
        (v) =>
          (v && v.length <= 20) ||
          "Nama Layanan must be less than 20 characters",
      ],
      hargaRules: [
        (v) => !!v || "Harga is required",
        (v) => (v && v.length <= 20) || "Harga must be less than 20 characters",
      ],
      deskripsiRules: [
        (v) => !!v || "Deskripsi is required",
        (v) =>
          (v && v.length <= 20) || "Deskripsi must be less than 20 characters",
      ],
    });

    onMounted(() => {
      showLayanan();
    });

    function save() {
      state.dialogLoading = true;
      axios
        .post(
          "https://davianop.com/UAS_WEB/public/api/layanan",
          {
            nama: state.formTodo.nama,
            harga: state.formTodo.harga,
            deskripsi: state.formTodo.deskripsi,
          },
          {
            headers: {
              Authorization: `Bearer ${access_token}`,
            },
          }
        )
        .then((response) => {
          state.dialogLoading = false;
          console.log(response);
          state.dialog = false;
          resetForm();

          showLayanan();
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    }

    function cancel() {

      state.dialog = false;
      state.deleteDialog = false;
    }
    function saveUpdate() {
      state.dialogLoading = true;
      axios
        .put(
          "https://davianop.com/UAS_WEB/public/api/layanan/" + state.editedIndex,
          {
            nama: state.formTodo.nama,
            harga: state.formTodo.harga,
            deskripsi: state.formTodo.deskripsi,
          },
          {
            headers: {
              Authorization: `Bearer ${access_token}`,
            },
          }
        )

        .then((response) => {
          state.dialogLoading = false;
          console.log(response);
          state.dialogEdit = false;
          resetForm();

          showLayanan();
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    }
    function cancelUpdate() {

      resetForm();
      state.dialogEdit = false;
    }
    function resetForm() {


      state.formTodo = {
        nama: null,
        layanan: null,
        sesi: null,
        tanggal: null,
      };
      state.formTodoEdit = {
        nama: null,
        layanan: null,
        sesi: null,
        tanggal: null,
      };
    }

    function deleteItemConfirm() {
      state.dialogLoading = true;
      axios
        .delete("https://davianop.com/UAS_WEB/public/api/layanan/" + state.deleteIndex, {
          headers: {
            Authorization: `Bearer ${access_token}`,
          },
        })
        .then((response) => {
          state.dialogLoading = false;
          console.log(response);
          showLayanan();
        })
        .catch((error) => {
          console.log(error.response.data);
        });
      state.deleteDialog = false;
    }
    function deleteItem(item) {
      state.deleteDialog = true;
      state.deleteIndex = item.id;
    }

    function editItem(item) {
      state.editedIndex = item.id;
      state.formTodo = { ...item };
      state.dialogEdit = true;
    }
    function validateSave() {
      save();
    }


    function validateSaveEdit() {
      if (
        state.formTodo.nama == null ||
        state.formTodo.harga == null ||
        state.formTodo.deskripsi == null
      ) {
        alert("Data tidak boleh kosong");
      } else {
        saveUpdate();
      }
    }
    function showLayanan() {
      state.dialogLoading = true;
      axios
        .get("https://davianop.com/UAS_WEB/public/api/layanan", {
          headers: {
            Authorization: `Bearer ${access_token}`,
          },
        })
        .then((response) => {
          state.dialogLoading = false;
          state.todos = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    }

    return {
      state,
      save,
      cancel,
      saveUpdate,
      cancelUpdate,
      resetForm,
      deleteItemConfirm,
      deleteItem,
      editItem,
      validateSave,
      validateSaveEdit,
      showLayanan,
    };
  },


};
</script>
<style>
.v-card {
  background-color: rgb(0, 0, 0);
  margin-top: 10px;
  margin-bottom: 10px;
}

.v-table {
  border-radius: 10px;
  background-color: hsl(0, 0%, 0%, 0);
}

.text {
  font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
  font-size: 40px;
  font-style: italic;
}
</style>
