<template>
  <v-card>
    <v-layout>
      <v-main class="mx-3">
        <router-view style="background-color: black"></router-view>
      </v-main>

      <v-app-bar app color="#1C232C" dark>
        <v-list-item :prepend-avatar="user.url" nav @click="showProfile">
        </v-list-item>
        <v-toolbar-title dark class="headline">
          <h4 class="text-yellow">{{ user.name }}</h4>
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <!-- <v-btn icon="mdi-dots-vertical" @click="showProfile"></v-btn> -->
      </v-app-bar>

      <v-navigation-drawer v-model="state.drawer" :rail="state.rail" permanent @click="state.rail = false"
        location="left" color="#1C232C">
        <v-list-item nav>
          <template v-slot:append>
            <v-btn class="text-white" variant="text" icon="mdi-menu" @click.stop="state.rail = !state.rail"></v-btn>
          </template>
        </v-list-item>

        <v-list density="compact" nav>
          <router-link to="/reservasi" custom v-slot="{ navigate }">
            <v-list-item class="text-yellow" prepend-icon="mdi-folder" title="Reservasi" value="reservasi"
              @click="navigate" role="link"></v-list-item>
          </router-link>
          <router-link to="/layanan" custom v-slot="{ navigate }">
            <v-list-item class="text-yellow" prepend-icon="mdi-folder" title="Layanan" value="layanan" @click="navigate"
              role="link"></v-list-item>
          </router-link>
          <router-link to="/produk" custom v-slot="{ navigate }">
            <v-list-item class="text-yellow" prepend-icon="mdi-folder" title="Produk" value="produk" @click="navigate"
              role="link"></v-list-item>
          </router-link>




          <v-list-item class="text-yellow" prepend-icon="mdi-logout" title="Logout" value="logout"
            @click="state.logoutDialog = true" role="link"></v-list-item>





        </v-list>
      </v-navigation-drawer>
    </v-layout>
  </v-card>

  <v-dialog v-model="state.dialogProfile">
    <v-card class="mx-auto" rounded="0">
      <v-avatar cover color="grey" size="250" rounded="0">
        <v-img cover :src="user.url">
          <template v-slot:placeholder>
            <div class="d-flex align-center justify-center fill-height">
              <v-progress-circular indeterminate color="grey-lighten-4"></v-progress-circular>
            </div>
          </template>
        </v-img>
      </v-avatar>
      <v-list-item>
        <v-list-item-title class="text-h7 text-white">{{
            user.name
        }}</v-list-item-title>
        <v-list-item-subtitle class="text-h9 text-white">Email : {{
            user.email
        }}</v-list-item-subtitle>
        <v-list-item-subtitle class="text-h9 text-white">No Telepon : {{
            user.no_telp
        }}</v-list-item-subtitle>
        <v-card-actions class="justify-center">
          <v-btn color="yellow darken-1" text @click="editProfile">Edit</v-btn>
        </v-card-actions>
      </v-list-item>
    </v-card>
  </v-dialog>

  <v-dialog v-model="state.dialogEditProfile" max-width="600px" persistent>
    <v-card color="black">
      <v-toolbar color="#1C232C" dark class="headline">
        <h2 class="text-yellow mx-5">Edit Profile</h2>
      </v-toolbar>
      <v-card-text>
        <v-container>
          <v-form ref="form" v-model="state.validProfile" lazy-validation>
            <v-text-field v-model="state.formTodoEditProfile.nama_profile" label="Nama Profile" persistent-hint
              variant="underlined" color="#FBDF7E" required></v-text-field>
            <v-text-field v-model="state.formTodoEditProfile.no_telp" label="Nomor Telepon" persistent-hint
              variant="underlined" color="#FBDF7E" required></v-text-field>
            <v-file-input @change="onFileChange" label="Upload Image" accept="image/*" persistent-hint
              variant="underlined" show-size color="#FBDF7E" required multipart></v-file-input>
          </v-form>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="cancelEditProfile">
          Cancel</v-btn>
        <v-btn color="blue darken-1" text @click="validateSaveEditProfile">
          Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <v-dialog v-model="state.logoutDialog" persistent max-width="400px">
    <v-card>
      <v-card-title style="background: transparent">
        <span class="headline text-white"><strong>Are you sure to logout ?</strong></span>
      </v-card-title>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="red darken-1" text @click="logoutConfirm">YES</v-btn>
        <v-btn color="blue darken-1" text @click="state.logoutDialog = false">No</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import { onMounted, reactive } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
export default {

  setup() {
    const state = reactive({
      logoutDialog: false,
      validProfile: true,
      drawer: true,
      timeout: 1000,
      indexItem: null,
      dialogIndex: -1,
      dialogProfile: false,
      dialogEditProfile: false,
      rail: true,
      items: [
        { title: "Home", icon: "mdi-home-city" },
        { title: "My Account", icon: "mdi-account" },
        { title: "Users", icon: "mdi-account-group-outline" },
      ],
      formTodoEditProfile: {
        nama_profile: null,
        no_telp: null,
        image: undefined,
      },

      namaProfileRules: [(v) => !!v || "Nama Customer harus diisi"],
      emailRules: [(v) => !!v || "Email harus diisi"],
    });

    const user = reactive({
      name: "",
      email: "",
      password: "",
      no_telp: "",
      url: undefined,
    });

    const id = localStorage.getItem("id");
    const access_token = localStorage.getItem("token");

    const router = useRouter();

    onMounted(() => {
      getUser();
    });


    const showProfile = () => {
      state.dialogProfile = true;
    };


    const editProfile = () => {
      state.dialogProfile = false;
      state.dialogEditProfile = true;
      state.formTodoEditProfile = {
        nama_profile: user.name,
        no_telp: user.no_telp,
        image: undefined,
      };
    };


    const cancelEditProfile = () => {
      state.dialogEditProfile = false;
    };


    const validateSaveEditProfile = () => {



      const formData = new FormData();
      formData.append("name", state.formTodoEditProfile.nama_profile);
      formData.append("nomor_telepon", state.formTodoEditProfile.no_telp);
      formData.append("image", state.formTodoEditProfile.image);

      axios
        .post("https://davianop.com/UAS_WEB/public/api/update/" + id, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: `Bearer ${access_token}`,
          },
        })
        .then((res) => {
          console.log(res.data.message);
          saveEditProfile();
        })
        .catch((err) => {
          console.log(err.response.data);
        });
    };


    const saveEditProfile = () => {
      state.dialogEditProfile = false;
      state.dialogProfile = true;
      getUser();
    };


    const onFileChange = (e) => {
      state.formTodoEditProfile.image = e.target.files[0];
    };


    const getUser = () => {
      axios
        .get("https://davianop.com/UAS_WEB/public/api/user/" + id, {
          headers: {
            Authorization: `Bearer ${access_token}`,
          },
        })
        .then((response) => {
          user.name = response.data.user.name;
          user.email = response.data.user.email;
          user.no_telp = response.data.user.nomor_telepon;
          user.url =
            "https://davianop.com/UAS_WEB/public/storage/users/" + response.data.user.image;
        })
        .catch((error) => {
          if (error.response.status == 401) {
            router.push("/login");
          } else {
            console.log(error.response.data);
          }
        });
    };


    const logout = () => {
      axios
        .get("https://davianop.com/UAS_WEB/public/api/logout", {
          headers: {
            Authorization: `Bearer ${access_token}`,
          },
        })
        .then((res) => {
          console.log(res.data);
          localStorage.removeItem("id");
          localStorage.removeItem("token");
          router.push("/login");
        })
        .catch((err) => {
          console.log(err.response.data);
        });
    };

    const logoutConfirm = () => {
      state.logoutDialog = false;
      logout();
    };

    return {
      state,
      showProfile,
      editProfile,
      cancelEditProfile,
      validateSaveEditProfile,
      saveEditProfile,
      onFileChange,
      user,
      getUser,
      logout,
      logoutConfirm,
    };
  },

};
</script>

<style>
.v-list-item__title {
  font-size: 14px;
}

body {
  background-color: rgb(0, 0, 0);
}
</style>
