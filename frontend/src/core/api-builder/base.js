export default {
    request(path,options){
        var method = typeof options == 'object' && options.method ? options.method : 'get';
        var params = typeof options == 'object' && options.param ? options.param : "";

        const api_resource = this.api_resource;
        var queryString = params?this.getQueryString(params):"";

        path = path ? "/"+path : "";
        var url = `${ api_resource }${path}${queryString}`;
        return Vue.http[method](url,{timeout:0});
    },
    all(params=""){
        const api_resource = this.api_resource;
        var url = `${ api_resource }/all${this.getQueryString(params)}`;
        return Vue.http.get(url,{timeout:0});
    },
    index(params=""){
        const api_resource = this.api_resource;
        var queryString = params?this.getQueryString(params):"";

        var url = `${ api_resource }${queryString}`;
        return Vue.http.get(url,{timeout:0});
    },
    store(payload,params){
        const api_resource = this.api_resource;

        if(this.form_methods.includes('store')){
            payload = this.getFormData(payload,'POST')
        }

        var url = `${ api_resource }${this.getQueryString(params)}`;
        return Vue.http.post(`${url}`,payload);
    },
    destroy(id,params){
        const api_resource = this.api_resource;
        var url = `${ api_resource }/${id}${this.getQueryString(params)}`;
        return Vue.http.delete(`${url}`);
    },
    update(id,payload,params){
        const api_resource = this.api_resource;
        var url = `${ api_resource }/${id}${this.getQueryString(params)}`;

        if(this.form_methods.includes('update')){
            payload = this.getFormData(payload,'PUT')
            return Vue.http.post(`${url}`,payload);
        }

        return Vue.http.put(`${url}`,payload);
    },
    find(params){
        var id = params.id;
        delete params.id;
        const api_resource = this.api_resource;
        var url = `${ api_resource }/${id}${this.getQueryString(params)}`;
        return Vue.http.get(`${url}`);
    },
    show(id,params){
        const api_resource = this.api_resource;
        var url = `${ api_resource }/${id}${this.getQueryString(params)}`;
        return Vue.http.get(`${url}`);
    },
    download(params,endpoint="/download",payload={},response={responseType:"blob"}){
        const web_resource = this.web_resource;
        var url = `${ web_resource }${endpoint}${this.getQueryString(params)}`;
        return Vue.http.post(url,payload,response);
    },
    upload(payload,params){
        const api_resource = this.api_resource;
        var fdata = this.getFormData(payload);
        var url = `${ api_resource }/upload${this.getQueryString(params)}`;
        return Vue.http.post(`${url}`,fdata);
    },
    getFormData(payload,action="POST",simple=true){
        let fdata = new FormData();
        var file_keys = this.file_keys;
        var n_payload;

        if(simple){
            //do simple deep clone with stringify
            //stringify will convert date/time to string
            n_payload = JSON.parse(JSON.stringify(payload));
            n_payload = Object.assign({},payload);
        }else{
            //do deep clone of payload
            //unlike stringify which only clone date/time by string
        }

        for(let i in file_keys){
            var key = file_keys[i];
            if(payload[key]){
                var file = payload[key];
                delete(n_payload[key]);

                if(typeof file.name == 'string'){
                    fdata.append(key,file);
                }
            }
        }


        for ( var key in n_payload) {
            var load = n_payload[key];

            if(!key || typeof load == 'undefined' || (typeof load == 'string' && load.length==0)){
                continue;
            }

            load = typeof load == 'boolean'?Number(load):load;

            if(Array.isArray(load)){
                fdata.append(key,JSON.stringify(load));
            }else if(typeof load == 'object') {
                fdata.append(key, JSON.stringify(load))
            }else if(typeof load == 'number' || load){
                fdata.append(key,load)
            }
        }

        fdata.append('_method',action);
        return fdata;
    },
    querify(params,level=1,delimeter="="){
        if(!params){
            return [];
        }
        var queries = [];
        for(let key in params){
            var param = params[key];
            if(key == 'search' && typeof param == 'object'){
                queries.push(this.getSearchString(param));
                continue;
            }

            if(Array.isArray(param)){
                queries.push(`${key}=${param.join(',')}`)
            }
            else if(typeof param == 'object'){
                queries = queries.concat(this.querify(param,level+1));
            }
            else if(param || typeof param == 'number'){
                queries.push(key+delimeter+param)
            }
        }
        return queries;
    },
    getSearchString(params){
        if(typeof params != 'object'){
            return params;
        }
        var searches = [];
        for(let key in params){
            var val = params[key];
            searches.push(`${key}:${val}`);
        }
        return "search="+searches.join(';');
    },
    getQueryString(params){
        if(!params){
            return'';
        }

        if(typeof params == 'string'){
            return params.indexOf('?')!=-1?params:`?${params}`
        }
        var queries = []

        if(typeof params == 'object'){
             queries = queries.concat(this.querify(params,0));
        }

        return queries.length>0?`?${queries.join('&')}`:''
    },
    file_keys:['file'],
    form_methods:['upload'],
}
