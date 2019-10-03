export const dictionary={
    en:{
        messages:{
            required: (field,args) => `${field} is required.`,
            numeric: (field,args) => `${field} must only contain whole numbers.`,
            min: (field,args) => `${field} must contain atleast ${args} number of characters.`,
            max: (field,args) => `${field} cannot contain more than ${args} number of characters.`,
            min_value:(field,args) => `please select a minimum value of ${args}`,
            max_value:(field,args) => `please select a larger value`,
            Confirm_password:(field,args) => 'confirm password does not match password',
            between: (field,min,max) => `${field} must contain atleast ${min} and not more than ${max} number of characters.`,
            digits: (field,args) => `${field} must be atleast ${args} digits number.`,
        }
    },
    jp:{
        messages:{
            required: (field,args) => "必須項目",
            numeric: (field,args) =>"正の数だけを入力してください",
            min: (field,args) => `このフィールドには少なくとも${args}文字の文字を含める必要があります`,
            min_value: (field,args) =>`の最小値は${args}以上を選択してください`,
            max_value: (field,args) =>`より大きな値を選択してください`,
            Confirm_password:(field,args) => '確認パスワードがパスワードと一致しません',
            confirmed:(field,args) => '確認パスワードがパスワードと一致しません',
            max: (field,args) => `${args}桁以上の数字は入力できません。`,
            between: (field,min,max) => `このフィールドには、少なくとも"${min}"文字と${max}文字以下の文字を含める必要があります`,
            digits: (field,args) =>`${args}桁の数字を入力してください。`,
            email: (field,args)  => `@を含む正しいE-MAIL形式で入力して下さい。`,
            url: (field,args)  => `${field}が有効ではありません。`,
        }
    }
}
export default dictionary
/* Author: AjDesamparado
 * Company: Freemight
 * Editor: Vim
 * Settings: tabstop=4 shiftwidth=4 expandtab=1 softtabstop=0 */

