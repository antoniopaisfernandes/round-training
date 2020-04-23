<template>
  <v-dialog v-model="dataVisible" @keydown.esc="dataVisible = false" max-width="500px">
    <v-card :loading="isSaving" class="px-5 py-5" height="90vh">
      <v-card-title>
        <span class="headline">Empresa</span>
      </v-card-title>
      <v-card-text class="mt-5">
        <v-text-field
          autofocus
          v-model="dataCompany.name"
          label="Nome"
          required
          :rules="rules.name"
        ></v-text-field>
        <div class="tw-flex">
          <v-text-field
            v-model="dataCompany.short_name"
            label="Abreviatura"
            required
            :rules="rules.short_name"
            class="tw-w-1/2"
          ></v-text-field>
          <v-text-field
            v-model="dataCompany.vat_number"
            label="Contribuinte"
            required
            :rules="rules.vat_number"
            class="tw-w-1/2 tw-ml-2"
          ></v-text-field>
        </div>
        <div class="mt-2">
          <span class="subtitle-1">Orçamentos</span>
          <v-btn
            fab
            dark
            x-small
            color="primary"
            @click="addBudget"
            class="tw-ml-2 tw--mt-2"
          >
            <v-icon dark>mdi-plus</v-icon>
          </v-btn>
        </div>
        <div v-bind:key="i" v-for="(yearBudget, i) in dataCompany.budgets" class="tw-flex">
          <div class="tw-w-1/2">
            <v-text-field label="Ano" v-model="yearBudget.year"></v-text-field>
          </div>
          <div class="tw-ml-2">
            <v-text-field label="Orçamento" v-model="yearBudget.budget" prefix="€" class="money"></v-text-field>
          </div>
          <div class="tw-w-5/100 tw-ml-2 tw-flex tw-items-center">
            <v-btn
              fab
              dark
              x-small
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
        <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
        <v-btn color="blue darken-1" text :disabled="isSaveDisabled" @click="save">Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Company from '../../models/Company'
import CompanyYearlyBudget from '../../models/CompanyYearlyBudget'
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
          v => !!v || 'É obrigatória a indicação de um valor para o campo.'
        ],
        vat_number: [
          v => !!v || 'É obrigatória a indicação de um valor para o campo.'
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
      this.dataCompany = new Company({
        budgets: [],
        ...value,
      })
    },
    visible: function(value) {
      this.dataVisible = value
    },
  },
}
</script>

<style>
  .money input {
    text-align: end;
  }
</style>
