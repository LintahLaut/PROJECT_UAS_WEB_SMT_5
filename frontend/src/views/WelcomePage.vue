<template>
    <v-app id="welcome">
        <NavBar />
        <v-container fluid>
            <div class="head">
                <v-row>
                    <v-col cols="5">
                        <div style="position: relative" class="mt-16">
                            <h1 class="text-grey">Hello,</h1>
                            <h1 class="text-white">Welcome To Beauty</h1>
                            <span class="text-grey">SALON HAIR STYLING, MAKE UP & SPA</span><br />

                            <router-link to="/login" custom v-slot="{ navigate }">
                                <v-btn @click="navigate" role="link" tile dark class="text-yellow mt-8"
                                    variant="outlined">
                                    LOG IN HERE
                                </v-btn>
                            </router-link>
                        </div>
                    </v-col>
                    <v-col cols="2">
                        <div style="
                            position: absolute;
                            z-index: 9999;
                            bottom: 0;
                            margin-left: auto;
                            margin-right: auto;
                            left: 0;
                            right: 0;
                            text-align: center;
                            " class="mt-16">
                            <v-icon>fas fa-angle-double-down</v-icon>
                        </div>
                    </v-col>
                    <v-col cols="5">
                        <div style="position: relative; z-index: 9999" class="mt-16">
                            <v-img :src="require('../assets/img/gambar2.png')" contain max-height="300" />
                        </div>
                    </v-col>
                </v-row>
            </div>
            <v-col cols="12" class="mt-16" id="about">
                <div>
                    <v-row>
                        <v-col cols="12" sm="6">
                            <div>
                                <v-img :src="require('../assets/img/gambar1.png')" contain max-height="500" />
                            </div>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <h5 class="mt-16">About Me</h5><br>
                            <p class="text-grey">
                                Salon Beauty adalah tempat yang menyediakan
                                layanan kecantikan dan perawatan kulit.
                                Salon Beauty menawarkan berbagai macam layanan seperti perawatan wajah,
                                haircut, perawatan rambut, dan lain sebagainya.
                            </p>
                            <br />
                            <p class="text-grey">
                                Salon Beauty dilengkapi dengan peralatan dan
                                perlengkapan yang sesuai untuk melakukan layanan kecantikan,
                                seperti kursi dan meja pedikur, alat cukur rambut,
                                dan peralatan lainnya. Selain itu, salon Beauty memiliki
                                ruangan yang terpisah untuk melakukan perawatan wajah dan layanan kecantikan lainnya.
                            </p>
                            <br />
                            <p class="text-grey">
                                Kami menyediakan reservasi bagi anda yang ingin datang tanpa mengantri. Terdapat 4 sesi.
                                Sesi 1 jam 09.00, sesi 2 jam 13.00, sesi 3 jam 16.00, sesi 4 jam 19.00.
                            </p>
                            <br />
                            <p class="text-grey">
                                Kami juga menyediakan berbagai macam produk kecantikan
                                seperti kosmetik, perawatan rambut, dan perawatan kulit yang dapat dibeli
                                oleh anda. Tentunya kami memiliki tim ahli yang terlatih dan
                                berpengalaman dalam memberikan layanan kecantikan yang berkualitas tinggi.
                            </p>
                            <br />
                            <p class="text-grey">
                                Salon beauty merupakan tempat yang cocok bagi anda
                                yang ingin merawat kecantikan dari luar maupun dalam.
                                Menjadi lebih percaya diri dengan penampilan yang lebih indah.
                            </p>
                            <br />

                        </v-col>
                    </v-row>
                </div>
            </v-col>
            <v-col cols="12" class="padd" id="portfolio">
                <div class="first" id="project">
                    <v-row>
                        <v-col cols="12">
                            <div class="child">
                                <v-btn icon="fas fa-laptop" color="#FBDF7E" class="text-white"></v-btn>
                                <h3 class="ml-3 mt-4">Hair Cut</h3>
                                <p class="text-grey ml-3 mt-4 text-caption">
                                    Layanan Hair Cut <br />dengan harga terjangkau
                                    <br />dan kualitas terbaik
                                </p>
                            </div>
                            <div class="child">
                                <v-btn icon="fas fa-mobile-alt" color="#FBDF7E" class="text-white"></v-btn>
                                <h3 class="ml-3 mt-4">Hair Care</h3>
                                <p class="text-grey ml-3 mt-4 text-caption">
                                    Layanan Hair Care <br />dengan pelayanan terbaik
                                    <br />dan hasil yang memuaskan
                                </p>
                            </div>
                            <div class="child">
                                <v-btn icon="fas fa-camera" color="#FBDF7E" class="text-white"></v-btn>
                                <h3 class="ml-3 mt-4">Skin Care</h3>
                                <p class="text-grey ml-3 mt-4 text-caption">
                                    Layanan Skin Care <br />memberikan kepercayaan diri
                                    <br />dengan kulit yang sehat
                                </p>
                            </div>
                        </v-col>
                    </v-row>
                    <v-divider></v-divider>
                </div>
            </v-col>
            <v-col cols="12" class="imgHover">
                <v-row class="fill-height" align="center" justify="center">
                    <template v-for="(item, i) in items" :key="i">
                        <v-col cols="12" md="4">
                            <v-hover v-slot="{ isHovering, props }">
                                <v-card :elevation="isHovering ? 12 : 2" :class="{ 'on-hover': isHovering }"
                                    v-bind="props">
                                    <v-img :src="item.img" height="225px" cover>
                                        <template v-slot:placeholder>
                                            <div class="d-flex align-center justify-center fill-height">
                                                <v-progress-circular indeterminate
                                                    color="grey-lighten-4"></v-progress-circular>
                                            </div>
                                        </template>
                                    </v-img>
                                </v-card>
                            </v-hover>
                        </v-col>
                    </template>
                </v-row>
            </v-col>
        </v-container>
        <FooterView />
    </v-app>
</template>
<script>

import { defineComponent } from "vue";
import NavBar from "../components/NavBar.vue";
import FooterView from "../components/FooterView.vue";

export default defineComponent({
    name: "WelcomePage",
    setup() {
        return {
            slider2: 50,
            items: [
                {
                    img: "https://img.freepik.com/free-photo/beautiful-girl-with-long-hair_144627-8093.jpg?w=2000",
                },
                {
                    img: "https://img.freepik.com/free-photo/beautiful-model-with-long-smooth-flying-blonde-hair-isolated-white-studio-background-young-caucasian-model-with-well-kept-skin-hair-blowing-air_155003-21878.jpg?w=1380&t=st=1671536119~exp=1671536719~hmac=8d92e1ddcebaf0684aa1b2bd10309f6ab7b5c39270f9447dc2895226992fd735",
                },
                {
                    img: "https://img.freepik.com/free-photo/very-sexy-woman-show-her-natural-look_144627-4630.jpg?w=1380&t=st=1671535501~exp=1671536101~hmac=076d522e86299e255392fb540bb5c54ec4bb5d50271e3ddbc3f7382183cf02ec",
                },
                {
                    img: "https://media.istockphoto.com/id/933399032/photo/style-stylish-therapy-treatment-problem-concept-cropped-top-above-close-up-view-photo-of-clean.jpg?s=612x612&w=0&k=20&c=yxYke84fBBMyhO8P_g54R4G7jpjFbiBeSNFHOpWq89Q=",
                },
                {
                    img: "https://img.freepik.com/free-photo/serious-portrait-young-man-isolated-white-studio-background-caucasian-attractive-male-model-concept-fashion-beauty-self-care-body-skin-care-handsome-boy-with-well-kept-skin_155003-34430.jpg?w=1380&t=st=1671536014~exp=1671536614~hmac=873c2f9a6cb3f51ca7441ba1858640182726b86a6b41efdb8db8275e37faa748",
                },
                {
                    img: "https://blog.marthatilaarshop.com/wp-content/uploads/2021/11/tp-two-block-haircut-1-e1637046120531.jpg",
                },
            ],
        };
    },
    components: {
        NavBar,
        FooterView
    },
});
</script>
<style scoped>
.v-container {
    padding: 16px 0 16px 0;
}

.head {
    position: relative;
    text-align: center;
    padding: 12px;
    margin-bottom: 6px;
    height: 400px;
    width: 100%;
    color: white;
}

.head:before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 50%;
    background: black;
    transform: skew(0deg, 6deg);
}

.head:after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    height: 100%;
    width: 50%;
    background: black;
    transform: skew(0deg, -6deg);
}

.egg {
    display: block;
    margin-left: 100px;
    margin-top: 50px;
    width: 356px;
    height: 300px;
    background-color: #fbdf7e;
    border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
}

.first {
    width: 100%;
    height: 280px;
    text-align: center;
    padding: 2rem 2rem;
}

.child {
    display: inline-block;
    padding: 2rem 1rem;
    vertical-align: middle;
    text-align: center;
    margin-right: 8px;
}

.imgHover {
    padding: 0 200px;
}

.pre {
    width: 100%;
    height: 380px;
    text-align: center;
    padding: 0 200px;
    background-color: #f5f5f5;
}

.hire {
    width: 100%;
    height: 200px;
    padding: 0 200px;
    background-color: #e9e9e9;
    margin-top: -24px;
}
</style>