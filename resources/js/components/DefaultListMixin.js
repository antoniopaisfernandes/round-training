import alert from '../plugins/toast'

export default {
  props: ['items'],

  data: () => ({
    list: [],
    editedIndex: -1,
    isSaving: false,
    createVisible: false,
  }),

  created() {
    this.list = this.items.map((c) => this.instance(c))
  },

  methods: {
    instance(attributes) {
      throw Error('Instance method must be defined in parent')
    },

    newItem() {
      this.editedIndex = -1
      this.editedItem = this.instance()
      this.createVisible = true
    },

    editItem(item) {
      this.editedIndex = this.list.indexOf(item)
      this.editedItem = item.clone()
      this.createVisible = true
    },

    async deleteItem(item) {
      const index = this.list.indexOf(item)

      if (! confirm('Tem a certeza que pretende remover este registo?')) {
        return
      }

      try {
        item.delete()
        this.list.splice(index, 1)
      } catch (error) {
        alert.error(error)
      }
    },

    saved(item) {
      if (this.editedIndex > -1) {
        Object.assign(this.list[this.editedIndex], item)
      } else {
        this.list.push(item)
      }
      this.close()
    },

    close() {
      //
    },
  },

}
