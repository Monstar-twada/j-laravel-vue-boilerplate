<template>
  <div id="app" class="app-container">
    <router-view class="app-main">
      <layout-header slot="header"></layout-header>
      <layout-footer slot="footer"></layout-footer>
    </router-view>
    <notification-container></notification-container>
  </div>
</template>

<script>
import NotificationContainer from "@views/_partials/NotificationContainer";
import Header from "@views/_partials/Header";
import Footer from "@views/_partials/Footer";

export default {
  name: "app-container",
  computed: {
    user() {
      return this.$store.getters["Auth/profile"];
    }
  },
  methods: {
    set_prerequisites() {
      var isAuthenticated = this.$store.getters["Auth/isAuthenticated"];
      if (!isAuthenticated) return;
    },
    onLogin(next) {
      this.$router.push(next);
    },
    onLogout(next) {
      this.$router.push(next);
    }
  },
  watch: {
    $route: {
      immediate: true,
      handler(to, from) {
        if (to.name) {
        }
      }
    }
  },
  mounted() {
    this.$bus.on("login", this.set_prerequisites);
    this.$bus.on("login", this.onLogin);
    this.$bus.on("logout", this.onLogout);
    this.set_prerequisites();
  },
  components: {
    "notification-container": NotificationContainer,
    "layout-header": Header,
    "layout-footer": Footer
  }
};
</script>
