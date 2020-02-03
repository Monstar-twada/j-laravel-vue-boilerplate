export default function(Vue) {
    Vue.auth={
        store:Vue.vuex.store(),
        authenticated:[
        ],
        profile(){
            return this.store.getters['Auth/profile'];
        },
        role(){
            return this.store.getters['Auth/role'];
        },
        within(roles){
            var user_role = this.store.getters['Auth/role'];
            if(user_role){
                for(let r in roles){
                    var role = roles[r];
                    if(user_role == role){
                        return true;
                    }
                }
            }
            return false;
        },
        check(){
            return this.store.dispatch('Auth/check');
        },
        refresh(){
            return this.store.dispatch('Auth/refresh')
        },
        isAuthenticated(permissions) {
            return this.store.getters['Auth/isAuthenticated'];
        },
        hasToken(permissions) {
            return this.store.getters['Auth/hasToken'];
        },
        logout(next_route=""){
            if(typeof this.logoutHandler == 'function'){
                this.logoutHandler();
            }
            this.store.dispatch('Auth/logout',next_route);
        },
        login(payload){
            this.store.dispatch('Auth/login',payload);
        },
        remove(){
            this.store.commit('Auth/remove');
        },
        logoutHandler:null,
    }

    Object.defineProperties(Vue.prototype,{
        $auth:{

            get:() => {
                return Vue.auth
            }
        }
    })
}
