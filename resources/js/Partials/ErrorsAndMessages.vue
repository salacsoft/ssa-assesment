<template>
   <div>
   <ul class="alert alert-danger" v-if="errors && Object.keys(errors).length > 0">
        <li v-for="(error, index) in errors" :key="index">{{error}}</li>
    </ul>
    <div class="alert alert-success" v-if="$page.props.flash.success">
        {{$page.props.flash.success}}
    </div>
   </div>
</template>

<script>
export default {
    name: "ErrorsAndMessages",
    props: ["errors"]
}
</script>