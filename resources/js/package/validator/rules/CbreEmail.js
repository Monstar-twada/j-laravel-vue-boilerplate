export var messages={
    en: (field, args) =>{

        //this field requires an exactly  number of characters
        return ` ${field} Email is not valid cbre email。`;
    },
    jp: (field, args)=>{
        return `@を含む正しい電子メール形式で「cbre.com」または「cbre.co.jp」を入力してください`;
    }
}
export function getMessage(field){
    return messages.jp(field)
}

export function validate(value,args){
    return value.indexOf('@cbre.co.jp') >= 0 || value.indexOf('@cbre.com') >= 0
}


export default {getMessage,messages,validate};



/* Author: AjDesamparado
 * Company: Freemight
 * Editor: Vim
 * Settings: tabstop=4 shiftwidth=4 expandtab=1 softtabstop=0 */

