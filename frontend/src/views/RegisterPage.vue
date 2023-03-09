<template>
    <v-app id="register">
        <v-parallax dark
            src="https://images.pexels.com/photos/853427/pexels-photo-853427.jpeg?cs=srgb&dl=pexels-delbeautybox-853427.jpg&fm=jpg"
            height="800">
            <v-container>
                <v-row align="center" justify="center">
                    <v-col cols="12" sm="12">
                        <v-card class="elevation-6 mt-16" color="black">
                            <v-window>
                                <v-row>
                                    <v-col cols="12" md="6" class="orange rounded-br-xl">
                                        <div style="  text-align: center; padding: 180px 0;">
                                            <v-card-text class="white--text">
                                                <h1 class="text-center ">Alredy Signed up?</h1>
                                                <br>
                                                <h5 class="text-center">
                                                    Log in to your account so you can continue
                                                    building and<br> editing your onboarding flows
                                                </h5>
                                            </v-card-text>
                                            <div class="text-center">
                                                <router-link to="/login" custom v-slot="{ navigate }">
                                                    <v-btn @click="navigate" role="link" tile dark
                                                        class="text-yellow mt-8" variant="outlined">
                                                        LOG IN
                                                    </v-btn>
                                                </router-link>
                                            </div>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" md="6">

                                        <v-card-text class="mt-12">
                                            <h4 class="text-center">Sign Up for an Account</h4>
                                            <br>
                                            <v-row align="center" justify="center">
                                                <h6 class="text-center  grey--text ">
                                                    Let's get you all set up so you
                                                    can start creatin your <br> first onboarding experiance</h6>
                                                <br>
                                                <v-col cols="12" sm="8">
                                                    <v-text-field v-model="name" label="Name" persistent-hint
                                                        variant="outlined" color="#FBDF7E" />
                                                    <v-text-field v-model="email" label="Email" persistent-hint
                                                        variant="outlined" color="#FBDF7E"></v-text-field>
                                                    <v-text-field v-model="no_telp" label="No Telepon" persistent-hint
                                                        variant="outlined" color="#FBDF7E"></v-text-field>
                                                    <v-text-field v-model="password" label="Password" persistent-hint
                                                        variant="outlined" color="#FBDF7E"
                                                        type="password"></v-text-field>

                                                    <v-file-input @change="onFileChange" v-model="image"
                                                        label="Upload Image" accept="image/*" persistent-hint
                                                        variant="outlined" show-size color="#FBDF7E"
                                                        multipart></v-file-input>

                                                    <!-- Preview Image Gimana Buatnya Bwang :v  -->


                                                    <v-btn @click="register" color="#FBDF7E" dark block tile
                                                        class="text-yellow mt-8" variant="outlined">Sign up</v-btn>
                                                </v-col>
                                            </v-row>
                                        </v-card-text>
                                    </v-col>
                                </v-row>
                            </v-window>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-parallax>
    </v-app>



    <v-dialog width="300px" :bottom="true" v-model="dialog" :scrim="false" persistent>
        <v-card color="white">
            <v-card-text>
                Waiting For Register..
                <v-progress-linear indeterminate color="yellow" class="mb-0"></v-progress-linear>
            </v-card-text>
        </v-card>
    </v-dialog>

    <v-dialog v-model="dialogRegister" max-width="290">
        <v-card>
            <v-card-title class="headline">Register Success</v-card-title>
            <v-card-text>
                <p>Please Check Your Email For Activation</p>
            </v-card-text>
            <v-card-actions>
                <v-btn color="green darken-1" block text @click="moveToLogin">Close</v-btn>
            </v-card-actions>

        </v-card>
    </v-dialog>



</template>
<script>
import axios from 'axios'
export default {
    data() {
        return {
            name: '',
            email: '',
            password: '',
            no_telp: '',
            image: undefined,
            file: undefined,
            dialog: false,
            dialogRegister: false

        }
    },

    methods: {
        register() {
            const formData = new FormData();

            formData.append('name', this.name);
            formData.append('email', this.email);
            formData.append('password', this.password);
            formData.append('nomor_telepon', this.no_telp);
            formData.append('image', this.file);

            this.dialog = true;



            axios.post('https://davianop.com/UAS_WEB/public/api/register', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(res => {
                console.log(res);
                this.dialog = false;
                this.dialogRegister = true;

            }).catch(err => {
                console.log(err.response.data);
            })
        },

        onFileChange(e) {
            this.file = e.target.files[0];
        },

        moveToLogin() {

            this.$router.push('/login');
        }



    }

}
</script>



<style scoped>
.v-application .rounded-bl-xl {
    border-bottom-left-radius: 300px !important;
}

.v-application .rounded-br-xl {
    border-bottom-right-radius: 300px !important;
}
</style>