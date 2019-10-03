import _actions from './actions'
import _getters from './getters'
import _mutations from './mutations'
import _state from './state'

export const base = {
    actions : _actions,
    getters : _getters,
    mutations : _mutations,
    state   : _state,
}

export function assign(part,key){
    var basis = base[key];
    for(let p in basis){
        if(!part[p]){
            var prop = basis[p];
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
