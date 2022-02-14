<template>
  <div class="container">
    <div class="box">
      <div class="title">Web solutions & analysis</div>
      <div class="subtitle">
        Bridge the gap between IT and management
      </div>
    </div>
    <div class="row v-center">
      <div class="col col-3">
        <router-link
          tag="a"
          class="card-link services"
          :to="{ name: 'Services' }"
          :style="{ 'background-image': 'url(' + servicesImage + ')' }"
        >
          <card title="Services" color="red" />
        </router-link>
      </div>

      <div class="col col-3">
        <router-link
          tag="a"
          class="card-link"
          :to="{ name: 'Mission' }"
          :style="{ 'background-image': 'url(' + missionImage + ')' }"
        >
          <card title="Mission" color="yellow" />
        </router-link>
      </div>

      <div class="col col-3">
        <router-link
          tag="a"
          class="card-link"
          :to="{ name: 'About' }"
          :style="{ 'background-image': 'url(' + aboutImage + ')' }"
        >
          <card title="About" color="blue" />
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import Card from "../components/Card.vue"
export default {
  name: "Home",
  components: {
    Card,
  },
  data() {
    return {
      servicesImage: require("@/assets/services.jpg"),
      missionImage: require("@/assets/mission.jpg"),
      aboutImage: require("@/assets/about.jpeg"),
    }
  },
}
</script>

<style scoped>

.v-center {
  align-items: center;
}

.thumb-title {
  margin-bottom: 20px;
}

.home-menu {
  font-size: 18px;
  font-weight: 700;
  text-align: center;
  color: rgba(1, 24, 38, 1);
  text-transform: uppercase;
}

.box {
  border-bottom: 3px solid #6c757d;
  padding: 20px;
  color: rgba(1, 24, 38, 1);
}

.card-link:hover {
  /* animation: pulse 2s infinite; */
}

.card-link {
  background-position: center top;
  background-size: cover;
  background-repeat: no-repeat;
  display: block;
  border-radius: 5px;
}
</style>
