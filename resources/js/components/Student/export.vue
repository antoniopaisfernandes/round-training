<template>
  <div>
    <h2
      class="mt-10 bg-warning tw-text-center tw-text-white tw-font-mono tw-rounded"
    >The export is based on the server data. You need to save before exporting.</h2>

    <div class="mt-10 container tw-flex tw-justify-between tw-items-end">
      <div>
        <v-switch v-model="cover" label="Global data" color="primary"></v-switch>
        <v-switch v-model="programEditions" label="Program edition informations" color="primary"></v-switch>
      </div>
      <div>
        <v-btn
          color="primary-darken-1"
          :loading="isExporting"
          :disabled="!cover && !programEditions"
          @click="exportExcel"
        >Export</v-btn>
      </div>
    </div>
  </div>
</template>

<script>
import alert from '../../plugins/toast'
import Model from '../../models/Model'

export default {

  props: {
    student: {
      type: Object,
      required: true,
    },
  },

  data: () => ({
    isExporting: false,
    cover: true,
    programEditions: true,
  }),

  methods: {
    exportExcel() {
      this.isExporting = true
      try {
        this.student.export({
          cover: this.cover,
          program_editions: this.programEditions,
        })
      } catch (error) {
        alert.warning(error)
      } finally {
        this.isExporting = false
      }
    }
  },

}
</script>
