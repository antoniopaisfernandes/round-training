<template>
  <v-navigation-drawer
    v-model="drawer"
    app
    color="primary"
  >
    <template v-slot:prepend>
      <div class="tw-mt-16"></div>
    </template>

    <v-list density="compact">
      <v-list-item
        v-for="(menuOption, index) in mainRoutes"
        :key="index"
        link
        @click.stop="goto(menuOption.link)"
      >
        <template v-slot:prepend>
          <v-icon>{{ menuOption.icon }}</v-icon>
        </template>
        <v-list-item-title>{{ menuOption.text }}</v-list-item-title>
      </v-list-item>
    </v-list>

    <template v-slot:append>
      <v-list density="compact">
        <v-list-item
          v-for="(menuOption, index) in footerRoutes"
          :key="index"
          link
          @click.stop="goto(menuOption.link)"
        >
          <template v-slot:prepend>
            <v-icon>{{ menuOption.icon }}</v-icon>
          </template>
          <v-list-item-title>{{ menuOption.text }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </template>
  </v-navigation-drawer>
</template>

<script>
import mRoutes from '../../routes'
import { footerRoutes as fRoutes } from '../../routes'

export default {

  props: {
    isActive: {
      default: null
    }
  },

  watch: {
    isActive: function (value) {
      this.drawer = value;
    },
  },

  data: () => ({
    drawer: null,
    mainRoutes: [],
    footerRoutes: [],
  }),

  mounted: function () {
    this.mainRoutes = mRoutes.filter((route) => {
      return !route.admin || this.admin
    })
    this.footerRoutes = fRoutes.filter((route) => {
      return !route.admin || this.admin
    })
  },

  computed: {
    auth: function() {
      // Access auth from parent app-layout component
      return this.$parent?.auth
    },

    admin: function () {
      return this.auth?.roles?.filter((role) => role.name == 'admin').length > 0
    },
  },

  methods: {

    goto(url) {
      if (! url) {
        return;
      }
      document.location = url;
    },

  },

}
</script>
