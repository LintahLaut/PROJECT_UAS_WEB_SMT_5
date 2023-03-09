<template>
    <v-app id="login">
        <v-parallax dark
            src="https://images.pexels.com/photos/853427/pexels-photo-853427.jpeg?cs=srgb&dl=pexels-delbeautybox-853427.jpg&fm=jpg"
            height="800">
            <v-container>
                <v-row align="center" justify="center">
                    <v-col cols="12" sm="12">
                        <v-card class="elevation-6 mt-16" color="black">
                            <v-window>
                                <v-row>
                                    <v-col cols="12" md="6">
                                        <v-card-text class="mt-12">
                                            <h4 class="text-center">Login in to Your Account</h4>
                                            <h6 class="text-center  grey--text ">
                                                Log in to your account to access
                                                your salon<br>and editing your onboarding flows
                                            </h6>
                                            <v-row align="center" justify="center">
                                                <v-col cols="12" sm="8">
                                                    <v-text-field v-model="user.email" label="Email" persistent-hint
                                                        variant="outlined" color="#FBDF7E" autocomplete="false"
                                                        class="mt-16"></v-text-field>
                                                    <v-text-field v-model="user.password" label="Password"
                                                        persistent-hint variant="outlined" color="#FBDF7E"
                                                        autocomplete="false" type="password"></v-text-field>
                                                    <v-row>
                                                        <v-col cols="12" sm="6">
                                                            <router-link to="/" custom v-slot="{ navigate }">
                                                                <v-btn @click="navigate" role="link" block tile dark
                                                                    class="text-yellow" variant="outlined">
                                                                    BACK
                                                                </v-btn>
                                                            </router-link>
                                                        </v-col>
                                                        <v-col cols="12" sm="6">
                                                            <v-btn @click="login" color="#FBDF7E" dark block tile
                                                                class="text-yellow" variant="outlined">
                                                                Log in
                                                            </v-btn>
                                                        </v-col>
                                                    </v-row>
                                                </v-col>
                                            </v-row>
                                        </v-card-text>
                                    </v-col>
                                    <v-col cols="12" md="6" class="orange rounded-bl-xl">
                                        <div style="  text-align: center; padding: 180px 0;">
                                            <v-card-text class="white--text">
                                                <h1 class="text-center ">Don't Have an Account Yet?</h1>
                                                <br>
                                                <h5 class="text-center">
                                                    Let's get you all set up so you can start
                                                    creating your your first<br> onboarding experience
                                                </h5>
                                            </v-card-text>
                                            <div class="text-center">
                                                <router-link to="/register" custom v-slot="{ navigate }">
                                                    <v-btn @click="navigate" role="link" tile dark
                                                        class="text-yellow mt-8" variant="outlined">
                                                        SIGN UP
                                                    </v-btn>
                                                </router-link>
                                            </div>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-window>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-parallax>
    </v-app>

    <v-dialog v-model="dialog.dialogLoginFailed" max-width="290">
        <v-card>
            <v-card-title class="headline">Login Failed</v-card-title>
            <v-card-text>
                <p>Wrong Email / Password</p>
            </v-card-text>
            <v-card-actions>
                <v-btn color="green darken-1" block text @click="dialog.dialogLoginFailed = false">Close</v-btn>
            </v-card-actions>

        </v-card>
    </v-dialog>

    <v-dialog v-model="dialog.dialogLoginSuccess" max-width="290">
        <v-card>
            <v-card-title class="headline">Login Success</v-card-title>
            <v-card-actions>
                <v-btn color="green darken-1" block text @click="moveToDashboard">Close</v-btn>
            </v-card-actions>

        </v-card>
    </v-dialog>

    <v-dialog v-model="dialog.dialogLoginVerify" max-width="290">
        <v-card>
            <v-card-title class="headline">Email Not Verified</v-card-title>
            <v-card-text>
                <p>Please Check Your Email For Activation</p>
            </v-card-text>
            <v-card-actions>
                <v-btn color="green darken-1" block text @click="dialog.dialogLoginVerify = false">Close</v-btn>
            </v-card-actions>

        </v-card>
    </v-dialog>

    <v-dialog width="300px" :bottom="true" v-model="dialog.dialogLoading" :scrim="false" persistent>
        <v-card color="white">
            <v-card-text>
                Waiting For Login..
                <v-progress-linear indeterminate color="yellow" class="mb-0"></v-progress-linear>
            </v-card-text>
        </v-card>
    </v-dialog>


</template>
<script>
import { reactive } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
export default {
    setup() {



        const router = useRouter()
        const user = reactive({
            email: '',
            password: '',
        })
        const formData = new FormData();

        const dialog = reactive({
            dialogLoginFailed: false,
            dialogLoginSuccess: false,
            dialogLoginVerify: false,
            dialogLoading: false,
        })

        function login() {
            formData.append('email', user.email);
            formData.append('password', user.password);
            dialog.dialogLoading = true

            axios.post('https://davianop.com/UAS_WEB/public/api/login', formData)
                .then(res => {
                    console.log(res.data.user)
                    // set id local storage
                    localStorage.setItem('id', res.data.user.id)
                    // set token local storage
                    localStorage.setItem('token', res.data.access_token)

                    dialog.dialogLoginSuccess = true
                    dialog.dialogLoading = false

                })
                .catch(err => {

                    if (err.response.status == 401) {
                        if (err.response.data.message == "Email Not Verified") {
                            dialog.dialogLoginVerify = true
                            dialog.dialogLoading = false

                        } else {
                            dialog.dialogLoginFailed = true
                            dialog.dialogLoading = false

                        }
                    }

                    console.log(err.response.data)

                })
        }

        function moveToDashboard() {
            router.push('/reservasi')
        }


        return {
            user,
            login,
            dialog,
            moveToDashboard
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