import Vue from 'vue'
import api from '@api/auth'


var profile = {
    id:'',
    first_name: '',
    last_name: '',
    email: ''
}

const roles = [
]


const state = {
    authenticated: false,
    profile,
    token: null,
    role: null
}

const getters = {
    isAuthenticated(state){return state.authenticated},
    hasToken(state){return Boolean(state.token)},
    role(state){return state.role},
    profile(state){return state.profile},
}

const mutations = {
    refresh(state,token){
        if(token) {
            state.token = token
            Vue.http.headers.common['Authorization'] = 'Bearer ' + token
        }
    },
    clear(state){
        state.token = null;
    },
    update(state,payload){
        state.profile = payload;
    },
    set(state,payload){
        state.authenticated = true;
        state.profile = payload.user
        state.role = payload.user.role
        if(payload.token) {
            state.token = payload.token
            Vue.http.headers.common['Authorization'] = 'Bearer ' + payload.token
        }
    },
    remove(state) {
        state.authenticated = false
        state.profile = profile
        state.role = null
        state.token = null
    }
}

const actions = {
    logout(context,payload){
        context.commit('remove');
        if(payload){
            Vue.bus.emit('logout',{name:payload})
        }
    },
    check(context, payload) {
        return new Promise((resolve,reject)=>{
            if(context.state.authenticated) {
                api.check(success=>{
                    if(context.state.authenticated) {
                        context.commit('set', payload)
                        resolve();
                    }
                },error=>{
                    //todo: change code when has more user types
                    if(context.state.authenticated != true){
                        context.commit('remove')
                        var name = payload?payload:'sign-in'
                        Vue.bus.emit('logout',{name})
                        reject();
                    }
                })
            }
            resolve();
        })
    },
    refresh(context, payload) {
        return new Promise((resolve,reject)=>{
            api.refresh().then(success=>{
                var store_payload = success.data['token'];
                context.commit('refresh', store_payload)
                resolve();
            },error=>{
                //todo: change code when has more user types
                if(context.state.authenticated != true){
                    context.commit('remove')
                    var name = payload?payload:'sign-in'
                    Vue.bus.emit('logout',{name})
                }
                reject();
            })
        })
    },
    login(context, payload) {
        var next = payload.to;
        return new Promise((resolve,reject)=>{
            api.login(payload).then(success=>{
                payload = success.data;
                context.commit('set',payload);
                if(!next){
                    var role = context.getters['role'];
                    console.log(role);
                    if(role){
                        Vue.bus.emit('login',{name:`${role}:login`});
                    }
                    Vue.bus.emit('login',{name:`login`});
                }else{
                    Vue.bus.emit('login',{name:next.name});
                }
                resolve();
            },error=>{
                context.commit('remove');
                reject(error);
            });
        })
    },
}

export default {
    namespaced:true,
    state,
    getters,
    actions,
    mutations
}
