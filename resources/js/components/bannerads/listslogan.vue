<template>
  <div>
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Tại sao chọn chúng tôi</h4>
            <p class="card-description">Quản lý các lý do hiển thị ở trang chủ (tối đa nên 3–4 mục)</p>
            <router-link class="nav-link" :to="{ name: 'addBannerslogan' }">
              <vs-button type="gradient" style="float:right;">Thêm mới</vs-button>
            </router-link>
            <vs-input icon="search" placeholder="Search" v-model="keyword" @keyup="searchBlog" />
            <vs-table stripe :data="list" max-items="10" pagination>
              <template slot="thead">
                <vs-th>Icon</vs-th>
                <vs-th>Ảnh đại diện</vs-th>
                <vs-th>Tiêu đề</vs-th>
                <vs-th>Trạng thái</vs-th>
                <vs-th>Hành động</vs-th>
              </template>
              <template slot-scope="{ data }">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td>
                    <vs-avatar size="70px" :src="tr.image" />
                  </vs-td>
                  <vs-td>
                    <vs-avatar v-if="tr.image_detail" size="70px" :src="tr.image_detail" />
                    <span v-else class="text-muted">Chưa có</span>
                  </vs-td>
                  <vs-td>{{ titleOf(tr) }}</vs-td>
                  <vs-td>{{ Number(tr.status) === 1 ? 'Hiện' : 'Ẩn' }}</vs-td>
                  <vs-td>
                    <router-link :to="{ name: 'editBannerslogan', params: { id: tr.id } }">
                      <vs-button vs-type="gradient" size="lagre" color="success" icon="edit"></vs-button>
                    </router-link>
                    <vs-button vs-type="gradient" size="lagre" color="red" icon="delete_forever" @click="confirmDestroy(tr.id)"></vs-button>
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
  data() {
    return {
      list: [],
      keyword: "",
      id_item: "",
      timer: null
    };
  },
  methods: {
    ...mapActions(["listBannerslogan", "loadings", "deleteBannerads"]),
    titleOf(tr) {
      try {
        const parsed = typeof tr.content === "string" ? JSON.parse(tr.content) : tr.content;
        return (parsed && parsed[0] && parsed[0].content) || "";
      } catch (e) {
        return tr.content || "";
      }
    },
    listServices() {
      this.loadings(true);
      this.listBannerslogan({ keyword: this.keyword })
        .then(response => {
          this.loadings(false);
          this.list = response.data;
        })
        .catch(() => {
          this.loadings(false);
          this.list = [];
        });
    },
    confirmDestroy(id) {
      this.id_item = id;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Bạn có chắc chắn`,
        text: "Xóa mục này",
        accept: this.destroy
      });
    },
    searchBlog() {
      if (this.timer) {
        clearTimeout(this.timer);
        this.timer = null;
      }
      this.timer = setTimeout(() => {
        this.listServices();
      }, 800);
    },
    destroy() {
      this.loadings(true);
      this.deleteBannerads({ id: this.id_item }).then(() => {
        this.listServices();
        this.$success("Xóa thành công");
      });
    }
  },
  mounted() {
    this.listServices();
  }
};
</script>
