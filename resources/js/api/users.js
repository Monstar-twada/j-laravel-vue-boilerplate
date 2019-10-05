import { web_prefix,api_prefix,build } from '@core/api-builder'
const api_resource = `${api_prefix}/users`;
const web_resource = `${web_prefix}/users`;


export default build({
    api_resource,
    web_resource,
});
