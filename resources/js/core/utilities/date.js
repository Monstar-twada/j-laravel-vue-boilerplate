import moment from 'moment'

// format options
// option a = year/month/day/ 2017年 5月3 日 （金)


const intRangeToArray = (low, max) => {
    return _.range(low, max+1)
}
export default function(Vue){
    Vue.date = {
        getTime(val,format="YYYY-MM-DD HH:mm:ss"){
            return moment(val,format).valueOf();
        },
        getDate(str){
            if(!str){
                return "";
            }
            var value = ""
            switch(str.length){
                case 1 :
                    value = new Date().getFullYear()+"/"+str;
                    break;
                case 2:
                    var month = str[1]
                    var date = str[0]
                    value = new Date().getFullYear()+"/"+month+"/"+ (date ? date : str)
                    break;
                default : value = str
            }
            return value;
        },
        fromString:(str,format="Y-m-d") => {
            if(!str){
                return '';
            }
            str = str.replace(/[^0-9]/g,'');
            var year = str.length > 4 ? str.substring(0,4) : str;

            if(str.length <= 4 || format=='Y'){
                return year;
            }

            var month = "01";
            var date = "";
            if(str.length>4){
                month = str.substring(4,6);
                if(month == "0"){
                }
                else if(month == "00" || month == "01"){
                    month = "01";
                }else{
                    month = Number(month);
                    if( month < 10 && month > 1){
                        month = "0"+month;
                    }else if(month > 12 ){
                        if(str.length==6){
                            month = Number(str.substring(4,5));
                            if(month < 10){
                                month = "0"+month
                            }
                            date = Number(str.substring(5,6));
                        }else{
                            month = 12;
                        }
                    }

                }
            }

            if(format == 'Y-m'){
                return `${year}/${month}`;
            }

            if(str.length>6 || date){
                date = !date ? str.substring(6,8) : date.toString();
                if(date == "00"){
                    date = "01";
                }else if(date.indexOf('0') == 0){

                }else{
                    date = Number(date);
                    if(date > 28){
                        var last_date = new Date(Number( year ),Number(month),0).getDate();
                        date = date > last_date ? last_date : date;
                    }

                    if(date < 1){
                        date = 1;
                    }

                    if(date > 3 && date < 10 ){
                        date = "0"+date;
                    }
                }
            }

            //if(str.length <= 6){
            //    return `${year}/${month}`;
            //}

            return `${year}${month?'/':''}${month}${date?'/':''}${date}`

        },
        format: (date=moment(), format) => {
            if( new Date(date) == 'Invalid Date'){
                return date;
            }
            date = moment(date).toObject();
            var day = date.date;
            var month = date.months+1;
            var year = date.years;
            switch(format){
                case 'a':
                    //sample 2017年 5月3 日 （金)
                    date = `${year}年${month}月${day}日`
                    break;
                case 'b':
                    //sample 2017年 5月3 日
                    date = `${year}年${month}月${day}日`
                    break;
                case 'c':
                    //sample 2017年 5月3 日
                    date = `${year}年${month}月`
                    break;
                case 'd':
                    //sample 2017年 5月3 日
                    date = `${year.toString().substring(2,4)}年${month}月期`
                    break;
                default: date = moment(date).format(format)
            }
            return date
        },
    }
    Object.defineProperties(Vue.prototype,{
        $date:{
            get:() => {
                return Vue.date;
            }
        }
    });
}
