<template>
  <v-dialog scrollable v-model="dataVisible" max-width="48rem">
    <v-card class="px-5 py-5 vert-card" height="90vh">
      <v-progress-linear v-if="isSaving" indeterminate color="primary"></v-progress-linear>
      <v-tabs v-model="tab">
        <v-tab value="user">User</v-tab>
      </v-tabs>

      <v-tabs-window v-model="tab">
        <v-tabs-window-item value="user">
          <v-card variant="flat">
            <v-card-text>
              <v-text-field
                autofocus
                v-model="dataUser.name"
                label="Name"
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
                label="Password confirmation"
                id="password_confirmation"
                type="password"
                prepend-icon="mdi-lastpass"
                required
                :rules="rules.password_confirmation"
              ></v-text-field>
              <v-select
                v-model="dataUser.roles"
                :items="roles"
                item-title="name"
                item-value="id"
                chips
                closable-chips
                label="Roles"
                id="roles"
                multiple
                prepend-icon="mdi-dolly"
              ></v-select>
            </v-card-text>
          </v-card>
        </v-tabs-window-item>
      </v-tabs-window>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue-darken-1" variant="text" @click="close">Cancel</v-btn>
        <v-btn color="blue-darken-1" variant="text" :disabled="isSaveDisabled" @click="save">Save</v-btn>
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

  emits: ['update:modelValue', 'close', 'saved'],

  props: {
    modelValue: {
      type: Object,
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
        || ! (this.dataUser.roles && this.dataUser.roles.length)
        || this.isSaving
    },
    rules() {
      return {
        name: [
          v => !!v || 'The field should have a value.'
        ],
        email: [
          v => !!v || 'The field should have a value.'
        ],
        password_confirmation: [
          v => (!!v && v) === this.dataUser.password || 'Password nao coincidem.'
        ],
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
        this.$emit('update:modelValue', user)
        this.$emit('saved', user)
      } catch (error) {
        this.isSaving = false
        alert.error(error)
      }
    },
  },

  watch: {
    modelValue: function(value) {
      this.dataUser = new User({
        roles: [],
        ...value,
      })
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
