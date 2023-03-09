<template>
  <v-card color="#1C232C" max-width="150">
    <v-list-item>
      <v-list-item-content>
        <v-list-item-title class="headline mt-5 mb-5">
          <h2 class="text-white" disabled>Reservasi</h2>
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
          <v-btn @click="state.dialog = true" tile dark class="text-yellow mt-2" variant="outlined">Tambah Reservasi
          </v-btn>
        </v-col>
      </v-row>

      <v-spacer></v-spacer>

      <!-- tabel reservasi  -->
      <v-table theme="dark">
        <thead>
          <tr>
            <th class="text-left">Nama Customer</th>
            <th class="text-left">Layanan</th>
            <th class="text-left">Sesi</th>
            <th class="text-left">Tanggal</th>
            <th class="text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in state.todos" :key="item.nama">
            <td>{{ item.nama_customer }}</td>
            <td>{{ item.layanans.nama }}</td>
            <td>{{ item.sesi }}</td>
            <td>{{ formatDate(item.tanggal) }}</td>
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
        <h2 class="text-yellow mx-5">Tambah Reservasi</h2>
      </v-toolbar>
      <v-card-text>
        <v-container>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-text-field v-model="state.formTodo.nama_customer" label="Nama Customer" persistent-hint
              variant="underlined" color="#FBDF7E" required :rules="namaCustomerRules"></v-text-field>

            <v-select v-model="state.formTodo.layanan" :items="state.layanan" item-title="nama" item-value="id"
              label="Layanan" persistent-hint return-object variant="underlined" color="#FBDF7E"></v-select>

            <v-select v-model="state.formTodo.sesi" label="Sesi" persistent-hint variant="underlined" color="#FBDF7E"
              required :items="[1, 2, 3, 4]" :rules="sesiRules"></v-select>

            <Datepicker :min-date="new Date()" :enable-time-picker="false" v-model="state.formTodo.tanggal" dark>
            </Datepicker>

          </v-form>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="cancelAdd"> Cancel</v-btn>
        <v-btn color="blue darken-1" text @click="saveAdd"> Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <!-- Edit Data -->
  <v-dialog v-model="state.dialogEdit" persistent max-width="600px">
    <v-card color="black">
      <v-toolbar color="#1C232C" dark class="headline">
        <h2 class="text-yellow mx-5">Edit Reservasi</h2>
      </v-toolbar>
      <v-card-text>
        <v-container>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-text-field v-model="state.formTodo.nama_customer" label="Nama Customer" persistent-hint
              variant="underlined" color="#FBDF7E" required :rules="namaCustomerRules"></v-text-field>

            <v-select v-model="state.formTodo.layanan" :items="state.layanan" item-title="nama" item-value="id"
              label="Layanan" persistent-hint return-object variant="underlined" color="#FBDF7E"></v-select>

            <v-select v-model="state.formTodo.sesi" label="Sesi" persistent-hint variant="underlined" color="#FBDF7E"
              required :items="[1, 2, 3, 4]" :rules="sesiRules"></v-select>


            <Datepicker :min-date="new Date()" :enable-time-picker="false" v-model="state.formTodo.tanggal" dark>
            </Datepicker>
          </v-form>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="cancelUpdate"> Cancel </v-btn>
        <v-btn color="blue darken-1" text @click="editReservasi"> Save </v-btn>
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
import { onMounted, reactive, ref } from "vue";
import axios from "axios";
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const access_token = localStorage.getItem("token");
export default {
  nama: "ReservasiList",

  components: {
    Datepicker
  },


  setup() {
    const date = ref(new Date());
    const state = reactive({
      layanan: [],
      todos: [],
      dialog: false,
      dialogEdit: false,
      deleteDialog: false,
      valid: true,
      deleteIndex: -1,
      dialogLoading: false,
      formTodo: {
        nama_customer: "",
        layanan: undefined,
        sesi: undefined,
        tanggal: "",
      },
      namaCustomerRules: [
        (v) => !!v || "Nama Customer is required",
        (v) =>
          (v && v.length <= 20) ||
          "Nama Customer must be less than 20 characters",
      ],
      keteranganRules: [
        (v) => !!v || "Keterangan is required",
        (v) =>
          (v && v.length <= 20) || "Keterangan must be less than 20 characters",
      ],
      sesiRules: [
        (v) => !!v || "Sesi is required",
        (v) => (v && v.length <= 20) || "Sesi must be less than 20 characters",
      ],
      tanggalRules: [
        (v) => !!v || "Tanggal is required",
        (v) =>
          (v && v.length <= 20) || "Tanggal must be less than 20 characters",
      ],
      select: {},
    });

    onMounted(() => {
      showReservasi();
      showLayanan();
    });

    const formatDate = (date) => {
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(date).toLocaleDateString('id-ID', options);
    }

    function showReservasi() {
      state.dialogLoading = true;
      axios
        .get("https://davianop.com/UAS_WEB/public/api/reservasi", {
          headers: {
            Authorization: `Bearer ${access_token}`,
          },
        })
        .then((response) => {
          state.todos = response.data.data;
          state.dialogLoading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    }

    function showLayanan() {
      axios
        .get("https://davianop.com/UAS_WEB/public/api/layanan", {
          headers: {
            Authorization: `Bearer ${access_token}`,
          },
        })
        .then((response) => {
          state.layanan = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    }

    function addReservasi() {
      state.dialogLoading = true;
      axios
        .post(
          "https://davianop.com/UAS_WEB/public/api/reservasi",
          {
            nama_customer: state.formTodo.nama_customer,
            id_layanan: state.formTodo.layanan.id,
            sesi: state.formTodo.sesi,
            tanggal: convertDate(),
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
          showReservasi();
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    }

    function editReservasi() {
      state.dialogLoading = true;
      axios
        .put(
          `https://davianop.com/UAS_WEB/public/api/reservasi/${state.formTodo.id}`,
          {
            nama_customer: state.formTodo.nama_customer,
            id_layanan: state.formTodo.layanan.id,
            sesi: state.formTodo.sesi,
            tanggal: convertDate(),
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
          showReservasi();
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    }

    function cancelAdd() {
      state.dialog = false;
    }

    function saveAdd() {
      addReservasi();
    }

    function cancelUpdate() {
      resetForm();
      state.dialogEdit = false;
    }

    function editItem(item) {

      state.formTodo = {
        id: item.id,
        nama_customer: item.nama_customer,
        layanan: item.layanans,
        sesi: item.sesi,
        tanggal: item.tanggal,
      };
      state.dialogEdit = true;
    }

    function deleteItemConfirm() {
      state.dialogLoading = true;
      axios
        .delete("https://davianop.com/UAS_WEB/public/api/reservasi/" + state.deleteIndex, {
          headers: {
            Authorization: `Bearer ${access_token}`,
          },
        })
        .then((response) => {
          state.dialogLoading = false;
          console.log(response);
          showReservasi();
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

    function cancel() {
      state.dialog = false;
      state.deleteDialog = false;
    }

    function resetForm() {
      // code untuk mereset form
      state.formTodo = {
        nama_customer: "",
        layanan: undefined,
        sesi: undefined,
        tanggal: "",
      };
    }

    function convertDate() {
      var date = new Date(state.formTodo.tanggal);
      var month = date.getMonth() + 1;
      var day = date.getDate();
      var year = date.getFullYear();
      var newDate = year + "-" + month + "-" + day;
      return newDate;
    }


    return {
      valid: true,
      search: null,
      dialog: false,
      timeout: 1000,
      dialogEdit: false,
      itemContent: [],
      indexItem: null,
      state,
      cancel,
      resetForm,
      showReservasi,
      showLayanan,
      addReservasi,
      editReservasi,
      cancelAdd,
      cancelUpdate,
      saveAdd,
      editItem,
      deleteItemConfirm,
      deleteItem,
      convertDate,
      formatDate,
      date
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
