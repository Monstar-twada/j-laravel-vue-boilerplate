//prefetch dependencies here

//custom dependencies here
import custom_packages from './package/index'
import directives from './directives'
import utilities from './utilities'
//import filters from './filters'

//npm package dependencies here
import icons from './icons'
import base from './base'


//dependency components

const dependencies ={
    custom_packages,
    icons,
    base,
    directives,
    utilities,
    //filters,
}

dependencies.install = (Vue) => {
    for(let name in dependencies){
        var dependency = dependencies[name]
        if (dependency && name !== 'install') {
            Vue.use(dependency)
        }
    }
}

if(typeof window != 'undefined' && window.Vue){
    window.Vue.use(dependencies);
}

export default dependencies
