<template>
  <v-card color="#1C232C" max-width="150">
    <v-list-item>
      <v-list-item-content>
        <v-list-item-title class="headline mt-5 mb-5">
          <h2 class="text-white" disabled>Produk</h2>
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
          <v-btn @click="state.dialog = true" tile dark class="text-yellow mt-2" variant="outlined">Tambah Produk
          </v-btn>
        </v-col>
      </v-row>

      <v-spacer></v-spacer>
    </v-list-item>
  </v-card>

  <v-item-group>
    <v-container>
      <v-row>
        <v-col v-for="item in state.todos" :key="item.nama" cols="12" md="3">
          <v-card :loading="loading" class="mt-10" max-width="300" color="#1C232C">


            <v-img cover height="250" :src="'https://davianop.com/UAS_WEB/public/storage/produks/' + item.gambar">

              <template v-slot:placeholder>
                <div class="d-flex align-center justify-center fill-height">
                  <v-progress-circular indeterminate color="grey-lighten-4"></v-progress-circular>
                </div>
              </template>
            </v-img>

            <v-card-title class="text-yellow"><strong> {{ item.nama }} </strong></v-card-title>
            <v-card-text class="text-yellow">
              Price : Rp. {{ item.harga }}</v-card-text>
            <v-card-subtitle class="text-white">
              {{ item.jenis }}</v-card-subtitle>
            <v-card-subtitle class="text-grey">
              {{ item.stok }}</v-card-subtitle>

            <v-card-actions>
              <!-- Edit Item -->
              <v-btn class="ma-2" outlined small color="success" @click="editItem(item)">
                <v-icon>mdi-pencil</v-icon>
              </v-btn>

              <!-- Delete Item  -->
              <v-btn class="ma-2" outlined small color="error" @click="deleteItem(item)">
                <v-icon>mdi-delete</v-icon>

              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-item-group>

  <v-dialog v-model="state.deleteDialog" persistent max-width="400px">
    <v-card>
      <v-card-title style="background: transparent">
        <span class="headline text-white"><strong>Are you sure to delete ?</strong></span>
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
        <h2 class="text-yellow mx-5">Tambah Produk</h2>
      </v-toolbar>
      <v-card-text>
        <v-container>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-text-field v-model="state.formTodo.nama" label="Nama Produk" persistent-hint variant="underlined"
              color="#FBDF7E" required :rules="namaRules"></v-text-field>
            <v-text-field v-model="state.formTodo.stok" label="Stok" persistent-hint variant="underlined"
              color="#FBDF7E" required :rules="stokRules" type="number"></v-text-field>
            <v-select v-model="state.formTodo.jenis" label="Jenis" persistent-hint variant="underlined" color="#FBDF7E"
              required :items="['Haircare', 'Skincare']" :rules="jenisRules"></v-select>
            <v-text-field v-model="state.formTodo.harga" label="Harga" persistent-hint variant="underlined"
              color="#FBDF7E" required :rules="hargaRules" type="number"></v-text-field>
            <v-file-input @change="onFileChange" v-model="state.formTodo.gambar" label="Upload Image" accept="image/*"
              persistent-hint variant="outlined" show-size color="#FBDF7E" multipart></v-file-input>
            <!-- <v-file-input v-model="formTodo.gambar" label="Gambar" accept="image/*" persistent-hint variant="underlined" color="#FBDF7E" show-size required :rules="gambarRules"></v-file-input> -->
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
        <h2 class="text-yellow mx-5">Edit Produk</h2>
      </v-toolbar>
      <v-card-text>
        <v-container>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-text-field v-model="state.formTodo.nama" label="Nama Produk" persistent-hint variant="underlined"
              color="#FBDF7E" required :rules="namaRules"></v-text-field>
            <v-text-field v-model="state.formTodo.stok" label="Stok" persistent-hint variant="underlined"
              color="#FBDF7E" required :rules="stokRules" type="number"></v-text-field>
            <v-select v-model="state.formTodo.jenis" label="Jenis" persistent-hint variant="underlined" color="#FBDF7E"
              required :items="['Shampoo', 'Masker', 'Skincare']" :rules="jenisRules"></v-select>
            <v-text-field v-model="state.formTodo.harga" label="Harga" persistent-hint variant="underlined"
              color="#FBDF7E" required :rules="hargaRules" type="number"></v-text-field>
            <v-file-input @change="onFileChange" label="Upload Image" accept="image/*" persistent-hint
              variant="underlined" show-size color="#FBDF7E" required multipart></v-file-input>
            <!-- <v-file-input v-model="formTodo.gambar" label="Gambar" accept="image/*" persistent-hint variant="underlined" color="#FBDF7E" show-size required :rules="gambarRules"></v-file-input> -->
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
  nama: "ProdukList",

  setup() {
    const state = reactive({
      dialog: false,
      dialogEdit: false,
      valid: true,
      deleteDialog: false,
      todos: [],
      editedIndex: -1,
      deleteIndex: -1,
      url: undefined,
      dialogLoading: false,
      formTodo: {
        nama: "",
        stok: "",
        jenis: "",
        harga: "",
        gambar: undefined,
        file: undefined,
      },
      namaRules: [
        (v) => !!v || "Nama Produk is required",
        (v) =>
          (v && v.length <= 20) ||
          "Nama Produk must be less than 20 characters",
      ],
      stokRules: [
        (v) => !!v || "Stok is required",
        (v) => (v && v.length <= 20) || "Stok must be less than 20 characters",
      ],
      jenisRules: [
        (v) => !!v || "Jenis is required",
        (v) => (v && v.length <= 20) || "Jenis must be less than 20 characters",
      ],
      hargaRules: [
        (v) => !!v || "Harga is required",
        (v) => (v && v.length <= 20) || "Harga must be less than 20 characters",
      ],
    });
    onMounted(() => {
      showProduk();
    });

    function save() {
      state.dialogLoading = true;

      const formData = new FormData();
      formData.append("nama", state.formTodo.nama);
      formData.append("stok", state.formTodo.stok);
      formData.append("jenis", state.formTodo.jenis);
      formData.append("harga", state.formTodo.harga);
      formData.append("gambar", state.formTodo.file);

      axios
        .post("https://davianop.com/UAS_WEB/public/api/produk", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: "Bearer " + access_token,
          },
        })
        .then((response) => {
          state.dialogLoading = false;
          resetForm();
          console.log(response);
          showProduk();
          console.log(state.dialog)

        })
        .catch((error) => {
          console.log(error.response.data);
          resetForm();
        });
    }

    function cancel() {

      state.dialog = false;
      state.deleteDialog = false;
    }
    function saveUpdate() {
      state.dialogLoading = true;

      const formData = new FormData();
      formData.append("nama", state.formTodo.nama);
      formData.append("stok", state.formTodo.stok);
      formData.append("jenis", state.formTodo.jenis);
      formData.append("harga", state.formTodo.harga);
      formData.append("gambar", state.formTodo.file);
      axios
        .post(
          "https://davianop.com/UAS_WEB/public/api/produk/" + state.editedIndex,
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
              Authorization: `Bearer ${access_token}`,
            },
          }
        )
        .then((response) => {
          state.dialogLoading = false;
          console.log(response);
          showProduk();
          state.dialogEdit = false;
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

      state.dialog = false;
      state.formTodo = {
        nama: "",
        stok: "",
        jenis: "",
        harga: "",
        gambar: undefined,
        file: undefined,
      };
    }

    function deleteItemConfirm() {
      state.dialogLoading = true;
      axios
        .delete("https://davianop.com/UAS_WEB/public/api/produk/" + state.deleteIndex, {
          headers: {
            Authorization: `Bearer ${access_token}`,
          },
        })
        .then((response) => {
          state.dialogLoading = false;
          console.log(response);
          showProduk();
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
      state.formTodo = {
        nama: item.nama,
        stok: item.stok,
        jenis: item.jenis,
        harga: item.harga,
      };
      state.dialogEdit = true;
    }
    function validateSave() {
      save();
    }


    function validateSaveEdit() {
      if (
        state.formTodo.nama == "" ||
        state.formTodo.stok == "" ||
        state.formTodo.jenis == "" ||
        state.formTodo.harga == ""
      ) {
        alert("Data tidak boleh kosong");
      } else {
        saveUpdate();
      }
    }

    function showProduk() {
      state.dialogLoading = true;
      axios
        .get("https://davianop.com/UAS_WEB/public/api/produk", {
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

    function onFileChange(e) {

      state.formTodo.file = e.target.files[0];
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
      showProduk,
      onFileChange,

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

.v-img {
  object-fit: contain;

}
</style>
