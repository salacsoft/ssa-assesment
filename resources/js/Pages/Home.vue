<template>
      <div>
         <app-header></app-header>
         <div class="jumbotron">
            <h1>Joseph E. Salac</h1>
            <p>Laravel + Vue 3 + InertiaJs</p>
         </div>
      </div>
</template>

<script>
    import AppHeader from "../Partials/AppHeader";

    export default {
        components: {
            AppHeader
        },
        name: "Login",
        setup() {
            return {
               
            }
        }
    }
</script>

<style scoped>
    form {
        margin-top: 20px;
    }
</style>