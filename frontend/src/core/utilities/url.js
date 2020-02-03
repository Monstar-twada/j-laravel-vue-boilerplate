export const url = {
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
}

export default function( Vue ){
    Vue._url = url

    Object.defineProperties(Vue.prototype,{
        $_url:{
            get:() => {
                return Vue._url;
            }
        }
    })
}
