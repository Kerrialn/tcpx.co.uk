<template>
  <div class="footer">
    <div>Copyright © {{ year }} tcpx.co.uk</div>
    <div>
      +420 608 187 548 | info@tcp.co.uk
    </div>
    <div>TCPX LLP - Company Number: OC432045</div>
  </div>
</template>

<script>
export default {
  name: "Footer",
  computed: {
    year() {
      return new Date().getFullYear()
    },
  },
}
</script>

<style scoped>
.footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: rgba(1, 24, 38, 0.05);
  border-top: 3px solid #011826;
  padding: 20px;
}
</style>
