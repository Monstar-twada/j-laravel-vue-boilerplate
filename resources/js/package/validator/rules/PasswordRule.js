export var messages={
    en: (field, args) =>{

        //this field requires an exactly  number of characters
        return `this field ${field} must atleast contain 1 lowercase, 1 uppercase, 1 numeric, 1 special character and must be atleast eight characters longer`;
    },
    jp: (field, args)=>{
        return `パスワードは少なくとも1つの小文字、大文字、数字、特殊文字を含み、8文字以上である必要があります。`;
        //return ` ${field} 桁の数字を入力してください。`;
    }
}
export function getMessage(field){
    return messages.jp(field)
}

// RegEx	Description
// ^	The password string will start this way
// (?=.*[a-z])	The string must contain at least 1 lowercase alphabetical character
// (?=.*[A-Z])	The string must contain at least 1 uppercase alphabetical character
// (?=.*[0-9])	The string must contain at least 1 numeric character
// (?=.[!@#\$%\^&])	The string must contain at least one special character, but we are escaping reserved RegEx characters to avoid conflict
// (?=.{8,})	The string must be eight characters or longer
export function validate(value,args){
    var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
    return strongRegex.test(value);
}


export default {getMessage,messages,validate};