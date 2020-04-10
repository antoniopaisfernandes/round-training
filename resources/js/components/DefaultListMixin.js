import alert from '../plugins/toast'

export default {
  props: ['items', 'totalItems'],

  data: () => ({
    list: [],
    totalList: 0,

    options: {},
    editedIndex: -1,

    isLoading: false,
    isSaving: false,
    createVisible: false,
  }),

  created() {
    this.list = this.items.map((c) => this.instance(c))
    this.totalList = this.totalItems || this.list.length
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

  watch: {
    options: {
      async handler (options, oldOptions) {
        // Do nothing when oldOptions are empty since we have hidration
        // from server side when components are build
        if (! oldOptions.page) {
          return
        }

        // After that, let's use ajax to fetch new data and fill the
        // tables or lists
        this.isLoading = true
        try {
          const { data, meta } = await this.instance().fetch(options)
          this.list = data
          this.totalList = meta.total
        } catch (error) {
          this.list = []
          this.totalList = 0
        } finally {
          this.isLoading = false
        }
      },
      deep: true,
    },
  },

}
