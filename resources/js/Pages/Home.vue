<template>
      <div>
         <app-header></app-header>
         <div class="row">
           <div class="container">
              <div>
                 <h2>SSA Tech Exam</h2>
              </div>
           </div>
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