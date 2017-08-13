<template>
  <li>Input: {{ data.input }}, response {{ data.response }}</li>
</template>

<script>
  export default {
    props: {
      data: {
        type: Object,
        default: () => ({ input: null, reponse: null })
      }
    }
  }
</script>