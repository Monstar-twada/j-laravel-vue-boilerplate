<template>
  <div class="registration-form">
    <div class="page-title">
      <ui-icon icon="client" use-svg size="medium" />
      <h1 class="title is-3">登録用紙</h1>
    </div>
    <div class="box">
        <div class="columns">
          <ui-textbox
            class="column"
            v-validate="'required|email'"
            is-lowercase
            v-model="profile.email"
            name="Email_ アドレス"
            :error="errors.first('Email_ アドレス')"
            :invalid="errors.has('Email_ アドレス')"
            is-fullwidth
            required
            required-text="必須"
            placeholder="例 ) toyota.morgan@gmail.com"
            label="Email_ アドレス"
          />
        </div>

        <div class="columns">
          <ui-textbox
            v-validate="'required'"
            v-model="profile.last_name"
            name="姓"
            :error="errors.first('姓')"
            :invalid="errors.has('姓')"
            is-fullwidth
            required
            required-text="必須"
            class="column"
            placeholder="例 ) 北風"
            label="姓"
          />
          <ui-textbox
            v-validate="'required'"
            v-model="profile.first_name"
            name="名"
            :error="errors.first('名')"
            :invalid="errors.has('名')"
            is-fullwidth
            required
            required-text="必須"
            class="column"
            placeholder="例 ) 茂幸"
            label="名"
          />
        </div>
        <div class="columns">
          <ui-textbox
            is-fullwidth
            v-model="profile.phone_number"
            class="column"
            placeholder="例 ) 090-1435-1447"
            label="電話番号"
          />
          <ui-textbox
            is-fullwidth
            v-model="profile.department"
            class="column"
            placeholder="例 ) 首都圏営業部長"
            label="部門"
          />
        </div>
        <div class="columns">
          <ui-textbox
            class="column"
            is-fullwidth
            v-model="profile.address"
            placeholder="例 ) 〒100-0005 東京都千代田区丸の内二丁目1番1号明治安田生命ビル18階 "
            label="住所"
          />
        </div>

        <div class="columns">
          <ui-textbox
            name="変更したいサイトログインパスワード"
            key="new_password"
            :error="errors.first('変更したいサイトログインパスワード')"
            :invalid="errors.has('変更したいサイトログインパスワード')"
            label="変更したいサイトログインパスワード"
            v-validate="new_password_validation"
            required
            requiredText="必須"
            v-model="profile.np"
            type="password"
            class="column"
            placeholder="••••••••"
            is-fullwidth
          ></ui-textbox>
          <ui-textbox
            :disabled="!profile.np"
            name="変更したいサイトログインパスワード（確認）"
            key="confirm_password"
            :error="errors.first('変更したいサイトログインパスワード（確認）')"
            :invalid="errors.has('変更したいサイトログインパスワード（確認）')"
            label="変更したいサイトログインパスワード（確認）"
            type="password"
            required
            requiredText="必須"
            v-validate="confirm_password_validation"
            v-model="profile.cp"
            class="column"
            placeholder="••••••••"
            is-fullwidth
          ></ui-textbox>
        </div>
        <div class="management-controls has-text-centered">
          <ui-button class="btn-cancel" @click="cancel" type="primary" color="default">キャンセル</ui-button>
          <ui-button class="btn-add" @click="add" type="primary" color="primary">登録</ui-button>
        </div>
    </div>
  </div>
</template>
<script>
import api from '@api/users'
export default {
  name: "registration-form",
  data() {
    return {
    default_data:'',
      profile: {
        email: "",
        last_name: "",
        first_name: "",
        phone_number: "",
        department: "",
        address: "",
        //note: I use 2-3 letter variable for
        //security purposes
        np: "", //new password
        cp: "", //confirm password
        role_id:2,
      }
    };
  },
  computed: {
    confirm_password_validation() {
      if (!this.profile.np) {
        return "";
      }
      return "required|Confirm_password:変更したいサイトログインパスワード";
    },
    new_password_validation() {
      var validation = "max:100|min:6";
      if (this.context == "add" || this.profile.op) {
        validation = "required|" + validation;
      }
      return validation;
    }
  },
  watch: {},
  created(){
      this.default_data = Object.assign({},this.profile);
  },
  methods: {
    clear(){
        this.profile = Object.assign({},this.default_data);
    },
    async add() {
        await api.store(this.profile);
        this.clear();
    },
    cancel() {
      this.$router.go(-1);
    }
  }
};
</script>

<style lang="scss">
</style>
