import _getters from './getters'
import _mutations from './mutations'
import _state from './state'

const base = {
    actions : _actions,
    getters : _getters,
    mutations : _mutations,
    state   : _state,
}

export function assign(part,key,options={}){
    var basis = base[key];
    var obj = typeof basis == 'function' ? basis(options) : basis;
    for(let p in obj){
        if(!part[p]){
            var prop = obj[p];
            part[p] = prop;
        }
    }
    return part;
}

export function build(module,basis=base,key="module"){
    if(key == 'module'){
        for(let i in basis){
            if(!module[i]){
                module[i] = basis[i];
            }else{
                module[i] = assign(module[i],i)
            }
        }
        return module;
    }
    return assign(module[key],basis[key]);
}

export default build;
