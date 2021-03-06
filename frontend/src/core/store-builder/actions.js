/*
* Set the payload with `total` if search is same as previous search
*/
const set_payload = function({context,params}){
    const search = context.state.search;
    if(params.search === context.state.search){
        params.total = context.state.meta.total;
    }
    if(!params.total){
        delete params.total;
    }
    if(!params.per_page){
        delete params.per_page;
    }
    context.state.search = params.search;
}


/*
* Set the meta after executing a search
*/
const set_meta = function({context,response}){
    context.state.meta = response.data.meta;
}
export default function({api}){
    return {
        find(context, params) {
            return new Promise((resolve, reject) => {
                api.find(params).then(
                    response => {
                        //var item = response.data.data;
                        //context.commit('add',item);
                        resolve(response.data);
                    },
                    response => {
                        reject(response.data);
                    }
                );
            });
        },
        all(context, params) {
            set_payload({context,params});
            return new Promise((resolve, reject) => {
                api.all(params).then(
                    response => {
                        set_meta({ context,response });
                        var items = response.data.data;
                        context.commit("set", items);
                        resolve(response.data);
                    },
                    response => {
                        reject(response);
                    }
                );
            });
        },
        init(context, params) {
            var join = !params ? false : params.join ? params.join : false;
            if (params && params.join) {
                delete params.join;
            }
            set_payload({context,params});
            return new Promise((resolve, reject) => {
                api.index(params).then(
                    response => {
                        set_meta({ context,response });
                        var items = response.data.data;
                        context.commit(join ? "join" : "set", items);
                        resolve(response.data);
                    },
                    response => {
                        reject(response);
                    }
                );
            });
        },
        get(context, id) {
            id = Number(id);
            return new Promise((resolve, reject) => {
                api.show(id).then(
                    response => {
                        var item = response.data.data;
                        context.commit("add", item);
                        resolve(response.data);
                    },
                    response => {
                        reject(response.data);
                    }
                );
            });
        },
        remove(context, datum) {
            var rm_id = datum.id ? datum.id : datum;
            var params = datum.params ? datum.params : null;
            if (params) {
                delete rm_id.params;
            }
            return new Promise((resolve, reject) => {
                api.destroy(rm_id, params).then(
                    response => {
                        context.commit("remove", datum);
                        resolve(response.data);
                    },
                    response => {
                        reject(response.data);
                    }
                );
            });
        },
        update(context, datum) {
            var payload = datum.data ? datum.data : datum;
            var params = datum.params ? datum.params : null;
            if (params) {
                delete payload.params;
            }
            return new Promise((resolve, reject) => {
                api.update(payload.id, payload, params).then(
                    response => {
                        var store_payload = response.data.data;
                        context.commit("update", store_payload);
                        resolve(response.data);
                    },
                    response => {
                        reject(response.data);
                    }
                );
            });
        },
        add(context, datum) {
            var payload = datum.data ? datum.data : datum;
            return new Promise((resolve, reject) => {
                api.store(payload).then(
                    response => {
                        var store_payload = response.data.data;
                        context.commit("add", store_payload);
                        resolve(response.data);
                    },
                    response => {
                        reject(response.data);
                    }
                );
            });
        },
        upload(context, datum) {
            var payload = datum.data ? datum.data : datum;
            var params = datum.params;
            if (payload.params) {
                delete payload.params;
            }
            return new Promise((resolve, reject) => {
                api.upload(payload, params).then(
                    response => {
                        var store_payload = response.data.data;
                        context.commit("add", store_payload);
                        resolve(response.data);
                    },
                    response => {
                        reject(response.data);
                    }
                );
            });
        },
        clear(context) {
            context.commit("clear");
        },
        api(context) {
            return api;
        }
    };
}
