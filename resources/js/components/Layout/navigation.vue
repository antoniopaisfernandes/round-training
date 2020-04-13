<template>
  <v-navigation-drawer
    v-model="drawer"
    fixed
    app
    color="primary"
  >
    <template v-slot:prepend>
      <div class="tw-mt-16"></div>
    </template>

    <v-list dense>
      <div v-for="(menuOption, index) in mainRoutes" :key="index">
        <v-list-item
          link
          @click.stop="goto(menuOption.link)"
        >
          <v-list-item-action>
            <v-icon>{{ menuOption.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ menuOption.text }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </div>
    </v-list>

    <template v-slot:append>
      <v-list dense>
        <div v-for="(menuOption, index) in footerRoutes" :key="index">
          <v-list-item
            link
            @click.stop="goto(menuOption.link)"
          >
            <v-list-item-action>
              <v-icon>{{ menuOption.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ menuOption.text }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </div>
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
      // TODO: replace with store when store is implemented
      return this.$root.$refs['eApp'].$options.propsData.auth
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
