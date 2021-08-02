<template>
  <div id="app">
    <img alt="Vue logo" src="./assets/logo.png">
    {{ info }}
    <HelloWorld msg="Welcome to Your Vue.js App"/>
  </div>
</template>



<script>
import HelloWorld from './components/HelloWorld.vue'


export default {
  name: 'App',
  data() {
    return{
    info: null
    }
  },
  headers: { "Content-Type": "application/json" },
  mounted() {
      delete this.axios.defaults.headers.common['X-CSRF-TOKEN']; 
      delete this.axios.defaults.headers.common['X-Requested-With']; 
      
      this.axios
        //.post('https://cors-anywhere.herokuapp.com/https://univer.kstu.kz/user/login?ReturnUrl=%2f')
      .get('https://cors-anywhere.herokuapp.com/https://univer.kstu.kz/user/login?ReturnUrl=%2f')
      .then(response => (this.info = response));
  },
  components: {
    HelloWorld
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
