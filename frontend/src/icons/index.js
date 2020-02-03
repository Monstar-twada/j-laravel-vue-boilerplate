
const icons = {
}
import * as svgicon from 'vue-svgicon'
icons.install = (Vue,options = {}) => {
    //Options
    //setOptions(Object.assign(config,options))
    Vue.use(svgicon,{
        tagName:'svgicon',
    })
}

if(typeof window != 'undefined' && window.Vue){
    window.Vue.use(icons);
}

export default icons;
