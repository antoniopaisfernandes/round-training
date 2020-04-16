<template>
  <div>
    <h2
      class="mt-10 warning tw-text-center tw-text-white tw-font-mono tw-rounded"
    >Export is done with data from the server.</h2>

    <div class="mt-10 container tw-flex tw-justify-between tw-items-end">
      <div>
        <v-switch v-model="cover" label="Cover"></v-switch>
        <v-switch v-model="students" label="Students"></v-switch>
      </div>
      <div>
        <v-btn
          color="primary darken-1"
          :loading="isExporting"
          :disabled="!cover && !students"
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
    programEdition: {
      type: Model,
      required: true,
    },
  },

  data: () => ({
    isExporting: false,
    cover: true,
    students: true,
  }),

  methods: {
    exportExcel() {
      this.isExporting = true
      try {
        this.programEdition.export({
          cover: this.cover,
          students: this.students,
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

<style>

</style>
