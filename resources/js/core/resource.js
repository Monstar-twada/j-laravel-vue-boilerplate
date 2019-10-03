import VueResource from 'vue-resource'
const APP_URL = process.env.MIX_APP_URL;
const APP_VERSION = process.env.MIX_APP_VERSION;

const resource_dependency = {
}

resource_dependency.install = (Vue,options) => {

    Vue.use(VueResource)
    const storage_key = APP_VERSION;

    Vue.http.headers.common['X-CSRF-TOKEN'] = document.getElementsByName('csrf-token')[0].getAttribute('content')
    Vue.http.headers.common['Authorization'] = (!localStorage[storage_key]) ? '' : 'Bearer ' + JSON.parse(localStorage[storage_key]).Auth.token
    //Vue.http.headers.common['Authorization'] = (!sessionStorage[storage_key]) ? '' : 'Bearer ' + JSON.parse(sessionStorage[storage_key]).Auth.token
    Vue.http.options.root = APP_URL

    Vue.http.interceptors.push((request, next) => {
            next((response) => {
                if (response.status == 401) {
                    Vue.auth.logout();
                }else {
                    var data = response.data;
                    if(data.message && data.display && !data.await){
                        var display = data.display.split('|');
                        var type = display[1];

                        switch(type){
                            case 'successful':
                            case 'success':
                                type = "info"
                                break;
                            case 'failed':
                            case 'fail':
                            case 'error':
                                type = "error"
                                break;
                        }

                        switch(display[0]){
                            case 'notification': Vue.prototype.$notification.show({message: data.message,type });break;
                            case 'toast': Vue.prototype.$toast.show({message: data.message,type });break;
                            case 'alert': Vue.prototype.$notification.show({message: data.message,type });break;
                        }
                    }
                    options.next(response);
                }
            })
    })
};

if(typeof window != 'undefined' && window.Vue){
    window.Vue.use(resource_dependency);
}

export default resource_dependency;
