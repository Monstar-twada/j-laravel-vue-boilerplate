<template>
    <div class="ui-login-form" >
        <div class="page-title">
            <ui-icon icon="client" use-svg size="medium"/>
            <h1 class="title is-3">
                    ログイン画面
            </h1>
        </div>
        <div class="box">
            <form class="login-form" autocomplete="on" @submit.prevent="login">
                <ui-textbox is-fullwidth name="login_id" id="login_id" autocomplete="on" v-model="username"
                                                                                         label="メールアドレス"
                                                                                         :maxLength="50"
                                                                                         :enforceMaxLength="true"
                                                                                         placeholder="例 ) test@cbre.co.jp"
                                                                                         v-validate="'required|max:50'"
                                                                                         :disabled="Boolean(block)"
                                                                                         :invalid="errors.has('login_id')"
                                                                                         error=""
                                                                                         hasMessage
                                                                                         >
                </ui-textbox>
                    <ui-textbox is-fullwidth id="password" name="password" autocomplete="on" v-model="password"
                                                                                             label="パスワード"
                                                                                             placeholder="••••••••"
                                                                                             type="password"
                                                                                             v-validate="'required|max:50'"
                                                                                             :disabled="Boolean(block)"
                                                                                             :invalid="errors.has('password')"
                                                                                             error=""
                                                                                             hasMessage
                                                                                             >
                    </ui-textbox>
                        <div class="login-buttons">
                            <ui-button :disabled="Boolean(block)">ログイン</ui-button>
                        </div>
                    <router-link class="forgot-password-link" :to="{name:'forgot-password'}">
                        パスワードをお忘れの方
                    </router-link>
            </form>
        </div>
    </div>
</template>


<script>
export default{
    data(){
        return {
            block:'',
            username:'',
            password:'',
        }
    },
    methods:{
        async login(){
            var is_valid = await this.$validator.validateAll();
            var password = this.password;
            var username = this.username;
            var message=""
            if(is_valid){
                this.block = this.$block.show();
                try{
                    var res = await this.$store.dispatch('Auth/login',{password,email:username,to:{name: 'tenant:home' }})
                }catch(error){
                    switch(error.status){
                        case 401:
                            message = 'メールアドレスもしくはパスワードが正しくありません。';
                            break;
                        case 503:
                        case 500:message = 'サーバーは現在停止中です';break;
                    }
                    this.$bus.emit('login-failed');
                    this.$notification.show({message,type:'error'});
                }finally{
                    this.block = this.block.close();
                }
            }else{
                if(!username && !password){
                    message="全て必須項目です"
                }else if(!username){
                    message="ユーザIDは必須入力です。"
                } else if(!password){
                    message="パスワードは必須入力です。"
                }
                this.$notification.show({ message ,type:'error'});
            }
        },
    },
    beforeRouteEnter(to,from,next){
        next(vm => {
            if(vm.$store.getters['Auth/isAuthenticated']){
                vm.$router.push({name:'home'});
            }
        })
    },
    beforeCreate(){
        var block = document.body.querySelector('#ui-block');
        if(block){
            setTimeout(()=>{
                document.body.removeChild(block);
            },500)
        }
    }
}
</script>
<style lang="scss">
.ui-login-form{
max-width:33em !important;
.page-title{
    margin-bottom:1em;
}
.login-form{
    padding: 30px;
    //background-color:#fbfbfb;
    display:flex;
    justify-content:space-around;
    flex-direction:column;
    .login-buttons{
        display:flex;
        justify-content:center;
        align-content:center;
    }
    a{
        text-align:center;
        padding-top:20px;
    }
}
}
</style>
