<template>
  <div>
    <h5 class="text-uppercase">{{ message }}</h5>
    <button class="btn btn-primary btn-round" @click="addPicture">ADD Picture</button>
    <div class="row">
      <div class="col-md-3 col-lg-3">
        <div class="list-group">
          <a
            href=""
            class="list-group-item"
            :key="index"
            v-for="(album, index) in albums"
            >{{ album.title }}</a
          >
        </div>
      </div>
      <div class="col-md-9 col-lg-9">
        <div class="row">
          <div
            v-for="(image, index) in photos"
            :key="index"
            class="col-md-6 col-lg-6 d-flex"
          >
            <photo :image="image"></photo>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Photo from "../components/Photo.vue";
export default {
  data() {
    return {
      message: "Photo Gallery",
      photos: [],
      albums: [],
    };
  },
  components: {
    Photo,
  },
  methods: {
    getAlbums() {
      axios.get("/api/admin/albums?api_token=" + api_token).then((response) => {
        this.albums = response.data;
      });
    },
    getPictures() {
      axios
        .get("/api/admin/pictures?api_token=" + api_token)
        .then((response) => {
          this.photos = response.data;
        });
    },
    addPicture(){
        alert('ADD')
    }
  },
  created() {
    this.getAlbums();
    this.getPictures();
  },
};
</script>