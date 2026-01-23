<template>
  <div class="c-data-table tw-flex tw-flex-col tw-mt-10 tw-mx-20">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'c-data-table'
}
</script>

<style lang="scss">
  .c-data-table {
    table th {
      text-transform: uppercase;
      letter-spacing: 1px;
      .v-icon {
        margin-left: 5px;
      }
    }

    table tr th:last-child,
    table tr td:last-child {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: flex-end;
      padding-right: 50px;
    }
  }
</style>
