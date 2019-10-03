export default {
    find(context,params){
        var api = context.state.api;
        return new Promise((resolve,reject)=>{
            api.find(params).then(response=>{
                //var item = response.data.data;
                //context.commit('add',item);
                resolve(response.data);
            },response=>{
                reject(response.data);
            })
        })
    },
    all(context,params){
        var api = context.state.api;
        return new Promise((resolve,reject)=>{
            api.all(params)
                .then(response=>{
                    var items = response.data.data;
                    context.commit('set',items);
                    resolve(response.data);
                },response=>{
                    reject(response);
                });
        });
    },
    init(context,params){
        var join = !params?false:params.join?params.join:false;
        if(params && params.join){delete params.join;}
        var api = context.state.api;
        return new Promise((resolve,reject)=>{
            api.index(params)
                .then(response=>{
                    var items = response.data.data;
                    context.commit(join?'join':'set',items);
                    resolve(response.data);
                },response=>{
                    reject(response);
                });
        });
    },
    get(context,id){
        id = Number(id);
        var api = context.state.api;
        return new Promise((resolve,reject)=>{
            api.show(id).then(response=>{
                var item = response.data.data;
                context.commit('add',item);
                resolve(response.data);
            },response=>{
                reject(response.data);
            })
        })
    },
    remove(context,datum){
        var api = context.state.api;
        var rm_id = datum.id?datum.id:datum;
        var params = datum.params?datum.params:null;
        if(params){
            delete rm_id.params;
        }
        return new Promise((resolve,reject)=>{
            api.destroy(rm_id,params).then(response=>{
                context.commit('remove',datum);
                resolve(response.data);
            },response=>{
                reject(response.data);
            })
        })
    },
    update(context,datum){
        var api = context.state.api;
        var payload = datum.data?datum.data:datum;
        var params = datum.params?datum.params:null;
        if(params){
            delete payload.params;
        }
        return new Promise((resolve,reject)=>{
            api.update(payload.id,payload,params).then(response=>{
                var store_payload = response.data.data;
                context.commit('update',store_payload);
                resolve(response.data);
            },response=>{
                reject(response.data);
            })
        })
    },
    add(context,datum){
        var api = context.state.api;
        var payload = datum.data?datum.data:datum;
        return new Promise((resolve,reject)=>{
            api.store(payload).then(response=>{
                var store_payload = response.data.data;
                context.commit('add',store_payload);
                resolve(response.data);
            },response=>{
                reject(response.data);
            })
        })
    },
    upload(context,datum){
        var api = context.state.api;
        var payload = datum.data?datum.data:datum;
        var params = datum.params
        if(payload.params){
            delete payload.params;
        }
        return new Promise((resolve,reject)=>{
            api.upload(payload,params).then(response=>{
                var store_payload = response.data.data;
                context.commit('add',store_payload);
                resolve(response.data);
            },response=>{
                reject(response.data);
            })
        })
    },
    clear(context){context.commit('clear');},
    api(context){
        return context.state.api;
    }
}
