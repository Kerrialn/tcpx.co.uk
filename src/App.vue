<template>
  <div id="app">
    <loading v-if="isLoading" />
    <navbar />
    <router-view class="router-view" />
    <foot />
  </div>
</template>

<script>
import Loading from "@/components/Loading.vue"
import { mapGetters, mapMutations } from "vuex"
import Navbar from "@/components/Navbar.vue"
import Foot from "@/components/Footer.vue"
export default {
  name: "App",
  mounted() {
    this.setLoading(true)

    window.setTimeout(() => {
      this.setLoading(false)
    }, 3000)
  },
  components: {
    Loading,
    Navbar,
    Foot,
  },
  methods: {
    ...mapMutations({
      setLoading: "setLoading",
    }),
  },
  computed: {
    ...mapGetters({
      isLoading: "getLoading",
    }),
  },
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}

.card-link {
  text-decoration: none;
}

.container {
  margin: 0 auto;
  min-width: 80%;
  max-width: 90%;
}

.router-view {
  min-height: 100vh;
}

.row {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.col {
  flex: 0 0 40%;
  margin: 10px;
}

.col.col-3 {
  flex: 2 2 30% !important;
}

.col.col-9 {
  flex: 9 9 60% !important;
}

@media (max-width: 800px) {
  .col {
    flex: 100%;
  }
}

@keyframes pulse {
  0% {
    -moz-box-shadow: 0 0 0 0 rgba(204, 169, 44, 0.4);
    box-shadow: 0 0 0 0 rgba(204, 169, 44, 0.4);
  }
  70% {
    -moz-box-shadow: 0 0 0 20px rgba(204, 169, 44, 0);
    box-shadow: 0 0 0 20px rgba(204, 169, 44, 0);
  }
  100% {
    -moz-box-shadow: 0 0 0 0 rgba(204, 169, 44, 0);
    box-shadow: 0 0 0 0 rgba(204, 169, 44, 0);
  }
}

.muted {
  text-align: center;
  color: gray;
  font-size: 14px;
}
</style>
