import cleave from './cleave'

const directives = {
    cleave,
}


directives.install = (Vue) => {
    for(let name in directives){
        var directive = directives[name]
        if (directive && name !== 'install') {
            Vue.directive(directive.name,directive)
        }
    }
}

if(typeof window != 'undefined' && window.Vue){
    window.Vue.use(directives);
}

export default directives
