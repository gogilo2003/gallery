<template>
  <div class="list-group">
    <span
      @click="changeAlbum({id:0})"
      key="-1"
      class="list-group-item list-group-item-action"
      :class="{ active: selectedAlbum.id == 0 }"
      >All</span
    >
    <span
      @click="changeAlbum(album)"
      v-for="album in albums"
      :key="album.id"
      class="list-group-item list-group-item-action"
      :class="{ active: album.id == selectedAlbum.id }"
      >{{ album.title }}</span
    >
  </div>
</template>

<script>
export default {
  props: {
    pictures: [],
  },
  data() {
    return {
      albums: [],
      photos: [],
      selectedAlbum: {id:0},
    };
  },
  methods: {
    getAlbums() {
      axios.get("/api/admin/albums?api_token=" + api_token).then((response) => {
        this.albums = response.data;
        this.$emit('updated:albums',response.data)
      });
    },
    getPictures() {
      axios
        .get("/api/admin/pictures?api_token=" + api_token)
        .then((response) => {
          this.photos = response.data;
          this.$emit("changed:pictures", this.photos);
        });
    },
    changeAlbum(album) {
      this.selectedAlbum = album;
      if(this.selectedAlbum.id==0){
        this.$emit("changed:pictures", this.photos);
      }else{
        this.$emit("changed:pictures", album.pictures);
      }
    },
  },
  created() {
    this.getAlbums();
    this.getPictures();
  },
};
</script>

<style lang="scss">
.list-group-item{
  cursor: pointer;
}
</style>