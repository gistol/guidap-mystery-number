<template>
  <section>
    <h1>Guess the mystery number !!!</h1>

    <app-form @play-response="play($event)"></app-form>
    <app-guess-list v-bind:guesses="guesses"></app-guess-list>
  </section>
</template>

<script>
  import AppForm from './Form';
  import AppGuessList from './GuessList';

  export default {
    components: { AppForm, AppGuessList },
    data: function() {
      return {
        guesses: []
      }
    },
    methods: {
      play: function($event) {
        if ($event.response === '=') {
          // sorry for that but "no design front"
          alert(`Victory !!!\nThe mystery number was ${$event.input}.\nThank you for playing !!!\nClick 'Ok' to start a new game.`);
          this.guesses = [];
        }
        else {
          this.guesses.unshift({ input: $event.input, response: $event.response });
        }
      }
    }
  }
</script>