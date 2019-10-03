import test from './test'
import config from './config'
import date from './date'
import formatter from './formatter'
import colors from './colors'
import mobile from './mobile'
//import lodash from './lodash/lodash'
//import handler from './handler/handler'
//import unique from './unique/unique'
//import md5 from './md5/md5'
import auth from './auth'
//import encode from './encode/encode'
//import route from './route/route'
//import time from './time/time'
import file from './file'
import masks from './masks'
import error_checker from './error_checker'
import url from './url'

const HelperUtility = {
    date,
    test,
    config,
    formatter,
    //lodash,
    //handler,
    //unique,
    //md5,
    auth,
    //encode,
    //route,
    //time,
    file,
    colors,
    masks,
    error_checker,
    mobile,
    url,
}


HelperUtility.install = (Vue) => {
    for(let name in HelperUtility){
        var utility = HelperUtility[name]
        if (utility && name !== 'install') {
            Vue.use(utility);
        }
    }
}

if(typeof window != 'undefined' && window.Vue){
    window.Vue.use(HelperUtility);
}

export default HelperUtility
