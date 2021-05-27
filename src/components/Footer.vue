<template>
  <div class="footer">
    <div class="row ai-center">
      <div class="col col-2">Copyright © {{ year }} tcpx.co.uk</div>
      <div class="col col-2">
        +420 608 187 548 | info@tcp.co.uk
      </div>
      <div class="col col-2">
        <div>Company Number: OC432045</div>

        <small
          >Office 4, 219 Kensington High Street, Kensington, London, W8
          6BD</small
        >
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
  background-color: #011826;
  color: #ffffff;
  padding: 20px;
}
</style>
