<template>
  <div class="loading">
    <div class="circle"></div>
    <img alt="Logo" class="logo" src="@/assets/round-sm.png" />
  </div>
</template>

<script>
export default {}
</script>

<style scoped>
.circle {
  width: 200px;
  height: 200px;
  background-color: #011826;
  position: absolute;
  border-radius: 50%;
  z-index: 1;
  animation: pulse 2s infinite;
}

.loading {
  position: fixed;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
}

.logo {
  z-index: 2;
  width: 200px;
}
</style>
