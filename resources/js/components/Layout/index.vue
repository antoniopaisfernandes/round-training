<template>
  <v-app id="training">
    <navigation-drawer
      v-if="auth"
      :isActive="navigationDrawerIsActive"
      ref="navigationDrawer"
    ></navigation-drawer>

    <v-app-bar
      app
      flat
      height="78"
      color="white"
    >
      <v-app-bar-nav-icon v-if="auth" @click.stop="toggle" />
      <v-toolbar-title class="tw-w-full">
        <div class="tw-flex tw-items-center tw-justify-between">
          <div><a href="/"><img src="/images/logo.svg" alt="Logo" /></a></div>
          <div class="text-xl tw-mr-6 tw-font-medium tw-uppercase">Training Management</div>
        </div>
      </v-toolbar-title>
    </v-app-bar>

    <v-main class="grey lighten-5">
      <slot></slot>
    </v-main>
  </v-app>
</template>

<script>
  import NavigationDrawer from './navigation.vue'

  export default {
    components: { NavigationDrawer },
    data: () => ({
      navigationDrawerIsActive: null,
    }),
    props: {
      auth: {
        type: Object,
        default: {}
      }
    },
    methods: {
      toggle() {
        this.navigationDrawerIsActive = ! this.navigationDrawerIsActive
      }
    }
  }
</script>
