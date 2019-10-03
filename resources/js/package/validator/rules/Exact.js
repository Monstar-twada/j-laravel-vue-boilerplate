export var messages={
    en: (field, args) =>{

        //this field requires an exactly  number of characters
        return `this field requires an exactly ${args} number of characters`;
    },
    jp: (field, args)=>{
        return ` ${args} 桁の数字を入力してください。`;
    }
}
export function getMessage(field){
    return messages.jp(field)
}

export function validate(value,args){
    var length=args;

    return value.length==length;
}


export default {getMessage,messages,validate};



/* Author: AjDesamparado
 * Company: Freemight
 * Editor: Vim
 * Settings: tabstop=4 shiftwidth=4 expandtab=1 softtabstop=0 */

