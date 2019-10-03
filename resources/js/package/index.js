import validator from './validator'

const custom_packagess = {
    validator,
}

custom_packagess.install = (Vue, options = {}) => {

    for (const custom_packages_name in custom_packagess) {
        const custom_packages = custom_packagess[custom_packages_name]

        if (custom_packages && custom_packages_name !== 'install') {
            var option = options[custom_packages_name];
            Vue.use(custom_packages, option ? option : {});
        }
    }
}

if(typeof window != 'undefined' && window.Vue){
    window.Vue.use(custom_packagess);
}

export default custom_packagess
