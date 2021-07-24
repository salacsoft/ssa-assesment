<template>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            
            <inertia-link :href="$route('showLoginForm')" class="nav-link">
               <h3 class="text-warning">SSA Tech Exam</h3>
            </inertia-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item active">
                       <inertia-link :href="$route('showUsers')" class="nav-link">
                           <button class="btn btn-primary text-white">Users</button>
                        </inertia-link>
                    </li>
                </ul>
            </div>

            <div class="navbar-collapse collapse order-3 dual-collapse2">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item" v-if="!user">
                        <inertia-link :href="$route('showLoginForm')" class="nav-link">Login</inertia-link>
                    </li>
                    <li class="nav-item" v-if="!user">
                        <inertia-link :href="$route('showRegisterForm')" class="nav-link">Register</inertia-link>
                    </li>
                    <li class="nav-item" v-if="user">
                        <span class="navbar-text" v-if="user">
                           <img :src="user.avatar" alt="profile" class="rounded-circle" width="30" height="30">
                           {{user.fullname}}
                        </span>
                        <inertia-link :href="$route('logout')" as="button" method="post" class="nav-link logout-link" title="click to logout"><i class="fas fa-sign-out-alt"></i></inertia-link>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</template>

<script>
import {computed} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";

export default {
    name: "AppHeader",
    setup() {
        const user = computed(() => usePage().props.value.auth.user);

        return {
            user
        }
    }
}
</script>