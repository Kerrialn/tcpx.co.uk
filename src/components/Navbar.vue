<template>
  <div class="navbar">
    <div>
      <router-link class="navbar-link brand" tag="a" :to="{ name: 'Home' }"
        >TCPX</router-link
      >
    </div>
    <div>
      <router-link class="navbar-link" tag="a" :to="{ name: 'Services' }"
        >Services</router-link
      >
      <router-link class="navbar-link" tag="a" :to="{ name: 'Mission' }"
        >Mission</router-link
      >
      <router-link class="navbar-link" tag="a" :to="{ name: 'About' }"
        >About</router-link
      >
    </div>
  </div>
</template>

<script>
export default {
  name: "Navbar",
}
</script>

<style scoped>
.navbar .brand {
  font-weight: bold;
  font-size: 24px;
  color: #000;
}
.navbar .brand:hover {
  font-weight: bold;
  font-size: 24px;
  color: #000;
}

.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  margin-top: 10px;
}

.navbar-link {
  padding: 20px;
  text-decoration: none;
  text-transform: uppercase;
  font-size: 14px;
  color: #444;
}

.navbar-link:hover {
  color: #ccc;
  transition: ease-in-out 0.3s;
}
</style>
