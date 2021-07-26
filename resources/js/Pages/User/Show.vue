<template>
   <div>
      <app-header></app-header>
      <div class="row mt-5">
         <div class="col-12 col-sm-8 offset-sm-1 col-md-6 offset-md-3">
            <h2 class="text-center">User Information</h2>
            <div class="card bg-light">
               <div class="row justify-content-center pt-5">
                  <img v-if="user.avatar" width="120" height="120" :src="user.avatar" class="rounded-circle" alt="photo">
               </div>
               <div class="card-body">
                  <div class="form-group">
                     <label for="name">Name</label>
                     <input type="text" class="form-control" v-model="user.fullname" readonly>
                  </div>
               </div>
            </div>
            <div class="card bg-light mt-3">
               <div class="card-body">
                 <div class="form-group">
                     <label for="name">Email</label>
                     <input type="text" class="form-control" v-model="user.email" readonly>
                  </div>
                  <div class="form-group">
                     <label for="name">Username</label>
                     <input type="text" class="form-control" v-model="user.username" readonly>
                  </div>
                  <div class="form-group">
                     <label for="name">User type</label>
                     <input type="text" class="form-control" v-model="user.type" readonly>
                  </div>
               </div>
            </div>

            <div class="row justify-content-between mt-3">
               <div class="col-6 col-sm-3 col-md-3 col-lg-3">
                  <button class="btn btn-secondary form-control" @click="back">Back</button>
               </div>
               <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                  <inertia-link :href="$route('users.edit', user.id)" class="btn btn-warning form-control">Edit info.</inertia-link>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>


<script>
    import AppHeader from "../../Partials/AppHeader";
    import ErrorsAndMessages from "../../Partials/ErrorsAndMessages";
   import {inject, reactive, computed} from "vue";
   import {usePage} from "@inertiajs/inertia-vue3";
   import {Inertia} from "@inertiajs/inertia";
   export default {
      name: "Register",
      components: {
         ErrorsAndMessages,
         AppHeader
      },
      props: {
         errors: Object
      },
      setup() {
         const photoPreview = null;

         const route = inject('$route');
         const user  = usePage().props.value.user;

         function edit() {
               Inertia.get(route('users.edit'), form);
         }

         function back(){
            window.history.back();
         }

         return {
               edit, user, back
         }
      }
   }
</script>
<style scoped>
    form {
        margin-top: 20px;
    }
</style>