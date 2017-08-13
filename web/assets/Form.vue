<template>
  <form @submit.prevent="onSubmit">
    <input type="number" name="guess" v-model.number="guess" :min="min" :max="max" required>
    <button type="button" @click="plus">+</button>
    <button type="button" @click="minus">-</button>
    <input type="submit" value="Send">
  </form>
</template>

<script>
  export default {
    props: {
      min: {
        type: Number,
        default: 0
      },
      max: {
        type: Number,
        default: 100
      }
    },
    data: function () {
      return {
        guess: 0
      }
    },
    methods: {
      onSubmit: function () {
        if (!Number.isInteger(this.guess)) { return; }

        // copy guess value to be sure to have the good value on callback
        let guessValue = this.guess;
        this.$http.get('/api/mystery', { params: { guess: guessValue } }).then(response => {
          this.$emit('play-response', { input: guessValue, response: response.data.data });
        }, response => {
          console.error(response);
        });
      },
      plus: function() {
        if (this.guess < this.max) {
          this.guess++;
        }
      },
      minus: function() {
        if (this.guess > this.min) {
          this.guess--;
        }
      }
    },
  }
</script>