<template>
  <v-navigation-drawer
    v-model="drawer"
    color="primary"
  >
    <template v-slot:prepend>
      <div class="tw-mt-16"></div>
    </template>

    <v-list density="compact" nav>
      <v-list-item
        v-for="(menuOption, index) in mainRoutes"
        :key="index"
        :prepend-icon="menuOption.icon"
        :title="menuOption.text"
        @click.stop="goto(menuOption.link)"
      ></v-list-item>
    </v-list>

    <template v-slot:append>
      <v-list density="compact" nav>
        <v-list-item
          v-for="(menuOption, index) in footerRoutes"
          :key="index"
          :prepend-icon="menuOption.icon"
          :title="menuOption.text"
          @click.stop="goto(menuOption.link)"
        ></v-list-item>
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
    },
    auth: {
      type: Object,
      default: () => ({})
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
