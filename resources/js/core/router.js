import VueRouter from 'vue-router'
const APP_DOMAIN = process.env.MIX_APP_DOMAIN;
const APP_ENV = process.env.MIX_APP_ENV;
const APP_URL = process.env.MIX_APP_URL;

const router_dependency = {
}

router_dependency.install = (Vue,options) => {

    Vue.use(VueRouter)

    var app = options.app;
    var routes = options.routes;
    var afterEach = options.afterEach;
    var beforeEach = options.beforeEach;
    var attachTo = options.attachTo;
    var scroll = {x:0,y:0}

    var base = ""
    if(APP_ENV == 'production'){
        if(APP_DOMAIN){
            base = APP_URL;
            base = base.replace(APP_DOMAIN,'');
        }else{
            base = APP_URL;
            base = base.replace('https://','');
            base = base.replace('http://','');
            base = base.substring(base.indexOf('/')+1,base.length);
        }
    }

    const scrollBehavior = (to,from,next)=>{
        return scroll;
    }

    var router = new VueRouter({
        routes,
        savedPosition:true,
        base,
        hashbang: false,
        history: true,
        mode: 'history',
        linkActiveClass: 'active',
        scrollBehavior,
    })

    router.beforeEach(
        (to, from, next) => {

            if(to.name == from.name){
                scroll = { x: window.scrollX, y:window.scrollY}
            }else{
                scroll = {x:0,y:0}
            }

            if(typeof beforeEach == 'function'){
                  beforeEach(to,from,next,router);
            }

            Vue.auth.logoutHandler=null;
            //Vue.alert.close('router',to);
            if(to.name != 'sign-in'){

            }

            if(to.name == 'home'){
                if( Vue.auth.isAuthenticated()){
                    next({name:Vue.auth.role()+":home"});
                    return;
                }
            }

            if(to.matched.some(record => record.meta.permission)){
                var permission='';

                if(typeof to.meta.permission != 'undefined'){
                    permission = to.meta.permission;
                }else{
                    for(let children in to.matched){
                        let match = to.matched[children];
                        if(typeof match.meta.permission != 'undefined'){
                            permission = match.meta.permission;
                        }
                    }
                }

                //for multiple allowed permission i.e 'admin|manager|salesman'
                var permissions = (permission.indexOf('|') !== -1) ? permission.split('|') : [permission];

                if(to.name == 'sign-in'){
                    if(Vue.auth.isAuthenticated()){
                        next({name:`${Vue.auth.role}:home`});
                        return;
                    }else{
                        next();
                        return;
                    }
                }

                //if route allows guest permission or any permission
                if(permission == 'guest' || permission == 'any'){next();return;}

                //if route only requires authenticated user
                if(permission == 'authenticated'){
                    if(Vue.auth.isAuthenticated()){
                        next();
                        return;
                    }else{
                        next({path:'/sign-in'});
                        return;
                    }
                }

                //if route only allows specific permissions
                if(Vue.auth.within(permissions)){
                    next();
                }else{
                    next({path:'/home'});
                }
                return;
            }
            next();
            //Vue.alert.close('router',to)
        }
    )

    router.afterEach(() => {
        if(typeof afterEach == 'function'){
            afterEach(router);
        }
    })

    attachTo.router = router;
}

if(typeof window != 'undefined' && window.Vue){
    window.Vue.use(router_dependency);
}

export default router_dependency;
