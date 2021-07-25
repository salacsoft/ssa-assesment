<template>
   <div>
      <app-header></app-header>
      <div class="row">
         <div class="col-12 col-sm-8 offset-sm-1 col-md-6 offset-md-3">
               <form method="post" @submit.prevent="submit" enctype="multipart/form-data">
                  <h5 class="text-center">User Registration</h5>
                  <errors-and-messages :errors="errors"></errors-and-messages>
                  <div class="form-group">
                      <div class="row">
                        <div class="col-10 col-sm-4 col-md-4 col-lg-4">
                            <label for="name">Prefix name</label>
                           <select name="prefixname" class="form-control" v-model="form.prefixname">
                              <option value="Mr">Mr</option>
                              <option value="Mrs">Mrs</option>
                              <option value="Ms">Ms</option>
                           </select>
                        </div>
                      </div>
                  </div>

                  <div class="row">
                     <div class="form-group col-sm-8 col-md-8 col-lg-8">
                           <label for="name">First name</label>
                           <input type="text" class="form-control" name="name" id="name" v-model="form.firstname" required />
                     </div>
                     <div class="form-group col-sm-4 col-md-4 col-lg-4">
                        <label for="name">Suffix name</label>
                        <input type="text" class="form-control" name="name" id="name" v-model="form.suffixname" />
                     </div>
                  </div>
            
                  <div class="form-group">
                     <label for="name">Middle name</label>
                     <input type="text" class="form-control" name="name" id="name" v-model="form.middlename" />
                  </div>

                  <div class="form-group">
                     <label for="name">Last name</label>
                     <input type="text" class="form-control" name="name" id="name" v-model="form.lastname" required />
                  </div>

                  <div class="form-group">
                     <label for="name">Username</label>
                     <input type="text" class="form-control" name="name" id="name" v-model="form.username" />
                  </div>

                  <div class="form-group">
                     <label for="email">Email</label>
                     <input type="text" class="form-control" name="email" id="email" v-model="form.email" required />
                  </div>

                  <div class="form-group">
                     <label for="password">Password</label>
                     <input type="password" class="form-control" name="password" id="password" v-model="form.password" required />
                  </div>

                  <div class="form-group">
                     <label for="password">Re-type Password</label>
                     <input type="password" class="form-control" name="password" id="password" v-model="form.confirm_password" required />
                  </div>
                  <div class="row">
                     <div class="form-group col-12 col-sm-7 col-md-7 col-lg-7">
                        <label for="image">Photo</label>
                        <input type="file" id="image" name="image" class="form-control" @change="selectFile">
                     </div>
                     <div class="col-12 col-sm-5 col-md-5 col-lg-5">
                        <label for="name">User type</label>
                        <select name="prefixname" class="form-control" v-model="form.type">
                           <option value="User">User</option>
                           <option value="Admin">Admin</option>
                        </select>
                     </div>
                  </div>
                 

                <input type="submit" class="btn btn-primary btn-block" value="Register" />
               </form>
         </div>
      </div>
   </div>
</template>


<script>
    import AppHeader from "../../Partials/AppHeader";
    import ErrorsAndMessages from "../../Partials/ErrorsAndMessages";
   import {inject, reactive} from "vue";
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
         const form = reactive({
               prefixname: null,
               firstname: null,
               middlename: null,
               lastname: null,
               suffixname: null,
               username: null,
               email: null,
               password: null,
               confirm_password: null,
               photo:null,
               password: null,
               type: null,
               _token: usePage().props.value.csrf_token
         });
         const photoPreview = null;

         const route = inject('$route');
         
         function submit() {
               Inertia.post(route('register'), form);
         }

         function selectFile($event) {
            form.photo = $event.target.files[0];
         }
         
         function clearPhotoFileInput() {
            if (this.$refs.photo?.value) {
               this.$refs.photo.value = null;
            }
         }

         return {
               form, photoPreview, clearPhotoFileInput, selectFile, submit
         }
      }
   }
</script>
<style scoped>
    form {
        margin-top: 20px;
    }
</style>