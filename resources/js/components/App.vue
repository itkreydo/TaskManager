<template>
    <!-- App.vue -->

    <v-app class="main-app">
        <v-content>
            <router-view></router-view>
        </v-content>
    </v-app>
</template>

<script>

export default {
  name: 'App',
    methods: {
      logout() {
      }
    }
}
</script>

<style>
.main-container {
    width:100%;
    max-width: 100%;
    height: 100%;
}
</style>
