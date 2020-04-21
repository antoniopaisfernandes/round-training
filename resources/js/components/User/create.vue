<template>
  <v-dialog scrollable v-model="dataVisible" @keydown.esc="dataVisible = false" max-width="48rem">
    <v-card :loading="isSaving" class="px-5 py-5 vert-card" height="90vh">
      <v-tabs
        fixed-tabs
        v-model="tab"
      >
        <v-tab key="user">Utilizador</v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab">
        <v-tab-item key="user">
          <v-card>
            <v-card-text dense>
              <v-text-field
                autofocus
                v-model="dataUser.name"
                label="Nome"
                id="name"
                prepend-icon="mdi-account-edit-outline"
                required
                :rules="rules.name"
              ></v-text-field>
              <v-text-field
                v-model="dataUser.email"
                label="E-mail"
                id="email"
                type="email"
                prepend-icon="mdi-email-outline"
                required
                :rules="rules.email"
              ></v-text-field>
              <v-text-field
                v-model="dataUser.password"
                label="Password"
                id="password"
                type="password"
                prepend-icon="mdi-lastpass"
                required
                :rules="rules.password"
              ></v-text-field>
              <v-text-field
                v-model="dataUser.password_confirmation"
                label="Repetir password"
                id="password_confirmation"
                type="password"
                prepend-icon="mdi-lastpass"
                required
                :rules="rules.password_confirmation"
              ></v-text-field>
              <v-select
                v-model="dataUser.roles"
                :items="roles"
                item-text="name"
                item-value="id"
                attach
                chips
                :deletable-chips="true"
                label="Perfis"
                id="roles"
                multiple
                prepend-icon="mdi-dolly"
              ></v-select>
            </v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs-items>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
        <v-btn color="blue darken-1" text :disabled="isSaveDisabled" @click="save">Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import User from '../../models/User'
import Role from '../../models/Role'
import Model from '../../models/Model'
import alert from '../../plugins/toast'
import map from 'lodash-es/map'

export default {
  name: 'user-create',

  model: {
    prop: 'user',
    event: 'input'
  },

  props: {
    user: {
      type: Model,
      default: function() {
        return new User()
      }
    },
    visible: {
      type: Boolean
    },
  },

  data: () => ({
    dataUser: new User(),
    isSaving: false,
    roles: [],
    tab: null,
  }),

  computed: {
    dataVisible: {
      get () {
        return this.visible
      },
      set (value) {
        if (! value) {
          this.$emit('close')
          this.dataUser = new User()
        }
      }
    },
    isSaveDisabled() {
      return ! this.dataUser.name
        || ! this.dataUser.email
        || ! this.dataUser.roles.length
        || this.isSaving
    },
    rules() {
      return {
        name: [
          v => !!v || 'É obrigatória a indicação de um valor para o campo.'
        ],
        email: [
          v => !!v || 'É obrigatória a indicação de um valor para o campo.'
        ]
      }
    },
  },

  methods: {
    close() {
      this.dataVisible = false
    },
    async save() {
      this.isSaving = true

      try {
        let user = await this.dataUser.save()
        this.isSaving = false
        this.close()
        this.$emit('input', user)
        this.$emit('saved', user)
      } catch (error) {
        this.isSaving = false
        alert.error(error)
      }
    },
  },

  watch: {
    user: function(value) {
      this.dataUser = value
    },
    visible: function(value) {
      this.dataVisible = value
    },
  },

  mounted: async function () {
    try {
      this.roles = await Role.get()
    } catch (error) {
      alert.error(error)
    }
  },

}
</script>
