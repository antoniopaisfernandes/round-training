<template>
  <div>
    <h2
      class="mt-10 bg-warning tw-text-center tw-text-white tw-font-mono tw-rounded"
    >The export is based on the server data. You need to save before exporting.</h2>

    <div class="mt-10 container tw-flex tw-justify-between tw-items-end">
      <div>
        <v-switch v-model="cover" label="Global data" color="primary"></v-switch>
        <v-switch v-model="studentsData" label="Students information" color="primary"></v-switch>
      </div>
      <div>
        <v-btn
          color="primary-darken-1"
          :loading="isExporting"
          :disabled="!cover && !studentsData"
          @click="exportExcel"
        >Export</v-btn>
      </div>
    </div>
    <div class="tw-mt-8 tw-flex">
      <div class="tw-mx-auto">
        Reports
      </div>
    </div>
    <div class="tw-mt-8 tw-flex">
      <v-btn
        class="tw-mx-auto"
        color="primary-darken-1"
        :loading="isExporting"
        @click="reports('evaluation')"
      >Training assessment report</v-btn>
    </div>
  </div>
</template>

<script>
import alert from '../../plugins/toast'
import Model from '../../models/Model'
import jsFileDownload from 'js-file-download'

export default {

  props: {
    programEdition: {
      type: Object,
      required: true,
    },
  },

  data: () => ({
    isExporting: false,
    cover: true,
    studentsData: true,
  }),

  methods: {
    exportExcel() {
      this.isExporting = true
      try {
        this.programEdition.export({
          cover: this.cover,
          students: this.studentsData,
        })
      } catch (error) {
        alert.warning(error)
      } finally {
        this.isExporting = false
      }
    },

    async reports(name) {
      this.isExporting = true
      try {
        let response = await axios.post(
          `/reports/${name}`,
          {
            name,
            program_edition_id: this.programEdition.id
          },
          { responseType: 'arraybuffer' }
        );
        jsFileDownload(response.data, `${name}.xlsx`);
      } catch (error) {
        alert.warning(error)
      } finally {
        this.isExporting = false
      }
    },
  },

}
</script>
