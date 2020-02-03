<template>
    <div class="change-password-form">
        <div class="page-title content-left">
            <ui-icon :useSvg="true" icon="login" size="large" :color="$_colors.accent"/>
            <h1 class="title is-3"> 新しいパスワードの設定 </h1>
        </div>
        <div class="box">
            <form class="change-password-form"  autocomplete="on" @submit.prevent="send">
                <ui-textbox is-fullwidth
                    :disabled="page_loading"
                    name="変更したいサイトログインパスワード"
                    key="new_password"
                    :error="errors.first('変更したいサイトログインパスワード')"
                    :invalid="errors.has('変更したいサイトログインパスワード')"
                    label="変更したいサイトログインパスワード"
                    v-validate="'max:100|min:6'"
                    required
                    required requiredText="必須"
                             v-model="np"
                             type="password"
                             class="column"
                             placeholder="••••••••"
                             ></ui-textbox>
                <ui-textbox is-fullwidth
                        :disabled="!np || page_loading"
                        name="変更したいサイトログインパスワード（確認）"
                        key="confirm_password"
                        :error="errors.first('変更したいサイトログインパスワード（確認）')"
                        :invalid="errors.has('変更したいサイトログインパスワード（確認）')"
                        label="変更したいサイトログインパスワード(確認)"
                        type="password"
                        required
                        required requiredText="必須"
                                 v-validate="confirm_password_validation"
                                 v-model="cp"
                                 class="column"
                                 placeholder="••••••••"
                                 ></ui-textbox>
            <div class="content-centered">
                <ui-button :disabled="page_loading">パスワードを設定する</ui-button>
            </div>
            </form>
        </div>
    </div>
</template>

<script>
import api from '@api/auth';
export default {
    name:'change-password',
    data(){
        return {
            page_loading:false,
            np:'',
            cp:'',
        }
    },
    props:{
        token:{
            type:String,
            required:true,
        }
    },
    computed:{
        confirm_password_validation(){
            if(!this.np){
                return '';
            }
            return 'required|Confirm_password:変更したいサイトログインパスワード';
        },
    },
    methods:{
        async send(){
            var is_valid = await this.$validator.validateAll();
            var message=""
            if(is_valid){
                this.page_loading = true;
                var block = this.$block.show();
                try{
                    var res = await api.change_password({
                        token:this.token,password:this.np,password_confirmation:this.cp
                    })
                    setTimeout(()=>{
                        this.$router.push({name:'sign-in'})
                    },3000)
                }catch(error){
                    this.page_loading = false;
                    switch(error.status){
                        case 401:
                            message = 'メールアドレスもしくはパスワードが正しくありません。';
                            break;
                        case 503:
                        case 500:message = 'Server is currently down please try again later';break;
                    }
                }
                block.close();
                block = null;
            }
        },
    },
}
</script>

<style lang="scss">
.change-password-form{
    max-width:35em !important;
    .page-title{
        margin-bottom:1em;
    }
    .note{
        padding-bottom:20px;
    }
    display:flex;
    justify-content:space-around;
    flex-direction:column;
}

</style>
