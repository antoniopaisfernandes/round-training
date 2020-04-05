<template>
  <v-card>
    <v-card-text>
      <div class="tw-flex tw-items-baseline">
        <div class="tw-flex-1">
          <v-autocomplete
            v-model="student"
            :items="studentsFromQuery"
            :loading="isLoading"
            :search-input.sync="search"
            hide-no-data
            hide-selected
            item-text="name"
            item-value="id"
            label="Alunos"
            placeholder="Nome do aluno"
            prepend-icon="mdi-database-search"
            return-object
          ></v-autocomplete>
        </div>
        <v-btn
          :disabled="!student"
          fab
          dark
          x-small
          color="primary"
          @click="addStudent()"
          class="tw-ml-2"
        >
          <v-icon dark>mdi-plus</v-icon>
        </v-btn>
      </div>

      <div class="tw-mt-6">
        <ul>
          <li v-for="(student, i) in students" :key="i">
            <span v-text="student.name"></span>
            <v-btn
              fab
              dark
              x-small
              color="error"
              @click="deleteStudent(i)"
              class="tw-ml-2 tw--mt-2"
            >
              <v-icon dark>mdi-minus</v-icon>
            </v-btn>
          </li>
        </ul>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
import Student from '../../models/Student'
import Model from '../../models/Model'
import findIndex from 'lodash-es/findIndex'
import filter from 'lodash-es/filter'

export default {
  name: 'program-edition-create-students',

  props: {
    programEdition: {
      type: Model,
      default: function() {
        return new ProgramEdition()
      }
    },
    students: {
      type: Array,
      default: []
    },
  },

  data: () => ({
    isLoading: false,
    search: null,
    studentsFromQuery: [],
    student: null
  }),

  methods: {
    addStudent() {
      this.$emit('add', this.student)
      this.search = null
      this.studentsFromQuery = []
      this.student = null
    },
    deleteStudent(index) {
      this.$emit('delete', index)
    },
  },

  watch: {
    async search (val) {
      if (this.isLoading) {
        return
      }

      this.isLoading = true

      try {
        const { data: students } = await Student
          .where('not_enrolled', this.programEdition.id)
          .orderBy('name')
          .get()

        this.studentsFromQuery = filter(students, (student) => findIndex(this.students, {id: student.id}) < 0)
      } catch (error) {
        console.log(err)
      } finally {
        this.isLoading = false
      }
    },
  },

}
</script>
