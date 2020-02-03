import { api_prefix } from '@core/api-builder'
const api_resource = `${api_prefix}/users`;

var auth = {
    check(payload){
        var resource = this.api_resource;
        return Vue.http.get(`${ resource }`);
    },
    refresh(payload){
        var resource = this.api_resource;
        return Vue.http.post(`${resource}/refresh`);
    },
    login(payload){
        var resource = this.api_resource;
        return Vue.http.post(`${ resource }/authenticate`,payload);
    },
    forgot_password(payload){
        var resource = this.api_resource;
        return Vue.http.post(`${ resource }/forgot-password`,payload);
    },
    change_password(payload){
        var resource = this.api_resource;
        return Vue.http.post(`${ resource }/change-password`,payload);
    },
    api_resource,
}

export default auth;
