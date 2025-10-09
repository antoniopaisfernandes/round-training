<template>
  <v-dialog v-model="dataVisible" @keydown.esc="dataVisible = false" max-width="500px">
    <v-card :loading="isSaving" class="px-5 py-5" height="90vh">
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
        <div class="tw-flex">
          <v-text-field
            v-model="dataCompany.short_name"
            label="Short name"
            required
            :rules="rules.short_name"
            class="tw-w-1/2"
          ></v-text-field>
          <v-text-field
            v-model="dataCompany.vat_number"
            label="VAT Number"
            required
            :rules="rules.vat_number"
            class="tw-w-1/2 tw-ml-2"
          ></v-text-field>
        </div>
        <div>
          <v-select
            :items="users"
            v-model="dataCompany.coordinator_id"
            label="Local coordinator"
            required
          ></v-select>
        </div>
        <div class="mt-2">
          <span class="subtitle-1">Budgets</span>
          <v-btn
            fab
            dark
            size="x-small"
            color="primary"
            @click="addBudget"
            class="tw-ml-2 tw--mt-2"
          >
            <v-icon dark>mdi-plus</v-icon>
          </v-btn>
        </div>
        <div v-bind:key="i" v-for="(yearBudget, i) in dataCompany.budgets" class="tw-flex">
          <div class="tw-w-1/2">
            <v-text-field label="Year" v-model="yearBudget.year"></v-text-field>
          </div>
          <div class="tw-ml-2">
            <v-text-field label="Budget" v-model="yearBudget.budget" prefix="€" class="money"></v-text-field>
          </div>
          <div class="tw-w-5/100 tw-ml-2 tw-flex tw-items-center">
            <v-btn
              fab
              dark
              size="x-small"
              color="error"
              @click="deleteBudget(i)"
              class="tw-ml-2 tw--mt-2"
            >
              <v-icon dark>mdi-minus</v-icon>
            </v-btn>
          </div>
        </div>

      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue -darken-1" variant="text" @click="close">Cancel</v-btn>
        <v-btn color="blue -darken-1" variant="text" :disabled="isSaveDisabled" @click="save">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Company from '../../models/Company'
import CompanyYearlyBudget from '../../models/CompanyYearlyBudget'
import Model from '../../models/Model'
import User from '../../models/User'
import alert from '../../plugins/toast'
import map from 'lodash-es/map'

export default {
  name: 'company-create',


  props: {
    modelValue: {
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
    users: [],
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
          v => !!v || 'The field should have a value.'
        ],
        vat_number: [
          v => !!v || 'The field should have a value.'
        ]
      }
    },
  },

  methods: {
    // Budgets
    addBudget() {
      this.dataCompany.budgets.push(new CompanyYearlyBudget)
    },
    deleteBudget(index) {
      this.dataCompany.budgets.splice(index, 1)
    },

    close() {
      this.dataVisible = false
    },
    async save() {
      this.isSaving = true

      try {
        let company = await this.dataCompany.save()
        this.isSaving = false
        this.close()
        this.$emit('update:modelValue', company)
        this.$emit('saved', company)
      } catch (error) {
        this.isSaving = false
        alert.error(error)
      }
    },

    async getUsers() {
      try {
        let data = await User.limit(999).$get()

        this.users = map(data, (v) => {
          return {
            'text': v.name,
            'value': v.id,
          }
        })
      } catch (error) {
        alert.error(error)
      }
    },
  },

  watch: {
    modelValue: function(value) {
      this.dataCompany = new Company({
        budgets: [],
        ...value,
      })
    },
    visible: function(value) {
      this.dataVisible = value
    },
  },

  mounted: async function () {
    this.getUsers()
  },
}
</script>

<style>
  .money input {
    text-align: end;
  }
</style>
