<template>
  <v-dialog v-model="display" :width="dialogWidth">
    <template v-slot:activator="{ props }">
      <v-text-field
        v-bind="{ ...textFieldProps, ...props }"
        :disabled="disabled"
        :loading="loading"
        :label="label"
        :model-value="formattedDatetime"
        readonly
      >
        <template v-slot:loader>
          <slot name="progress">
            <v-progress-linear color="primary" indeterminate absolute height="2"></v-progress-linear>
          </slot>
        </template>
      </v-text-field>
    </template>

    <v-card>
      <v-card-text class="px-0 py-0">
        <v-tabs v-model="activeTab">
          <v-tab value="calendar">
            <slot name="dateIcon">
              <v-icon>mdi-calendar</v-icon>
            </slot>
          </v-tab>
          <v-tab value="timer" :disabled="dateSelected">
            <slot name="timeIcon">
              <v-icon>mdi-clock-outline</v-icon>
            </slot>
          </v-tab>
        </v-tabs>
        <v-tabs-window v-model="activeTab">
          <v-tabs-window-item value="calendar">
            <v-date-picker
              v-model="dateValue"
              v-bind="datePickerProps"
              @update:model-value="showTimePicker"
            ></v-date-picker>
          </v-tabs-window-item>
          <v-tabs-window-item value="timer">
            <v-time-picker
              ref="timer"
              v-model="time"
              v-bind="timePickerProps"
            ></v-time-picker>
          </v-tabs-window-item>
        </v-tabs-window>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <slot name="actions" :parent="this">
          <v-btn color="grey-lighten-1" variant="text" @click="clearHandler">{{ clearText }}</v-btn>
          <v-btn color="green-darken-1" variant="text" @click="okHandler">{{ okText }}</v-btn>
        </slot>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { format, parse } from 'date-fns'

const DEFAULT_DATE = ''
const DEFAULT_TIME = '00:00:00'
const DEFAULT_DATE_FORMAT = 'yyyy-MM-dd'
const DEFAULT_TIME_FORMAT = 'HH:mm:ss'
const DEFAULT_DIALOG_WIDTH = 340
const DEFAULT_CLEAR_TEXT = 'LIMPAR'
const DEFAULT_OK_TEXT = 'OK'

export default {
  name: 'v-datetime-picker',
  emits: ['update:modelValue'],
  props: {
    modelValue: {
      type: [Date, String],
      default: null
    },
    disabled: {
      type: Boolean
    },
    loading: {
      type: Boolean
    },
    label: {
      type: String,
      default: ''
    },
    dialogWidth: {
      type: Number,
      default: DEFAULT_DIALOG_WIDTH
    },
    dateFormat: {
      type: String,
      default: DEFAULT_DATE_FORMAT
    },
    timeFormat: {
      type: String,
      default: DEFAULT_TIME_FORMAT
    },
    clearText: {
      type: String,
      default: DEFAULT_CLEAR_TEXT
    },
    okText: {
      type: String,
      default: DEFAULT_OK_TEXT
    },
    textFieldProps: {
      type: Object,
      default: () => ({})
    },
    datePickerProps: {
      type: Object,
      default: () => ({})
    },
    timePickerProps: {
      type: Object,
      default: function() {
        return {
          format: '24hr',
          scrollable: true
        }
      }
    }
  },
  data() {
    return {
      display: false,
      activeTab: 'calendar',
      date: DEFAULT_DATE,
      dateValue: null,
      time: DEFAULT_TIME
    }
  },
  mounted() {
    this.init()
  },
  computed: {
    dateTimeFormat() {
      return this.dateFormat + ' ' + this.timeFormat
    },
    defaultDateTimeFormat() {
      return DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT
    },
    formattedDatetime() {
      return this.selectedDatetime ? format(this.selectedDatetime, this.dateTimeFormat) : ''
    },
    selectedDatetime() {
      if (this.date && this.time) {
        let datetimeString = this.date + ' ' + this.time
        if (this.time.length === 5) {
          datetimeString += ':00'
        }
        return parse(datetimeString, this.defaultDateTimeFormat, new Date())
      } else {
        return null
      }
    },
    dateSelected() {
      return !this.date
    }
  },
  methods: {
    init() {
      if (!this.modelValue) {
        return
      }

      let initDateTime
      if (this.modelValue instanceof Date) {
        initDateTime = this.modelValue
      } else if (typeof this.modelValue === 'string' || this.modelValue instanceof String) {
        initDateTime = parse(this.modelValue, this.dateTimeFormat, new Date())
      }

      this.date = format(initDateTime, DEFAULT_DATE_FORMAT)
      this.dateValue = initDateTime
      this.time = format(initDateTime, DEFAULT_TIME_FORMAT)
    },
    okHandler() {
      this.resetPicker()
      this.$emit('update:modelValue', this.selectedDatetime)
    },
    clearHandler() {
      this.resetPicker()
      this.date = DEFAULT_DATE
      this.dateValue = null
      this.time = DEFAULT_TIME
      this.$emit('update:modelValue', null)
    },
    resetPicker() {
      this.display = false
      this.activeTab = 'calendar'
      if (this.$refs.timer) {
        this.$refs.timer.selectingHour = true
      }
    },
    showTimePicker(value) {
      if (value) {
        this.date = format(new Date(value), DEFAULT_DATE_FORMAT)
        this.activeTab = 'timer'
      }
    }
  },
  watch: {
    modelValue: function() {
      this.init()
    }
  }
}
</script>

<style>
.v-picker__title {
  min-height: 110px;
  height: 110px;
  font-size: 0.5rem;
}
</style>
