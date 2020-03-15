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
    >
      <v-app-bar-nav-icon v-if="auth" @click.stop="toggle"></v-app-bar-nav-icon>
      <v-toolbar-title class="tw-w-full">
        <div class="tw-flex tw-items-center tw-justify-between">
          <div><a href="/"><img src="/images/logo.png" alt="Logo" /></a></div>
          <div class="text-xl tw-ml-6 tw-font-bold tw-font-sans tw-uppercase tw-tracking-widest">Gestão de Formação</div>
        </div>
      </v-toolbar-title>
    </v-app-bar>

    <v-content>
      <slot></slot>
    </v-content>
  </v-app>
</template>

<script>
  import NavigationDrawer from './navigation'

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
