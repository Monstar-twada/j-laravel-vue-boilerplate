const only = {
    date_keys(event){
        if(Vue.masks.only.is_special_key(event)){
            return;
        }
        var key = event.which || event.keyCode || event.charCode;
        if(Vue.masks.only.is_number_key(event) || key == 191){
            return;
        }
        event.preventDefault();
    },
    number_keys(event){
        if(Vue.masks.only.is_special_key(event)){
            return;
        }
        var key = event.which || event.keyCode || event.charCode;
        if(Vue.masks.only.is_number_key(event) || key == 188){
            return;
        }
        event.preventDefault();
    },
    decimal_keys(event){
        if(Vue.masks.only.is_special_key(event)){
            return;
        }
        var key = event.which || event.keyCode || event.charCode;
        if(Vue.masks.only.is_number_key(event) || key == 188 || key == 190){
            return;
        }
        event.preventDefault();
    },
    /*
     * Check if event key is a numeric key
     * returns Boolean
     */
    is_number_key(event){
        var key = event.which || event.keyCode || event.charCode;
        if(key == 189 || ( event.shiftKey === false && key > 47 && key < 60 )|| !isNaN(Number(event.key))){
            return true;
        }
        return false;
    },
    /*
     * Check if event key is a special key
     * returns Boolean
     */
    is_special_key(event){
        var allowable_is_special_key = [8,35,36,37,38,39,40,46, 9, 27, 13, 110, 116,115];
        var key = event.which || event.keyCode || event.charCode;
        var is_special = allowable_is_special_key.includes(key);
        //if ctrl+a or alt+a is down do not prevent
        var is_special_a = key === 65 && (event.ctrlKey === true || event.metaKey === true)
        //if meta+shift+a or alt+a is down do not prevent
        var is_special_r = key === 82 && (event.shiftKey === true || event.metaKey === true)
        if(is_special || is_special_a || is_special_r ){
            return true;
        }
        return false;
    },
    is_enter_key(event){
        var key = event.which || event.keyCode || event.charCode;
        var allowable_enter_key = [13];
        if(allowable_enter_key.includes(key)){
            return true;
        }
        return false;
    }
}

export default function (Vue) {
    Vue.masks = {
        creditCard: { creditCard: true },
        full_date:{
            date:true,
            delimeters:['-'],
            datePattern:['Y','m','d'],
            blocks:[4,2,2],
        },
        year_month_date:{
            date:true,
            datePattern:['Y','m'],
            delimeter:'-',
            blocks:[4,2],
        },
        integer: {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            numeralDecimalScale:0,
        },
        decimal: {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            numeralDecimalScale:4,
        },
        double: {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            numeralDecimalScale:2,
        },
        custom: {
            delimiters: ['.', '.', '-'],
            blocks: [3, 3, 3, 2],
            numericOnly: true
        },
        only,
    },

        Object.defineProperties(Vue.prototype,{
            $masks:{
                get:() => {
                    return Vue.masks;
                }
            }
        })
}
