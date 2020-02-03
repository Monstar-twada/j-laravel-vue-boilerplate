/*
 * ALL GET METHODS SHOULD RETURN ACTUAL PRIMARY DATA TYPE OF THE VALUE
 * ALL FORMAT METHODS SHOULD RETURN IN STRING VALUE
 * */
export default function (Vue) {
    Vue.formatter = {
        template:{
            percentage:'%',
            currency:''
        },
        getObjectParameters(param,addon){
            var parameters = param.split('|');
            var format = parameters[0];
            var options = this.getOptions(parameters.slice(1));
            if(typeof addon === 'object'){
                Object.assign(options,addon);
            }
            return {format,options};
        },
        /*
         * convert array options to key value object
         * ex: ['multiplier:1','precision:2']
         * result: { multiplier:1,'precision:2]
         * */
        getOptions(parameters){
            var options = {}
            for(let index in parameters){
                var split_opt = parameters[index].split(':');
                var key = split_opt[0];
                var args = split_opt[1].split(',');
                options[key] = args;
            }
            return options
        },
        StringToObject(parameters){
            return this.ArrayToObject(parameters.split('|'));
        },
        setLocale(locale){
            return 'ja'
        },
        toHalfWidth(value){
            value = value.toString();
            return value.replace(/[\uff01-\uff5e]/g, (s)=>{
              return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
            })
        },
        isEmpty(a,format="text"){
            var ret = false;
            if(a === '' || a === undefined || a === null || a === '-'){
                return true;
            }
            switch(format){
                case 'decimal':
                case 'float':
                case 'double':
                    ret = isNaN(parseFloat(a));
                    break;
                case 'integer':
                case 'number':
                    ret = isNaN(Number(a));
                    break;
            }
            return ret;
        },
        checkDefault(a,b,format){
            if(( ( a === '' || a === undefined || a === null ) && b === 0 ) ||
                ( b === '' || b === undefined || b === null ) && a === 0 ){
                return '';
            }
            if( Number(a) === 0 && Number(b) === 0){
                return 0;
            }
        },
        format(value,type,currency="$"){
            let {format,options} = this.getObjectParameters(type);
            var splitted = type.split('|');
            var format_type = splitted[0];
            var parameters=[];
            if(splitted.length>0){
                parameters = parameters.concat(splitted.splice(1));
            }
            if(this.isEmpty(value,format_type)){
                return value;
            }
            switch(format_type){
                case 'integer':
                case 'number':
                    value = this.formatNumber(value,parameters);
                    break;
                case 'currency':
                    value = this.formatCurrency(value,currency,parameters);
                    break;
                case 'date':
                    value = this.formatDate(value,parameters[0])
                    break;
                case 'float':
                case 'double':
                case 'decimal':
                    value = this.formatDecimal(value,parameters);
                    break;
                case 'percentage':
                    value = this.formatPercentage(value,this.getOptions(parameters));
                    break;
            }
            return value.toString();
        },
        formatDate(value,format){
            if(format.toLowerCase().indexOf( 'fromstring' ) >= 0 ){
                var format = format.split(':')[1];
                value = this.getNumber(value).toString();
                return Vue.date.fromString(value, format ? format : 'Y-m-d');
            }
            return Vue.date.format(value,format);
        },
        formatPercentage(value,{multiplier=1,precision=2,symbol="%" }){
            if(value == "-"){
                return value;
            }
            var number = this.formatDecimal(value,{precision,multiplier});
            return `${number}${ symbol }`;
        },
        formatNumber(value){
            if(value == "-"){
                return value;
            }
            value = this.toHalfWidth(value)
            return this.getNumber(value).toLocaleString('en');
        },
        formatDecimal(value,{ precision=8,stringify=true ,multiplier=1}){
            if(value == "-" || value == '.'){
                return value;
            }
            value = this.toHalfWidth(value)
            var number = this.getNumber(value);
            var point = this.getPoint(value);
            if(point){
                if(precision > 0 && precision != null){
                    point = point.toString().substring(0,precision)
                }
                point = "."+point
            }
            var new_val =  `${ number }${point}`
            if(value[0] == '-' && new_val[0] != '-'){
                new_val = '-'+new_val;
            }
            new_val = parseFloat(new_val) * multiplier;
            if(!stringify){
                return new_val;
            }
            if(this.isEmpty(new_val)){
                return '';
            }
            new_val = new_val.toLocaleString('en');
            if(point == '.' && value.indexOf('.')>=0){
                new_val = new_val+".";
            }
            return new_val
        },
        formatCurrency(value,currency="$"){
            var number = this.getNumber(value);
            if(!isNaN(number)){
                number = Number(number).toLocaleString('en');
            }
            return currency+number;
        },
        getJapanese(value){
            var match = value
            .match(/[\u3000-\u303F\u3040-\u309F\u30A0-\u30FF\uFF00-\uFFEF\u4E00-\u9FAF\u2605-\u2606\u2190-\u2195]|Tab/)
            value  = value.replace(match,'');
            return value;
        },
        getNumber(value){
            if(this.isEmpty(value) || value == "-"){
                return value;
            }

            var number = value = this.toHalfWidth(value);
            var negative = number[0] == "-" ? '-' : '';
            var split = number.split('.');
            number = split.length>0 ? split[0] : number;
            number = number.replace(/\D+/g,'');
            number = `${ negative }${ number }`
            return  this.isEmpty(number,'number') ? '' : Number(number);
        },
        getPoint(value){
            if(!value){
                return 0;
            }
            var number = value.toString();
            var split = number.split('.');
            number = split.length>1 ? split[1] : '';
            number = number.replace(/\D+/g,'');
            var is_zero_prefixed = number[0] == '0'
            if(this.isEmpty(number,'number')){
                return '';
            }
            if(is_zero_prefixed){
                return number;
            }
            return Number(number);
        },
        getDecimal(value){
            if(this.isEmpty(value) || value == "-"){
                return value;
            }
            value = value.toString();

            var negative = value[0] == '-' ? '-' : '';
            var number = this.getNumber(value);
            var point = this.getPoint(value);
            point = point ?`.${point}`:''
            var new_val = `${ number }${point}`;
            return this.isEmpty(new_val,'decimal') ? '' : parseFloat(new_val);
        },
        getDate(value,format){
            var format = format.split(':')[1];
            value = this.getNumber(value).toString();
            return Vue.date.fromString(value, format ? format : 'Y-m-d');
        },
        getValue(value,type){
            var splitted = type.split('|');
            var format_type = splitted[0];
            var options=[];
            if(splitted.length>0){
                options = options.concat(splitted.splice(1,1));
            }
            if(this.isEmpty(value,format_type)){
                return value;
            }
            switch(format_type){
                case 'integer':
                case 'percentage':
                case 'number':
                    value = this.getNumber(value,options);
                    break;
                case 'currency':
                case 'double':
                case 'decimal':
                case 'float':
                    value = this.getDecimal(value,options);
                    break;
                case 'date':
                    value = this.getDate(value,options[0])
                    break;
            }
            return value;
        },
    },

    Object.defineProperties(Vue.prototype,{
        $formatter:{
            get:() => {
                return Vue.formatter;
            }
        }
    })
}
