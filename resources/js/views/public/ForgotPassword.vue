<template>
<div class="forgot-password-form">
    <div class="page-title content-left">
        <ui-icon :useSvg="true" icon="login" size="large" :color="$_colors.accent"/>
        <h1 class="title is-3">パスワードをお忘れの方</h1>
    </div>
    <div class="box">
        <form class="forgot-password-form"  autocomplete="on" @submit.prevent="send">
            <p class="note">
                ご登録のメールアドレスにパスワード再設定の手順をお送りします。
            </p>
            <ui-textbox name="email" id="email" autocomplete v-model="email" :disabled="page_loading"
                                                             is-fullwidth
                                                             :maxLength="50"
                                                             :enforceMaxLength="true"
                                                             v-validate="'required|email'"
                                                             :invalid="errors.has('email')"
                                                             label="メールアドレス  " placeholder="例 ) test@gmail.com"></ui-textbox>
             <div class="content-centered">
                 <ui-button :disabled="page_loading">メールを送信する </ui-button>
             </div>
        </form>
    </div>
</div>
</template>


<script>
import api from '@api/auth';
export default {
    name:'forgot-password',
    data(){
        return {
            email:'',
            page_loading:false,
        }
    },
    methods:{
        async send(){
            var is_valid = await this.$validator.validateAll();
            var email = this.email;
            if(is_valid){
                this.page_loading = true;
                var block = this.$block.show();
                try{
                    var res = await api.forgot_password({email});
                    setTimeout(()=>{
                        this.$router.push({name:'sign-in'})
                    },3000)
                }catch(error){
                    this.page_loading = false;
                }
                block.close();
                block = null;
            }else {
                this.$notification.show({ message:'正しいメールアドレスを入力してください',type:'error' })
            }
        },
    },
}
</script>

<style lang="scss">
.forgot-password-form{
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
