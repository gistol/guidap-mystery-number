<template>
  <section>
    <h2>Plays history</h2>

    <ul>
      <li v-for="guess in guesses">
        <app-guess v-bind:data="guess"></app-guess>
      </li>
    </ul>
  </section>
</template>

<script>
  import AppGuess from './Guess';

  export default {
    props: {
      guesses: {
        type: Array,
        default: () => []
      }
    },
    components: { AppGuess }
  }
</script>