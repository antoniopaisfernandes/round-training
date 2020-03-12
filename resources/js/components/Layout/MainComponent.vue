<template>
  <v-app id="inspire">

    <v-navigation-drawer
      v-model="drawer"
      app
      color="#68c6be"
    >
      <v-list dense>
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-home</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Home</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-mail</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Contact</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
        app
        flat=""
        height="78"
    >
        <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
        <v-toolbar-title class="tw-w-full">
            <div class="tw-flex tw-items-center tw-justify-between">
                <div><img src="/images/logo.png" alt="Logo"></div>
                <div class="text-xl">Gestão da Formação</div>
            </div>
        </v-toolbar-title>
    </v-app-bar>

    <v-content>
      <v-container
        class="fill-height"
        fluid
      >
        <v-row
          align="center"
          justify="center"
        >
          <v-col class="text-center">
              <slot></slot>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
  export default {
    props: {
      source: String,
      auth: {
        type: Object,
        default: {}
      }
    },
    data: () => ({
      drawer: null,
    }),
  }
</script>
