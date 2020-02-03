export default function (Vue){
    Vue._colors = {
        primary:'#006a4d', 
        accent:'#69be28',
        danger:'#f44336',
    },

        Object.defineProperties(Vue.prototype,{
            $_colors:{
                get:() => {
                    return Vue._colors;
                }
            }
        })
}
