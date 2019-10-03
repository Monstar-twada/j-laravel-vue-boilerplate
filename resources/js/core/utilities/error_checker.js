export default function (Vue) {
    Vue.error_checker = {
        onError(vm,messages={}){
            var errors = vm.errors;
            var message = "";
            for(let i in errors.items){
                var item = errors.items[i];
                var msg = messages[item.field] ? messages[item.field] : `${item.field}: ${item.msg}`;
                item.msg = msg;

                message += msg+`<br>`;
            }
            return message;
        }

    },

    Object.defineProperties(Vue.prototype,{
        $error_checker:{
            get:() => {
                return Vue.error_checker;
            }
        }
    })
}
