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
      <v-simple-table v-if="students.length > 0">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-left">Nome</th>
              <th class="text-left">Horas</th>
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
                  dense
                  placeholder="Só necessita preencher se diferente das horas do curso"
                  class="hours_attended"
                ></v-text-field>
              </td>
              <td>
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
              </td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
    </v-card-text>
  </v-card>
</template>

<script>
import Student from '../../models/Student'
import Model from '../../models/Model'
import alert from '../../plugins/toast'
import findIndex from 'lodash-es/findIndex'
import filter from 'lodash-es/filter'
import cloneDeep from 'lodash-es/cloneDeep'

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
    student: null,
  }),

  computed: {
    rules() {
      return {
        hours_attended: [
          v => v >= 0 || 'O valor deve numérico e positivo'
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
        const { data: students } = await Student
          // .where('not_enrolled', this.programEdition.id || '')
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
