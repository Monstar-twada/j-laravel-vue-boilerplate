<template>
  <layout-header>
    <template slot="start">
      <div class="navbar-item">
        <ui-button @click="$router.push({name:'admin:home'})">HOME</ui-button>
      </div>
      <div class="navbar-item">
        <ui-button @click="$router.push({name:'admin:list'})">LIST</ui-button>
      </div>
    </template>
  </layout-header>
</template>

<script>
import Header from "@views/_partials/Header";
export default {
  components: {
    "layout-header": Header
  }
};
</script>

<style>
</style>
