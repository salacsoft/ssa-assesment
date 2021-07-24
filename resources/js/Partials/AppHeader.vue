<template>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <h3 class="text-warning">SSA Tech Exam</h3>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                       <inertia-link :href="$route('home')" class="nav-link">User Listing</inertia-link>
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
                            Logged in as {{user.fullname}}
                        </span>
                        <inertia-link :href="$route('logout')" as="button" method="post" class="nav-link logout-link" style="display: inline" type="button">Logout</inertia-link>
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