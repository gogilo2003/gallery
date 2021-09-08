<template>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <img :src="imgSrc" alt="" class="preview" />
        <!--<button class="btn btn-primary">Crop Image</button>-->
      </div>
      <div class="form-group col-md-12 form-file-upload form-file-multiple">
        <input
          ref="input"
          type="file"
          class="inputFileHidden"
          @change="setImage"
        />
        <div class="input-group">
          <input
            type="text"
            class="form-control inputFileVisible"
            placeholder="Single File"
            accept="image/*"
          />
          <span class="input-group-btn">
            <button type="button" class="btn btn-fab btn-round btn-primary">
              <i class="material-icons">attach_file</i>
            </button>
          </span>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-row">
        <div class="form-group col-md-12">
          <label for="photoTitle">Title</label>
          <input
            type="text"
            class="form-control"
            aria-describedby="helpId"
            placeholder="Photo title"
            v-model="item.title"
            id="photoTitle"
          />
        </div>
        <div class="form-group col-md-12">
          <label for="albumSelect">Select Albums</label>
          <vue-select
            id="albumSelect"
            v-model="selectedAlbums"
            label="title"
            taggable
            multiple
            :options="albums"
            :reduce="(album) => album.id"
            :create-option="createOption"
            @option:created="optionCreated"
          />
        </div>
        <div class="form-group col-md-12">
          <label for="photoCaption">Caption</label>
          <textarea
            class="form-control"
            id="photoCaption"
            rows="3"
            v-model="item.caption"
          ></textarea>
        </div>
      </div>
    </div>
    <modal :show.sync="modal.show">
      <card>
        <template slot="header">
          <h5 class="text-upercase card-title">Crop Photo</h5>
        </template>
        <template>
          <vue-cropper
            ref="thumbnail"
            :src="placeholder"
            alt="Source Image"
            :aspect-ratio="1 / 1"
          >
          </vue-cropper>

          <vue-cropper
            ref="image"
            :src="placeholder"
            alt="Source Image"
            :aspect-ratio="4 / 3"
          >
          </vue-cropper>
        </template>
      </card>
    </modal>
  </div>
</template>
<script>
import VueCropper from "vue-cropperjs";
import "cropperjs/dist/cropper.css";
import Modal from "./Modal.vue";
import Card from "./Cards/Card.vue";
import VueSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
  props: {
    photo: {
      type: Object,
      required: true,
    },
    edit: {
      Type: Boolean,
      default: false,
    },
    albums: {
      type: Array,
      default: [],
    },
  },
  data() {
    return {
      item: {},
      filename: "",
      placeholder: "/vendor/admin/img/placeholder.png",
      imgSrc: "/vendor/admin/img/placeholder.png",
      selectedAlbums: null,
      modal: {
        show: false,
      },
    };
  },
  methods: {
    save() {
      let thumbnail_cropdetails = JSON.stringify(
        this.$refs.thumbnail.getData()
      );
      let image_cropdetails = JSON.stringify(this.$refs.image.getData());
      let picture = this.$refs.input.files[0];
      let formData = new FormData();
      if (picture) {
        formData.append("picture", picture);
      }
      if (this.selectedAlbums) {
        formData.append("albums", this.selectedAlbums);
      }

      formData.append("title", this.photo.title);
      formData.append("caption", this.photo.caption);

      formData.append("thumbnail_cropdetails", thumbnail_cropdetails);
      formData.append("image_cropdetails", image_cropdetails);
      formData.append("api_token", api_token);

      if (this.edit) {
        formData.append("id", this.photo.id);
        return axios.post("/api/admin/pictures/update", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
      } else {
        return axios.post("/api/admin/pictures", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
      }
    },
    changePicture(event) {
      let file = event.target.files[0];
      this.filename = file.name;
    },
    setImage(e) {
      const file = e.target.files[0];
      if (file.type.indexOf("image/") === -1) {
        alert("Please select an image file");
        return;
      }
      if (typeof FileReader === "function") {
        const reader = new FileReader();
        reader.onload = (event) => {
          // this.imgSrc = event.target.result;
          this.imgSrc = URL.createObjectURL(e.target.files[0]);
          // rebuild cropperjs with the updated source
          this.$refs.image.replace(event.target.result);
          this.$refs.thumbnail.replace(event.target.result);
        };
        reader.readAsDataURL(file);
      } else {
        alert("Sorry, FileReader API not supported");
      }
    },
    showFileChooser() {
      this.$refs.input.click();
    },
    async optionCreated(option) {
      let album = {};
      await axios
        .post("/api/admin/albums", { api_token, title: option.title })
        .then((response) => {
          album = response.data.album;
        });
      this.$emit("created:album", album);
    },
    createOption(album) {
      return { id: null, title: album, description: "" };
      // console.log(album)
    },
  },
  watch: {
    photo(val) {
      this.item = val;
      if (this.item.albums) {
        this.selectedAlbums = this.item.albums.map((album) => album.id);
      }
      if (val.picture) {
        this.imgSrc = val.picture.url;
      }
    },
  },
  components: {
    VueCropper,
    Modal,
    Card,
    VueSelect,
  },
};
</script>

<style scoped>
/* Ensure the size of the image fit the container perfectly */
img {
  display: block;

  /* This rule is very important, please don't ignore this */
  max-width: 100%;
}
</style>