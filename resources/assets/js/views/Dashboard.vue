<template>
  <div>
    <h5 class="text-uppercase">{{ message }}</h5>
    <div class="row">
      <div class="col-md-3 col-lg-3">
        <albums
          :pictures="photos"
          v-on:changed:pictures="changeAlbum"
          v-on:updated:albums="updateAlbums"
        />
      </div>
      <div class="col-md-9 col-lg-9">
        <button class="btn btn-primary btn-round" @click="addPicture">
          ADD Picture
        </button>
        <div class="row">
          <div
            v-for="(image, index) in photos"
            :key="index"
            class="col-md-6 col-lg-6 d-flex"
          >
            <photo
              :image="image"
              @edit="editPicture"
              @published="published"
            ></photo>
          </div>
        </div>
      </div>
    </div>
    <modal :show.sync="dialog.show" size="lg">
      <card headerClasses="modal-header card-header-primary">
        <template slot="header">
          <h6 class="text-uppercase card-title">{{ dialog.title }}</h6>
          <button
            type="button"
            class="close btn btn-danger btn-sm btn-fab btn-round"
            @click="dialog.show = false"
          >
            <span class="material-icons">close</span>
          </button>
        </template>
        <template>
          <photo-form
            ref="photoForm"
            :albums="albums"
            :photo="photo"
            :edit.sync="edit"
            @created:album="newAlbum"
          />
        </template>
        <template slot="footer">
          <button class="btn btn-danger" @click="dialog.show = false">
            Close
          </button>
          <button class="btn btn-primary" @click="savePhoto">Save</button>
        </template>
      </card>
    </modal>
  </div>
</template>
<script>
import Modal from "../components/Modal.vue";
import Photo from "../components/Photo.vue";
import PhotoForm from "../components/PhotoForm.vue";
import Albums from "../components/Albums.vue";
import Card from "../components/Cards/Card.vue";

export default {
  data() {
    return {
      message: "Photo Gallery",
      photos: [],
      albums: [],
      photo: {
        picture: {
          original: "/vendor/admin/img/placeholder.png",
          url: "/vendor/admin/img/placeholder.png",
          url_alt: "/vendor/admin/img/placeholder.png",
        },
        caption: "",
        title: "",
        alt: "",
        url: "",
      },
      emptyPhoto: {
        picture: {
          original: "/vendor/admin/img/placeholder.png",
          url: "/vendor/admin/img/placeholder.png",
          url_alt: "/vendor/admin/img/placeholder.png",
        },
        caption: "",
        title: "",
        alt: "",
        url: "",
      },
      edit: false,
      dialog: {
        show: false,
        title: "New Picture",
      },
    };
  },
  components: {
    Photo,
    PhotoForm,
    Modal,
    Albums,
    Card,
  },
  methods: {
    addPicture() {
      this.dialog.show = true;
      this.photo = this.emptyPhoto;
      this.edit = false;
      // alert("ADD");
    },
    editPicture(picture) {
      this.edit = true;
      this.photo = picture;
      this.dialog.show = true;
    },
    changeAlbum(pictures) {
      this.photos = pictures;
    },
    async savePhoto() {
      await this.$refs.photoForm
        .save()
        .then((response) => {
          if (response.data.success) {
            if (!this.edit) {
              this.photos.unshift(response.data.picture);
            }
            this.edit = false;
            this.photo = this.emptyPhoto;
            this.dialog.show = false;
          }
        })
        .catch((error) => {
          console.log(error.message);
        });
    },
    updateAlbums(albums) {
      this.albums = albums;
    },
    newAlbum(album) {
      this.albums.unshift(album);
    },
    published(photo) {
      this.photos.forEach((item, index) => {
        if (item.id === photo.id) {
          this.photos[index].published = photo.published;
        }
      });
    },
  },
};
</script>