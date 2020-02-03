import App from './App'

window.Vue = require('vue')
window.Vue.config.productionTip = false
window.installer = (instance,modules) => {
    for(let name in modules){
        if(name !== 'install' && modules[name]){
            instance.use(modules[name])
        }
    }
}


import ui from 'aj-vue-ui/dist/j-ui.common.js'
Vue.use(ui)
import moment from 'moment'
moment.locale('ja')



var options = {
    el: '#app',
    template: '<App/>',
    components: { App }
}

import {modules,persistLocal,persistSession} from './store'
import routes from './views'
var session = {
    time : 900000,
    timer:'',
    renew(before,after){
        //if(Vue.auth.isAuthenticated()){
        //    clearInterval(this.timer);
        //    this.timer = setInterval(()=>{this.action(before,after)},this.time);
        //}
    },
    action:(before,after)=>{
        if(typeof before == 'function'){
            before();
        }
        //Vue.auth.refresh();
        if(typeof after == 'function'){
            after();
        }
    }
};
var progress = null;
const config = {
    vuex:{
        modules,
        persistLocal,
        persistSession,
        attachTo:options,
    },
    router:{
        routes,
        beforeEach:(to,from,next,router)=>{
            Vue.prototype.$notification.clear('route-change');
            if(progress){
                progress = progress.close();
                progress = null
            }
            if(to.name != from.name){
                progress = router.app.$progress.show();
            }
        },
        afterEach: (router)=>{
            if(progress){
                progress = progress.close();
                progress = null
            }
            session.renew();
        },
        attachTo: options,
    },
    resource:{
        next: (response)=>{
            session.renew();
        }
    },
    session,
}

import {installer} from './core'
Vue.use(installer,config);
import dependencies from './dependencies'
Vue.use(dependencies);

var app = new Vue(options);
options.router.onReady(()=>{
    app.$mount(options.el);
})
