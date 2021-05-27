<template>
  <div class="container">
    <div class="box">
      <div class="title">Web solutions & analysis</div>
      <div class="subtitle">
        Bridging the gap between IT and management
      </div>
    </div>
    <div class="row v-center">
      <div class="col col-3">
        <router-link tag="a" class="card-link" :to="{ name: 'Services' }">
          <div class="home-menu" title="Services">
            <div class="thumb-title">Services</div>
            <div>
              <img class="thumb-img services" :src="servicesImage" />
            </div>
          </div>
        </router-link>
      </div>

      <div class="col col-3">
        <router-link tag="a" class="card-link" :to="{ name: 'Mission' }">
          <div class="home-menu" title="Mission">
            <div class="thumb-title">Mission</div>
            <div>
              <img class="thumb-img mission" :src="missionImage" />
            </div>
          </div>
        </router-link>
      </div>

      <div class="col col-3">
        <router-link tag="a" class="card-link" :to="{ name: 'About' }">
          <div class="home-menu" title="About">
            <div class="thumb-title">About</div>
            <div>
              <img class="thumb-img about" :src="aboutImage" />
            </div>
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Home",
  data() {
    return {
      servicesImage: require("@/assets/services.png"),
      missionImage: require("@/assets/mission.png"),
      aboutImage: require("@/assets/about.png"),
    }
  },
}
</script>

<style scoped>
.v-center {
  min-height: 70vh;
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

.card-link:hover .thumb-img {
  animation: pulse 2s infinite;
}

.thumb-img {
  height: 250px;
  width: 250px;
  border-radius: 50%;
  object-fit: cover;
  background-size: cover;
  background-position: center center;
}

.services {
  border: 3px solid #ef0b59;
}

.mission {
  border: 3px solid #ffce05;
}

.about {
  border: 3px solid #37a2b9;
}
</style>
