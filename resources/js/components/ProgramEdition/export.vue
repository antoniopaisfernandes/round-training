<template>
  <div>
    <h2
      class="mt-10 warning tw-text-center tw-text-white tw-font-mono tw-rounded"
    >A exportação ocorre sempre baseado nos dados do servidor pelo que necessitará gravar quaisquer alterações que tenha efectuado</h2>

    <div class="mt-10 container tw-flex tw-justify-between tw-items-end">
      <div>
        <v-switch v-model="generic" label="Dados globais"></v-switch>
        <v-switch v-model="students" label="Informação dos alunos"></v-switch>
      </div>
      <div>
        <v-btn color="primary darken-1" :disabled="!generic && !students" @click="exportExcel">Exportar</v-btn>
      </div>
    </div>
  </div>
</template>

<script>
import alert from '../../plugins/toast'
import Model from '../../models/Model'

export default {

  props: {
    programEdition: {
      type: Model,
      required: true,
    },
  },

  data: () => ({
    generic: true,
    students: true,
  }),

  methods: {
    exportExcel() {
      alert.warning('todo')
    }
  },
}
</script>

<style>

</style>
