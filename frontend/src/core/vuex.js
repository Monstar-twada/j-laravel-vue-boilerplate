import Vuex from 'vuex'
import * as Cookies from 'js-cookie'
import createPersistedState from 'vuex-persistedstate'
//const debug = process.env.NODE_ENV !== 'production'
const debug = false
const APP_VERSION = process.env.MIX_APP_VERSION;

const vuex_dependency = {

}

vuex_dependency.install = (Vue,options) => {

    Vue.use(Vuex)
    var modules = options.modules;
    var persistLocal = options.persistLocal;
    var persistSession = options.persistSession;
    var attachTo = options.attachTo;

    if (!('localStorage' in window)) {
        persistLocal.storage = {
            getItem: key => Cookies.get(key),
            setItem: (key, value) => Cookies.set(key, value, { expires: 1, secure: true }),
            removeItem: key => Cookies.remove(key)
        }
    }

    persistSession.storage = window.sessionStorage

    const storage_key = APP_VERSION;

    persistLocal.key = storage_key
    persistSession.key = storage_key

    var store = new Vuex.Store({
        modules,
        strict: debug,
        plugins: [
            createPersistedState(persistLocal),
            createPersistedState(persistSession),
        ]
    })

    attachTo.store = store;

    Vue.vuex={
        store(){
            return store;
        }
    },

    Object.defineProperties(Vue.prototype,{
        $vuex:{
            get:() => {
                return Vue.vuex;
            }
        }
    })
}

if(typeof window != 'undefined' && window.Vue){
    window.Vue.use(vuex_dependency);
}

export default vuex_dependency;
