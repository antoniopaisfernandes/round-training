<template>
  <v-form
    ref="form"
  >
    <v-card
      max-width="250"
      class="mx-auto"
    >
      <div class="px-2 tw-text-xl tw-text-center primary tw-text-white" v-text="title"></div>
      <div class="container py-0">

        <div v-if="dateFormat == 'date-range'">
          <v-menu
            v-model="startsAtActive"
            :close-on-content-click="false"
            transition="scale-transition"
            min-width="none"
          >
            <template v-slot:activator="{ props }">
              <v-text-field
                v-model="startsAt"
                label="From"
                prepend-inner-icon="mdi-calendar"
                readonly
                v-bind="props"
                class="mt-4"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="startsAt"
              @update:model-value="startsAtActive = false"
            ></v-date-picker>
          </v-menu>
          <v-menu
            v-model="endsAtActive"
            :close-on-content-click="false"
            transition="scale-transition"
            min-width="none"
          >
            <template v-slot:activator="{ props }">
              <v-text-field
                v-model="endsAt"
                label="To"
                prepend-inner-icon="mdi-calendar"
                readonly
                v-bind="props"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="endsAt"
              @update:model-value="endsAtActive = false"
            ></v-date-picker>
          </v-menu>
        </div>

        <div v-if="dateFormat == 'year'">
          <v-select
            prepend-inner-icon="mdi-calendar"
            :items="years"
            label="Year"
            class="mt-4"
            v-model="year"
          ></v-select>
        </div>
      </div>
      <div class="container tw-text-xs">{{ description }}</div>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          icon="mdi-microsoft-excel"
          class="primary"
          :loading="loading"
          :disabled="loading || ! valid"
          @click="submit"
        >
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-form>
</template>

<script>
import jsFileDownload from 'js-file-download'
import alert from '../../plugins/toast'

export default {

  props: {
    title: {
      type: String,
      default: null
    },
    description: {
      type: String,
      default: null
    },
    report: {
      type: String,
      default: null
    },
    dateFormat: {
      type: String,
      default: 'date-range'
    },
    beginDate: {
      type: [Date, null],
      default: null
    },
    endDate: {
      type: [Date, null],
      default: null
    }
  },

  data: () => {
    return {
      loading: false,
      year: null,
      startsAtActive: false,
      startsAt: null,
      endsAtActive: false,
      endsAt: null,
    }
  },

  computed: {
    years: function () {
      const currentYear = new Date().getFullYear();
      const range = (start, stop, step) => Array.from({ length: (stop - start) / step + 1}, (_, i) => start + (i * step));
      return range(currentYear, currentYear - 20, -1);
    },
    form: function () {
      return {
        'name': this.report,
        'year': this.year,
        'begin_date': this.startsAt,
        'end_date': this.endsAt,
      }
    },
    valid: function () {
      return this.year || this.startsAt;
    },
  },

  methods: {
    async submit() {
      try {
        this.loading = true;
        let response = await axios.post(`/reports/${this.report}`, this.form, {
          responseType: 'arraybuffer'
        });
        jsFileDownload(response.data, `${this.report}.xlsx`);
      } catch (error) {
        alert.error(error)
      } finally {
        this.loading = false;
      }
    }
  },

  mounted() {
    this.startsAt = this.beginDate
    this.endsAt = this.endDate
  }

}
</script>
