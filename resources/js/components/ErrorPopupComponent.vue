<script setup>
  import { store } from '../store.js';
</script>

<template>
  <div 
    v-if="store.error"
  >
    <h3>{{ store.errorMsgHeader }}</h3>
    <p>{{ store.errorMsgTxt }}</p>
    <button @click="store.error = false">
      Close
    </button>
  </div>
</template>

<style scoped>
  div{
    position: fixed;
    top: 20vh;
    width: 24em;
    left: calc(50vw - 12.75em);
    background-color: white;
    color: var(--momo-red);
    border: 1px solid var(--momo-red);
    padding-left: 1.5em;
    box-sizing: border-box;
  }
  button{
    float: right;
    padding-right: 1.5em;
  }
</style>