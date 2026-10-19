<template>
  <div>
    <h3 class="page-title">Số liệu ấn tượng</h3>
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-description">Quản lý các con số hiển thị ở trang chủ (nên giữ khoảng 4 mục)</p>
            <div class="row" v-for="(item, key) in objData" :key="key">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Icon</label>
                  <image-upload type="avatar" v-model="item.image" :title="'so-lieu-'"></image-upload>
                </div>
              </div>
              <div class="col-md-9">
                <div class="form-group">
                  <label>Tiêu đề</label>
                  <label style="float: right;cursor: pointer" title="Xóa mục" v-if="key != 0" @click="removeItem(key)">
                    <vs-icon icon="clear"></vs-icon>
                  </label>
                  <vs-input type="text" v-model="item.name" size="default" placeholder="Ví dụ: Happy Travelers" class="w-100" />
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Số liệu</label>
                      <vs-input type="text" v-model="item.value" size="default" placeholder="Ví dụ: 25000" class="w-100" />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Hậu tố</label>
                      <vs-input type="text" v-model="item.suffix" size="default" placeholder="Ví dụ: + hoặc %" class="w-100" />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Thứ tự</label>
                      <vs-input type="number" v-model="item.sort_order" size="default" placeholder="1" class="w-100" />
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Trạng thái</label>
                  <vs-select v-model="item.status">
                    <vs-select-item value="1" text="Hiện" />
                    <vs-select-item value="0" text="Ẩn" />
                  </vs-select>
                </div>
              </div>
              <hr style="border: 0.5px solid #04040426; width: 100%;">
            </div>
            <vs-button color="primary" @click="saveItems">Lưu</vs-button>
            <vs-button color="success" @click="addItem">Thêm mục</vs-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
  name: "impressiveNumber",
  data() {
    return {
      objData: [
        {
          name: "",
          value: "",
          suffix: "+",
          image: "",
          status: 1,
          sort_order: 1
        }
      ]
    };
  },
  methods: {
    ...mapActions(["saveImpressiveNumber", "listImpressiveNumber", "loadings"]),
    emptyItem(order) {
      return {
        name: "",
        value: "",
        suffix: "+",
        image: "",
        status: 1,
        sort_order: order
      };
    },
    saveItems() {
      this.loadings(true);
      this.saveImpressiveNumber({ data: this.objData })
        .then(() => {
          this.loadings(false);
          this.$success("Lưu số liệu thành công");
          this.loadItems();
        })
        .catch(() => {
          this.loadings(false);
          this.$error("Lưu số liệu thất bại");
        });
    },
    addItem() {
      this.objData.push(this.emptyItem(this.objData.length + 1));
    },
    removeItem(i) {
      this.objData.splice(i, 1);
    },
    loadItems() {
      this.loadings(true);
      this.listImpressiveNumber()
        .then(response => {
          this.loadings(false);
          if (response.data && response.data.length > 0) {
            this.objData = response.data.map((item, index) => ({
              name: item.name || "",
              value: item.value || "",
              suffix: item.suffix || "",
              image: item.image || "",
              status: item.status === "" || item.status === null || item.status === undefined ? 1 : item.status,
              sort_order: item.sort_order || index + 1
            }));
          } else {
            this.objData = [this.emptyItem(1)];
          }
        })
        .catch(() => {
          this.loadings(false);
        });
    }
  },
  mounted() {
    this.loadItems();
  }
};
</script>
