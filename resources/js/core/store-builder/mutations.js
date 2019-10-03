export default{
    set(state,data){
        if(!data){
            state.list = []
            return;
        };
        if(Array.isArray(data)){
            state.list = data;
        }else{
            state.list.push(data);
        }
    },
    add(state,datum){
        if(!datum){return}
        var data=[];
        var o_data = datum.data?datum.data:datum
        var mapping = datum.mapping?datum.mapping:[];
        var data = Array.isArray(o_data)?o_data:[o_data];

        state.model.add(state.list,data);
    },
    remove(state,id){
        if(!id){return}
        var o_id = id.id?id.id:id
        var mapping = id.mapping?id.mapping:'';
        var ids = Array.isArray(o_id)?o_id:[o_id];

        state.model.remove(state.list,ids);
    },
    update(state,datum){
        if(!datum){return}
        var o_data = datum.data?datum.data:datum
        var mapping = datum.mapping?datum.mapping:[];
        var data = Array.isArray(o_data)?o_data:[o_data];

        state.model.update(state.list,data);
    },
    clear(state){
        state.list = [];
    },
    join(state,data){
        if(!data){return}
        data = Array.isArray(data)?data:[data];
        for(let i in data){
            state.model.add(state.list,data[i]);
        }
    },
}
