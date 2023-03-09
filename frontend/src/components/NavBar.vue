<template>
    <v-app-bar app color="black" dark flat class="px-12">
        <v-spacer></v-spacer>
        <v-btn color="black" text @click="scroll('home')" class="text-yellow">Home</v-btn>
        <v-btn color="black" text @click="scroll('about')">About</v-btn>
        <v-btn color="black" text @click="scroll('portfolio')">Gallery</v-btn>
    </v-app-bar>
</template>
<script>
export default {
    methods: {
        scroll(refName) {
            const element = document.getElementById(refName);
            element.scrollIntoView({ behavior: "smooth" });
        },
    },
};
</script>
<style>

</style>