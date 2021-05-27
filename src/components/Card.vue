<template>
  <div>
    <div :class="['card', color, { link: link }]">
      <div class="card-header">
        {{ number ? number + "." : null }} {{ title }}
      </div>
      <div class="card-inner">{{ content }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Card",
  props: {
    title: String,
    number: Number,
    content: String,
    color: String,
    link: Boolean,
  },
}
</script>

<style scoped>
.card.orange .card-header {
  border-bottom: 3px solid #e65100;
}

.card.orange:hover {
  background-color: rgba(230, 81, 0, 0.05);
}
.card.green .card-header {
  border-bottom: 3px solid #388e3c;
}

.card.green:hover {
  background-color: rgba(56, 142, 60, 0.05);
}

.card.purple .card-header {
  border-bottom: 3px solid #4527a0;
}

.card.purple:hover {
  background-color: rgba(69, 39, 160, 0.05);
}

.card {
  background-color: white;
  border-radius: 4px;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
  margin-bottom: 20px;
}
.card:hover {
  transition: ease-in-out 0.3s;
}

.card {
  color: #000;
}

.card.link:hover {
  cursor: pointer;
}

.card-header {
  padding: 20px;
  font-size: 20px;
}
.card-inner {
  padding: 20px;
}
</style>
