const APP_URL = process.env.MIX_APP_URL;
const APP_ENV = process.env.MIX_APP_ENV;
const APP_DEBUG = process.env.MIX_APP_DEBUG;
const APP_DOMAIN = process.env.MIX_APP_DOMAIN;
const APP_LOGO = `${APP_URL}/logos/logo-white.svg`;
const APP_LOGO_ALT = `${APP_URL}/logos/logo-green.png`;
;

export default function( Vue ){

    Vue._config={
        app_env:APP_ENV,
        app_url:APP_URL,
        app_debug:APP_DEBUG,
        app_domain:APP_DOMAIN,
        app_logo:APP_LOGO,
        app_logo_alt:APP_LOGO_ALT,
        app_sidebar:true,
        app_header:true,
        page_options:[
            { value:15, label:"15"},
            { value:30, label:"30"},
            { value:50, label:"50"},
            { value:75, label:"75"},
            { value:100, label:"100"},
        ]
    }

    Object.defineProperties(Vue.prototype,{
        $_config:{
            get:() => {
                return Vue._config;
            }
        }
    })
}
