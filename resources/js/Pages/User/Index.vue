<template>
      <div>
         <app-header></app-header>
         
         <div class="row mt-5 justify-content-between">
           <!-- left side -->
            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
              <div class="row justify-content-start">
                 <div class=" col-12 col-sm-4 col-md-4 col-lg-4">
                    <inertia-link :href="$route('showRegisterForm')" class="btn btn-outline-primary form-control">
                       Create New User
                     </inertia-link>
                 </div>
               </div>
           </div>
            <!-- right side -->
           <div>

           </div>
         </div>

         <div class="row mt-2">
             <errors-and-messages :errors="errors"></errors-and-messages>
         </div>
         <div class="mt-2">
            <table class="table table-striped">
               <thead class="thead-light">
                  <tr>
                     <th>Photo</th>
                     <th>Name</th>
                     <th>Username</th>
                     <th>Email</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <tr v-for="user in users.data" :key="user.username">
                     <td><img v-if="user.avatar" width="60" height="60" :src="user.avatar" class="rounded-circle img-thumbnail" alt="photo"></td>
                     <td>{{user.fullname}}</td>
                     <td>{{user.username}}</td>
                     <td>{{user.email}}</td>
                     <td>
                        <inertia-link :href="`/users/${user.id}`" class="btn btn-sm btn-success mr-1">View</inertia-link>
                        <inertia-link :href="`/users/${user.id}/edit`" class="btn btn-sm btn-warning mr-1">Edit</inertia-link>
                        <inertia-link :href="`/users/${user.id}`" class="btn btn-sm btn-danger">Remove</inertia-link>
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
         <div>
             <!-- Pagination links-->
            <nav aria-label="Page navigation" v-if="users.total > users.per_page" style="margin-top: 20px">
               <ul class="pagination">
                  <!-- Previous link -->
                  <li :class="'page-item' + (users.links[0].url == null ? ' disabled' : '')">
                     <inertia-link :href="users.links[0].url == null ? '#' : users.links[0].url" class="page-link" v-html="users.links[0].label"></inertia-link>
                  </li>
                  <!-- Numbers -->
                  <li v-for="(item, index) in numberLinks" :key="index" :class="'page-item' + (item.active ? ' disabled' : '')">
                     <inertia-link :href="item.active ? '#' : item.url" class="page-link" v-html="item.label"></inertia-link>
                  </li>
                  <!-- Next link -->
                  <li :class="'page-item' + (users.links[users.links.length - 1].url == null ? ' disabled' : '')">
                     <inertia-link :href="users.links[users.links.length - 1].url == null ? '#' : users.links[users.links.length - 1].url" class="page-link" v-html="users.links[users.links.length - 1].label"></inertia-link>
                  </li>
               </ul>
            </nav>
               
         </div>
      </div>
</template>

<script>
    import AppHeader from "../../Partials/AppHeader";
    import ErrorsAndMessages from "../../Partials/ErrorsAndMessages";

    import {Inertia} from "@inertiajs/inertia";
    import { usePage } from '@inertiajs/inertia-vue3'
    import {reactive,inject, computed} from 'vue';

    export default {
        components: {
            ErrorsAndMessages,
            AppHeader
        },
        name: "Index",
        props: {
            errors: Object
        },
        setup() {
            const params = reactive({
               search: null,
               password: null,
               _token: usePage().props.value.csrf_token
            });


            const route = inject('$route');
            const users = computed(() => usePage().props.value.users);
            const numberLinks = users.value.links.filter((v, i) => i > 0 && i < users.value.links.length - 1);
            console.log("users", users.value);
      

            return {
                params, users, numberLinks
            }
        }
    }
</script>

<style scoped>
    form {
        margin-top: 20px;
    }
    td {
      height: 20px;
      vertical-align: middle;
   }
</style>