<template>
  <ui-navbar
    class="layout-header navbar"
    role="navigation"
    aria-label="main navigation"
    :hasBurger="isAuthenticated"
    v-bind="{brand}"
  >
  <slot name="start" slot="start"></slot>
  <slot name="end" slot="end"></slot>
    <template slot="end">
        <div class="navbar-item" v-if="isAuthenticated">
            <ui-button @click="logout">Logout</ui-button>
        </div>
    </template>
  </ui-navbar>
</template>

<script>
export default {
  name: "layout-header",
  data() {
    return {
      brand: {
        href: 'home',
        src: "test"
      }
    };
  },
  computed:{
      isAuthenticated(){
          return this.$store.getters['Auth/isAuthenticated'];

      },
  },
  methods:{
      logout(){
          this.$store.dispatch('Auth/logout','sign-out');
      }
  }
};
</script>

<style>
</style>
