<?php

namespace App\Http\Controllers\Api;

use App\Repositories\UserRepository;
use App\Http\Resources\User as UserResource;
use App\Criteria\UserCriteria;
use App\Http\Requests\UserRequest;
use App\Criteria\TrashedCriteria;


class UserController extends ApiController
{
    protected $messages = [
        'index' => [
            'successful' => '',
        ],
        'store' => [
            //'successful' => 'Successfully added User',
            'successful' => '営業担当者の登録が完了しました',
            //'error'      => 'Failed to add new User'
            'error'      => '営業担当者の登録に失敗しました'
        ],
        'destroy' => [
            //'successful' => 'Successfully removed User',
            'successful' => 'セールスマンを正常に削除しました',
            //'error'      => 'Failed to removed User'
            'error'      => 'セールスマンを削除できませんでした'
        ],
        'update' => [
            //'successful' => 'Successfully update User profile',
            'successful' => '営業担当者の更新が完了しました',
            //'error'      => 'Failed to update User profile',
            'error'      => '営業担当者の更新が失敗しました',
        ],
        'show' => [
            'successful' => '',
        ],
    ];

    public function __construct(UserRepository $repository,UserRequest $request)
    {
        parent::__construct($request);
        $repository->pushCriteria(new UserCriteria($request));
        $this->repository = $repository;
        $this->transformer = new UserResource(null);
    }

    /**
     * Get Paginated Collection
     *
     * @param Request
     * @return paginated collection
     */
    public function index()
    {
        $columns = [ "*" ];
        $request = $this->request;
        if($request->has('sort')){
            if(strpos($request->input('sort'),"authority_full_name") !== false){
                $columns = [ "users.*" ];
            }
        }
        $collection = $this->repository->paginate($this->per_page,$columns);
        return $this->response($collection,200);
    }

    public function store()
    {
        $this->repository->pushCriteria(new TrashedCriteria(true));
        $new = $this->request->except('np');
        $new['password'] = $this->request->input('np');

        $model = $this->repository->create($new);
        return $this->response($model,200);
    }

    /**
     * Update specific item
     *
     * @param PUTRequest
     * @return repository item
     */
    public function update($id)
    {
        $item = $this->repository->find($id);
        $data = $this->request->except('cp','np','op');

        if($item == $data['email']){
            unset($data['email']);
        }

        if($this->request->has('np')){
            $old_password = $this->request->input('op');
            if(!\Hash::check($old_password,$item->password)){
                return $this->response($item->password,500,
                    [
                        //'message' => "current site login password is not correct"
                        'message' => "ログイン用パスワードが間違っています"
                    ]
                );
            }
            $confirm_password = $this->request->input('cp');
            $new_password = $this->request->input('np');
            if($confirm_password != $new_password){
                return $this->response($item->password,500,
                    [
                        //'message' => "confirm site login password does not match new site login password"
                        'message' => "パスワードが新しいパスワードと一致しないことを確認する"
                    ]
                );
            }

            $data['password'] = $new_password;
        }

        $item = $this->repository->update($data,$id);

        return $this->response($item,200);
    }

}
