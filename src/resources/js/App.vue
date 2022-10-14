<template>
  <div id="app">
    <h1>qaedfmkwneufbnwujefbhwebf</h1>
  </div>
</template>

<script>
import ExampleComponent from './components/ExampleComponent.vue';

export default {
    components: { ExampleComponent }
}
</script>

<style>
body {
    background-image: url('./assets/background.svg');
    height: 100vh;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;

    display: flex;
    flex-direction: column;
    justify-content: center;
    flex-wrap: nowrap;
    align-items: center;
}

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@900&family=Roboto+Condensed:wght@700&family=Roboto:wght@400;500;700;900&display=swap');

* {
    margin: 0px;
    font-family: 'Inter', sans-serif;
    font-family: 'Roboto', sans-serif;
    font-family: 'Roboto Condensed', sans-serif;
    color: white;
}

</style>