const APP_ENV = process.env.MIX_APP_ENV;
export default function( Vue ){

    Vue._test={
        label(text,append){
            if(APP_ENV == 'local'){
                return append+text;
            }

            return text
        },
        test(){

        }
    }

    Object.defineProperties(Vue.prototype,{
        $_test:{
            get:() => {
                return Vue._test;
            }
        }
    })
}
