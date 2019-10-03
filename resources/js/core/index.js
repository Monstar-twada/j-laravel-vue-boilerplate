import router from './router'
import vuex from './vuex'
import resource from './resource'

import store_builder,{base as base_store} from './store-builder'
import api_builder,{base as base_api} from './api-builder'
import dependencies from './dependencies'

const installer = {};
installer.install = (Vue,options) => {
    Vue.use(resource,options.resource)
    Vue.use(vuex,options.vuex)
    Vue.use(router,options.router)
    Vue.use(dependencies);
}

if(typeof window != 'undefined' && window.Vue){
    window.Vue.use(installer);
}

export {
    router,
    resource,
    vuex,
    api_builder,
    store_builder,
    base_store,
    base_api,
    dependencies,
    installer,
}

export default installer
