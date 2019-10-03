export var messages={
    en: (field, args) =>{

        //this field requires an exactly  number of characters
        return `abc is only`;
    },
    jp: (field, args)=>{
        return ` ${args}`;
    }
}
export function getMessage(field){
    return messages.jp(field)
}

export function validate(value,args){
    var val = value.replace(/[\uff01-\uff5e]/g, (s)=>{
        return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
    });
    var minByteSize = args;
    return new Blob([val]).size >= args;
}


export default {getMessage,messages,validate};



/* Author: AjDesamparado
 * Company: Freemight
 * Editor: Vim
 * Settings: tabstop=4 shiftwidth=4 expandtab=1 softtabstop=0 */

