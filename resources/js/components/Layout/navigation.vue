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
      <div v-for="(menuOption, index) in menuOptions" :key="index">
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
        <v-list-item
          link
          @click.stop="goto('/logout')"
        >
          <v-list-item-action>
            <v-icon>mdi-account-circle-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Logout</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </template>
  </v-navigation-drawer>
</template>

<script>
import menuOptions from '../../routes'

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
    menuOptions
  }),
  methods: {
    goto(url) {
      if (! url) {
        return;
      }

      document.location = url;
    }
  }
}
</script>
