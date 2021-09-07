<template>
  <div ref="gallery"></div>
</template>

<script>
export default {
  props: {
    wrapper: {
      type: String,
      default: "",
    },
    albumWrapper: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      albums: [],
    };
  },
  mounted() {
    let x = this.wrapper.split(".");
    // let el = document.createElement(x[0]);
    // el.classList.add(x[1]);
    this.$refs.gallery.classList.add(x[1]);
    // this.$refs.gallery.appendChild(el);

    let albumEl = this.albumWrapper.split('>')
    console.log(albumEl)
  },
  created() {
    axios
      .get("/api/gallery/albums")
      .then((response) => {
        this.albums = response.data;
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>