<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;
use Illuminate\Validation\Rule;


/**
 * Class UserValidator.
 *
 * @package namespace App\Validators;
 */
class UserValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'email' => ['required','email'],
            'last_name' => 'required',
            'first_name' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'email' => ['sometimes','required','email'],
            //'sometimes|required|email|unique:users,email,:id',
            'last_name' => 'required',
            'first_name' => 'required',
        ],
    ];

    protected $messages = [
        'email.required' => 'メールアドレスを入力してください。',
        'email.email' => '指定したメールアドレスは有効ではありません。',
        'email.unique' => 'このメールアドレスは既に存在します。',
    ];

    /**
     * Get rule for validation by action ValidatorInterface::RULE_CREATE or ValidatorInterface::RULE_UPDATE
     *
     * Default rule: ValidatorInterface::RULE_CREATE
     *
     * @param null $action
     * @return array
     */
    public function getRules($action = null)
    {
        $rules = $this->rules;
        $data = $this->data;

        if (isset($this->rules[$action])) {
            $rules = $this->rules[$action];
        }

        $rules['email']['unique'] = Rule::unique('users','email')->where(function($query) use ($action,$data){
            $query = $query->where('deleted_at',null);
            if($action == ValidatorInterface::RULE_UPDATE && isset($data['id'])){
                $query = $query->where('id','!=',$data['id']);
            }
            return $query;
        });

        return $this->parserValidationRules($rules, $this->id);
    }

}
