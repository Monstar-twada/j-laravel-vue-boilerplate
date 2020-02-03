import base from './base';

const app_url = process.env.MIX_APP_URL;
const api_prefix = `${app_url}/api`;
const web_prefix = `${app_url}`;

export {
    base,
    api_prefix,
    web_prefix,
}

export function build(part,basis=base){
    for(let p in basis){
        if(!part[p]){
            var prop = basis[p];
            part[p] = prop;
        }
    }
    return part
}

export default build;
