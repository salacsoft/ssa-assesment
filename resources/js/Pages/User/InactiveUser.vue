<template>
      <div>
         <app-header></app-header>
         
         <div class="row mt-2 container">
             <errors-and-messages :errors="errors"></errors-and-messages>
         </div>
      
         <div class="mt-3">
            <div class="row container justify-content-center">
               <h5>InActive Users</h5>
            </div>
            <table class="table table-striped">
               <thead class="thead-dark">
                  <tr>
                     <th>Photo</th>
                     <th>Name</th>
                     <th>Username</th>
                     <th>Email</th>
                     <th>Date Deleted</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <tr v-for="user in users.data" :key="user.username">
                     <td><img v-if="user.avatar" width="60" height="60" :src="user.avatar" class="rounded-circle img-thumbnail" alt="photo"></td>
                     <td>{{user.fullname}}</td>
                     <td>{{user.username}}</td>
                     <td>{{user.email}}</td>
                     <td>{{user.deleted_at}}</td>
                     <td>
                        <button @click="restoreUser(user)"  title="Click to restore the user " class="btn btn-sm btn-warning mr-1"><i class="fas fa-undo"></i></button>
                        <button @click="deleteUser(user)"  title="Click to delete permanently the user" class="btn btn-sm btn-danger"><i class="fas fa-user-slash"></i></button>
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

            function deleteUser(user)
            {
               swal({
                  title: 'Are you sure?',
                  text: `${user.fullname } will remove permanently - this is irreversible would like to continue?`,
                  icon: 'warning',
                  buttons: ["Cancel", "Yes!"],
               }).then(function(value) {
                  if (value) {
                         Inertia.delete(route('hardDelete',{id: user.id}));
                  }
               });
            }

            function restoreUser(user)
            {
               swal({
                  title: 'Are you sure?',
                  text: `${user.fullname } will be back to the active list of users`,
                  icon: 'warning',
                  buttons: ["Cancel", "Yes!"],
               }).then(function(value) {
                  if (value) {
                        Inertia.patch(route('restoreUser',{id: user.id}));
                  }
               });
            }

            return {
                params, users, numberLinks, deleteUser, restoreUser
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