<template>
<div class="row position-relative">

<HeaderPage></HeaderPage>
<router-view></router-view>
</div>

</template>

<script>
import HeaderPage from '@/components/HeaderPage.vue';
export default {
  components: {
    HeaderPage,
  },
}
</script>
<style lang="scss">

@font-face {
  font-family: 'Inter';
  src: url('./assets/fonts/brown/Steak.otf') format('truetype');
}
#app {
  font-family: 'Inter';
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
nav {
  padding: 30px;

  a {
    font-weight: bold;
    color: #2c3e50;

    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
</style>
