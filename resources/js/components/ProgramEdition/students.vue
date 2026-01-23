<template>
  <v-card variant="flat">
    <v-card-text>
      <div class="tw-flex tw-items-baseline">
        <div class="tw-flex-1">
          <v-autocomplete
            v-model="student"
            :items="studentsFromQuery"
            :loading="isLoading"
            v-model:search="search"
            hide-no-data
            hide-selected
            item-title="name"
            item-value="id"
            label="Students"
            placeholder="Student name"
            prepend-icon="mdi-database-search"
            return-object
          ></v-autocomplete>
        </div>
        <v-btn
          :disabled="!student"
          icon
          size="small"
          color="primary"
          @click="addStudent()"
          class="tw-ml-2"
        >
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </div>
      <v-table v-if="students.length > 0">
        <thead>
          <tr>
            <th class="text-left">Name</th>
            <th class="text-left">Hours</th>
            <th class="text-left">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(student, i) in students" :key="i">
            <td>{{ student.name }}</td>
            <td>
              <v-text-field
                v-model="student.pivot.hours_attended"
                :rules="rules.hours_attended"
                density="compact"
                placeholder="You only need to fill if the number of hours is different from the program edition"
                class="hours_attended"
              ></v-text-field>
            </td>
            <td>
              <v-btn
                icon
                size="small"
                color="error"
                @click="deleteStudent(i)"
                class="tw-ml-2 tw--mt-2"
              >
                <v-icon>mdi-minus</v-icon>
              </v-btn>
            </td>
          </tr>
        </tbody>
      </v-table>
    </v-card-text>
  </v-card>
</template>

<script>
import Student from '../../models/Student'
import Model from '../../models/Model'
import ProgramEdition from '../../models/ProgramEdition'
import alert from '../../plugins/toast'
import findIndex from 'lodash-es/findIndex'
import filter from 'lodash-es/filter'
import cloneDeep from 'lodash-es/cloneDeep'

export default {
  name: 'program-edition-create-students',

  emits: ['add', 'delete'],

  props: {
    programEdition: {
      type: Object,
      default: function() {
        return new ProgramEdition()
      }
    },
    students: {
      type: Array,
      default: () => []
    },
  },

  data: () => ({
    isLoading: false,
    search: null,
    studentsFromQuery: [],
    student: null,
  }),

  computed: {
    rules() {
      return {
        hours_attended: [
          v => v >= 0 || 'O valor deve numerico e positivo'
        ],
      }
    },
  },

  methods: {
    addStudent() {
      let student = cloneDeep(this.student)
      student.pivot = {
        'hours_attended': null
      }
      this.$emit('add', new Student(student))
      this.search = null
      this.studentsFromQuery = []
      this.student = null
    },
    deleteStudent(index) {
      this.$emit('delete', index)
    },
  },

  watch: {
    programEdition (val) {
      this.student = null
      this.studentsFromQuery = []
    },

    async search (val) {
      if (this.isLoading) {
        return
      }

      this.isLoading = true
      this.studentsFromQuery = []

      try {
        const students = await Student
          .orderBy('name')
          .get()

        this.studentsFromQuery = filter(students, (student) => findIndex(this.students, {id: student.id}) < 0)
      } catch (error) {
        alert.error(error)
      } finally {
        this.isLoading = false
      }
    },
  },

}
</script>

<style>
  .hours_attended input::placeholder {
    font-size: xx-small;
  }
</style>
