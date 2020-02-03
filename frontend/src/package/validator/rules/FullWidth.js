import eaw from 'eastasianwidth';

export var messages={

    en: (field , args) => {

    },
    jp: (field,args) => {
        // full width message 半角で入力してください。

    }

}

export function getMessage(field){
    return messages.jp(field)
}
export function validate(value,args){
    /* half and full width character
     * Regex for matching half-width Katakana (hankaku 半角)
     * [ｧ-ﾝﾞﾟ]
     *
     * Regex for matching full-width (zenkaku) Katakana codespace characters (includes non phonetic characters)
     * [ァ-ン]
     *
     * change: regex.test(input[item]) to /[ァ-ヶ]/.test(input[item])
     */

    //var regex = /[\uFF00-\uFFEF]/g;
    var result = true;

    for(var item in value){
        if(item != "strremarks"){
            if (value.hasOwnProperty(item)) {
                var _type = false;
                function isHalfWidth(c){ return eaw.length(c) == 1; }
                if(item.substring(0,3)=="int"){
                    (isNaN(value[item])) ? _type = isHalfWidth(value[item]) : _type = !isHalfWidth(value[item]);
                }
                else if(item.substring(0,3)=="str"){
                    _type = /[ァ-ン]/.test(value[item]);
                }

                if(_type) {
                    (result) ? true : false;
                }
                else {
                    if(item == "incharge"){
                        for(var key in value[item]){
                            if(key == "id" && value[item].hasOwnProperty(key)){
                                (result) ? true : false;
                            }
                        }
                    }
                    else{
                        result = false;
                    }
                }
            }
        }
        else{
            if(value[item] != ""){
                result = /[ァ-ン]/.test(value[item]);
            }
            else{
                result = true;
            }
        }
    }
    return result;
}
export default {getMessage,messages,validate}

/* Author: AjDesamparado
 * Company: Freemight
 * Editor: Vim
 * Settings: tabstop=4 shiftwidth=4 expandtab=1 softtabstop=0 */

