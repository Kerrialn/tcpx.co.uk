<template>
  <div class="footer">
    <div>Copyright © {{ year }} tcpx.co.uk</div>
    <div>
      +420 608 187 548 | info@tcp.co.uk
    </div>
    <div>
      <div>Company Number: OC432045</div>
      <div class="muted">
        Office 4, 219 Kensington High Street, Kensington, London, W8 6BD
      </div>
    </div>
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
  background-color: #011826;
  color: #ffffff;
  padding: 20px;
}
</style>
