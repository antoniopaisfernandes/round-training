<template>
  <v-app id="training">
    <v-navigation-drawer
      v-if="auth"
      v-model="drawer"
      fixed
      app
      color="primary"
    >
      <v-list dense>
        <div v-for="(menuOption, index) in menuOptions" :key="index" :class="index ? '' : 'tw-mt-16'">
          <v-list-item link>
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
            @click="logout"
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

    <v-app-bar
        app
        flat
        height="78"
        color="white"
    >
        <v-app-bar-nav-icon v-if="auth" @click.stop="drawer = !drawer" />
        <v-toolbar-title class="tw-w-full">
            <div class="tw-flex tw-items-center tw-justify-between">
                <div><a href="/"><img src="/images/logo.svg" alt="Logo" /></a></div>
                <div class="text-xl tw-mr-6 tw-font-medium tw-uppercase">Gestão de Formação</div>
            </div>
        </v-toolbar-title>
    </v-app-bar>

    <v-content class="grey lighten-5">
      <slot></slot>
    </v-content>
  </v-app>
</template>

<script>
  export default {
    props: {
      auth: {
        type: Object,
        default: {}
      }
    },
    data: () => ({
      drawer: null,
      menuOptions: [ // TODO: extract to routes/files
        {
          type: 'v-list-item',
          icon: 'mdi-monitor-dashboard',
          text: 'Dashboard',
          link: '',
        },
        {
          // TODO: extract to components
          type: 'v-divider',
        },
        {
          type: 'v-list-item',
          icon: 'mdi-domain',
          text: 'Empresas',
          link: '',
        },
        {
          type: 'v-list-item',
          icon: 'mdi-teach',
          text: 'Professores',
          link: '',
        },
        {
          type: 'v-list-item',
          icon: 'mdi-account-edit-outline',
          text: 'Alunos',
          link: '',
        },
        {
          type: 'v-list-item',
          icon: 'mdi-brain',
          text: 'Cursos',
          link: '',
        },
        {
          type: 'v-list-item',
          icon: 'mdi-book-account-outline',
          text: 'Inscrições',
          link: '',
        },
      ]
    }),
    methods: {
      logout() {
        document.location = '/logout';
      }
    }
  }
</script>
