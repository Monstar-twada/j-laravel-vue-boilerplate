<template>
  <ui-navbar
     class="layout-header navbar"
    role="navigation"
    aria-label="main navigation"
    v-bind="{brand,hasBurger:false,}"
 > Header</ui-navbar>
</template>

<script>
export default {
    name:'layout-header',
    data(){
        return {
            brand:{
                href:'test',
                src:'test',

            }
        }
    }
}
</script>

<style>

</style>
