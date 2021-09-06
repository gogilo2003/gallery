<template>
  <div
    @click.self="closeModal"
    class="modal fade"
    :class="[
      { 'show d-block': show },
      { 'd-none': !show },
      { backdrop: backdrop },
    ]"
  >
    <div
      class="modal-dialog"
      :class="[
        { 'modal-xl': size === 'xl' },
        { 'modal-lg': size === 'lg' },
        { 'modal-sm': size === 'sm' },
        { 'modal-dialog-centered': centered },
        { 'modal-dialog-scrollable': scrollable },
      ]"
    >
      <div class="modal-content">
        <slot></slot>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "modal",
  props: {
    show: {
      type: Boolean,
      default: false,
    },
    size: {
      type: String,
      default: "",
    },
    centered: {
      type: Boolean,
      default: true,
    },
    scrollable: {
      type: Boolean,
      default: true,
    },
    backdrop: {
      type: Boolean,
      default: true,
    },
  },
  methods: {
    closeModal() {
      if (!this.backdrop) {
        this.$emit("update:show", false);
        this.$emit("close");
      }
    },
  },
  watch: {
    show(val) {
      let documentClasses = document.body.classList;
      if (val) {
        documentClasses.add("modal-open");
      } else {
        documentClasses.remove("modal-open");
      }
    },
  },
};
</script>

<style scoped>
.backdrop {
  background-color: rgba(0, 0, 0, 0.35);
}
.modal-dialog {
  max-width: 500px;
}
.modal-dialog.modal-sm {
  max-width: 300px;
}
.modal-dialog.modal-lg {
  max-width: 800px;
}
.modal-dialog.modal-xl {
  max-width: 1140px;
}
.modal-dialog-scrollable .modal-content .modal-body, .modal-dialog-scrollable .modal-content .card-body .card-body{
  max-height: 500px;
  overflow-y: auto;
}
</style>