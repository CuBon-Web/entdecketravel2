<template>
  <div>
    <h3 class="page-title">Sửa lý do chọn chúng tôi</h3>
    <div class="row">
      <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label>Icon (tab trái)</label>
              <image-upload v-model="objData.image" type="avatar" :title="'why-choose-icon'"></image-upload>
            </div>
            <div class="form-group">
              <label>Ảnh đại diện (panel phải)</label>
              <image-upload v-model="objData.image_detail" type="avatar" :title="'why-choose-avatar'"></image-upload>
            </div>
            <div class="form-group">
              <label>Tiêu đề</label>
              <vs-input
                type="text"
                size="default"
                placeholder="Ví dụ: Trusted service"
                class="w-100"
                v-model="objData.content[0].content"
              />
            </div>
            <div class="form-group">
              <label>Mô tả</label>
              <vs-textarea
                class="w-100"
                placeholder="Mô tả ngắn"
                v-model="objData.description[0].content"
              />
            </div>
            <div class="form-group">
              <label>Nhãn nổi trên ảnh</label>
              <vs-input
                type="text"
                size="default"
                placeholder="Ví dụ: 24/7 Support"
                class="w-100"
                v-model="objData.name"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label>Trạng thái</label>
              <vs-select v-model="objData.status">
                <vs-select-item value="1" text="Hiện" />
                <vs-select-item value="0" text="Ẩn" />
              </vs-select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row fixxed">
      <div class="col-12">
        <div class="saveButton">
          <vs-button color="primary" @click="addBanner">Cập nhật</vs-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
  name: "editWhyChoose",
  data() {
    return {
      errors: [],
      objData: {
        id: this.$route.params.id,
        name: "",
        content: [{ lang_code: "vi", content: "" }],
        description: [{ lang_code: "vi", content: "" }],
        status: 1,
        image: "",
        image_detail: "",
        status_show: "banner_slogan"
      }
    };
  },
  methods: {
    ...mapActions(["addBannerads", "loadings", "detailBannerads"]),
    normalizeLangField(value) {
      if (Array.isArray(value)) return value;
      if (typeof value === "string" && value) {
        try {
          const parsed = JSON.parse(value);
          if (Array.isArray(parsed)) return parsed;
        } catch (e) {}
        return [{ lang_code: "vi", content: value }];
      }
      return [{ lang_code: "vi", content: "" }];
    },
    addBanner() {
      this.errors = [];
      if (!this.objData.content[0] || !this.objData.content[0].content) {
        this.errors.push("Vui lòng nhập tiêu đề");
      }
      if (!this.objData.image) this.errors.push("Vui lòng chọn icon");
      if (!this.objData.image_detail) this.errors.push("Vui lòng chọn ảnh đại diện");
      if (this.errors.length > 0) {
        this.errors.forEach(value => this.$error(value));
        return;
      }
      this.loadings(true);
      this.addBannerads(this.objData)
        .then(() => {
          this.loadings(false);
          this.$router.push({ name: "listBannerslogan" });
          this.$success("Cập nhật thành công");
        })
        .catch(() => {
          this.loadings(false);
          this.$error("Cập nhật thất bại");
        });
    },
    editById() {
      this.loadings(true);
      this.detailBannerads(this.objData)
        .then(response => {
          this.loadings(false);
          if (!response.data) return;
          this.objData = {
            id: response.data.id,
            name: response.data.name || "",
            content: this.normalizeLangField(response.data.content),
            description: this.normalizeLangField(response.data.description),
            status: response.data.status,
            image: response.data.image || "",
            image_detail: response.data.image_detail || "",
            status_show: response.data.status_show || "banner_slogan"
          };
        })
        .catch(() => {
          this.loadings(false);
        });
    }
  },
  mounted() {
    this.editById();
  }
};
</script>
