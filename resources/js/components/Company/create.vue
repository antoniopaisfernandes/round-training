<template>
  <v-dialog v-model="dataVisible" @keydown.esc="dataVisible = false" max-width="500px">
    <v-card :loading="isSaving" class="px-5 py-5">
      <v-card-title>
        <span class="headline">Company</span>
      </v-card-title>
      <v-card-text class="mt-5">
        <v-text-field
          autofocus
          v-model="dataCompany.name"
          label="Name"
          required
          :rules="rules.name"
        ></v-text-field>
        <v-text-field
          v-model="dataCompany.vat_number"
          label="VAT"
          required
          :rules="rules.vat_number"
        ></v-text-field>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
        <v-btn color="blue darken-1" text :disabled="isSaveDisabled" @click="save">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Company from '../../models/Company'
import Model from '../../models/Model'
import alert from '../../plugins/toast'

export default {
  name: 'company-create',

  model: {
    prop: 'company',
    event: 'input'
  },

  props: {
    company: {
      type: Model,
      default: function() {
        return new Company()
      }
    },
    visible: {
      type: Boolean
    },
  },

  data: () => ({
    dataCompany: new Company(),
    isSaving: false,
  }),

  computed: {
    dataVisible: {
      get () {
        return this.visible
      },
      set (value) {
        if (! value) {
          this.$emit('close')
          this.dataCompany = new Company()
        }
      }
    },
    isSaveDisabled() {
      return ! this.dataCompany.name
        || ! this.dataCompany.vat_number
        || this.isSaving
    },
    rules() {
      return {
        name: [
          v => !!v || 'The field is mandatory.'
        ],
        vat_number: [
          v => !!v || 'The field is mandatory.'
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
        let company = await this.dataCompany.save()
        this.isSaving = false
        this.close()
        this.$emit('input', company)
        this.$emit('saved', company)
      } catch (error) {
        this.isSaving = false
        alert.error(error)
      }
    },
  },

  watch: {
    company: function(value) {
      this.dataCompany = value
    },
    visible: function(value) {
      this.dataVisible = value
    },
  },
}
</script>
