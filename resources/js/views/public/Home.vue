<template>
  <div class="home">
      <h1> HELLO {{fullName}}</h1>

  </div>
</template>

<script>
export default {
    name:'home-page',
    computed:{
        user(){
            return this.$store.getters['Auth/profile'];
        },
        fullName(){
            return this.user.first_name +" " + this.user.last_name;
        },

    },

}
</script>

<style>

</style>
