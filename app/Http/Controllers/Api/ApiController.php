<?php

namespace App\Http\Controllers\Api;

use Storage;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Route;
use App\Exceptions\RecentlyDeletedException;
use App\Http\Controllers\Controller;
use App\Criteria\TrashedCriteria;
use \Prettus\Validator\Contracts\ValidatorInterface;
use Auth;

abstract class ApiController extends Controller
{

    /**
     * The prettus-validator instance.
     *
     * @var \Prettus\Validator\LaravelValidator;
     */
    protected $validator = null;


    /**
     * The Eloquent repository instance.
     *
     * @var Prettus\Repository\Contracts\RepositoryInterface;
     */
    protected $repository;

    /**
     * The number of repositorys to return for pagination.
     *
     * @var int|null
     */
    protected $per_page;

    /**
     * The Transformer instance
     *
     * @var \League\Fractal\TransformerAbstract
     */
    protected $transformer;

    /**
     * The Resource Data key
     *
     * @var String
     */
    protected $key;

    /**
     * The Request
     *
     * @var Illuminate\Http\Request
     */
    protected $request;

    /**
     * The Storage
     *
     * @var Storage
     */
    protected $storage;


    /**
     * The Controller Service
     *
     * @param Request
     * @return void
     */
    protected $service;



    protected $action;

    protected $messages = [
        'index' => [
            'successful' => 'Successfully retrieved data',
        ],
        'store' => [
            'successful' => 'Successfully created data',
        ],
        'destroy' => [
            'successful' => 'Successfully deleted data',
        ],
        'update' => [
            'successful' => 'Successfully updated data',
        ],
        'show' => [
            'successful' => 'Successfully retrieved data',
        ],
    ];

    protected $criterias = [
        'index' => [
        ],
        'export' => [
        ],
        'show' => [
        ],

    ];

    protected $collections = ['index','all'];

    /**
     * Initialize per_page
     *
     * @param Request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->action = $request->route()->getActionMethod();
        $this->per_page = $request->input('per_page');
        $this->per_page = !empty($this->per_page) ? $this->per_page : \Config::get('borderless.defaults.pagination');
        $this->request = $request;
        $this->storage = Storage::disk('public');
        $this->boot();
    }

    public function getCriteria($action="")
    {
        $action = $action ? $action : $this->action;
        if(isset( $this->criterias[$action] )){
            return  $this->criterias[$action];
        }
        return [];
    }

    public function applyCriteria($action = "")
    {
        $criterias = $this->getCriteria($action);
        foreach($criterias as $criteria){
            $this->repository->pushCriteria($criteria);
        }
    }

    public function boot()
    {

    }

    /**
     * Get Paginated Collection
     *
     * @param Request
     * @return paginated collection
     */
    public function all()
    {
        $criterias = $this->applyCriteria('index');
        $collection = $this->repository->all();
        return $this->response($collection,200);
    }

    /**
     * Get Paginated Collection
     *
     * @param Request
     * @return paginated collection
     */
    public function index()
    {
        $this->applyCriteria();
        $collection = $this->repository->paginate($this->per_page);
        return $this->response($collection,200);
    }

    /**
     * Add an item
     *
     * @param POSTRequest
     * @return repository item
     */
    public function store()
    {
        $inputs =  $this->request->all() ;
        if($this->validator){
            $this->validator->with($inputs)->passesOrFail( ValidatorInterface::RULE_CREATE );
        }
        $result = $this->repository->create($inputs);
        return $this->response($result,200);
    }

    /**
     * Show specific item
     *
     * @param Request
     * @return document
     */
    public function show($id)
    {
        $this->applyCriteria();
        $params = $this->request->all();
        $key = 'id';
        if(isset($params['primaryKey'])){
            $key = $params['primaryKey'] ? $params['primaryKey'] : 'id';
        }

        if($key != 'id'){
             $this->repository->scopeQuery(function($query) use($key,$id){
                return $query->where($key,$id);
            });
             $item = $this->repository->first();
        }else{
            $item = $this->repository->find($id);
        }

        return $this->response($item,200);
    }

    /**
     * Update specific item
     *
     * @param PUTRequest
     * @return repository item
     */
    public function update($id)
    {
        $inputs =  $this->request->all() ;
        try{
            if($this->validator){
                $this->validator->with($inputs)->passesOrFail( ValidatorInterface::RULE_UPDATE );
            }
            $item = $this->repository->update($inputs,$id);
        }catch(ModelNotFoundException $e){
            $this->repository->popCriteria(TrashedCriteria::class);
            $deleted = $this->repository->find($id);
            if($deleted){
               throw new RecentlyDeletedException("");
            }
            throw $e;
        }

        return $this->response($item,200);
    }

    /**
     * Delete a specific item
     *
     * @param  int $id
     * @return Message String
     */
    public function destroy($id)
    {
        $ids = explode(',',$id);
        foreach($ids as $id){
            $this->repository->delete($id);
        }

        return $this->response(count($ids),200);
    }

    public function response($data=[],$status,array $response = [])
    {

        $action = $this->action;
        $state = $this->getState($status);
        $messages = $this->messages;
        $message = "";

        if($this->request->return_result){
            $this->request->return_result = false;
            return $data;
        }


        if(is_array($data) && count($data)==0){
            $status = 204; $message = "no content";
        }else {
            if(isset($messages[$action]) && isset($messages[$action][$state])){
                $message = $messages[$action][$state];
            }
        }

        if(!$this->request->data_only){

            if(!isset($response['message']) && isset($message)){
                $response['message'] = $message;
            }

            if(!isset($response['display']) && isset($response['message'])){
                $type = $state == "successful" ? "successful": "error";
                $response['display'] = "notification"."|"."$type";
            }

        }else{
            $response = [];
        }

        $is_collection = is_a($data,'Illuminate\Pagination\LengthAwarePaginator')
            || is_a($data,'Illuminate\Database\Eloquent\Collection');

        if($is_collection ||  is_a($data,'Illuminate\Database\Eloquent\Model')){
            $data = $this->transformData($data, $is_collection ? 'collection' : 'single');
            if($is_collection){
                return $data->additional($response);
            }
        }


        $response['data'] = $data;
        return response()->json($response,$status);
    }

    public function transformData($data,$transform=null)
    {
        switch($transform){
            case 'multiple':
            case 'plural':
            case 'collection':
                $data = $this->transformCollection($data,$this->transformer,$this->key);break;
            case 'singlular':
            case 'single':
                $data = $this->transformItem($data,$this->transformer,$this->key);break;
        }

        return $data;
    }

    public function getState($status)
    {
        if(strpos($status,'2') === 0){
            return 'successful';
        }
        if(strpos($status,'4') === 0 || strpos($status,'5') === 0 ){
            return 'error';
        }
        return 'informational';
    }

    public function getTransformType()
    {
        if($this->transform){
            return $this->transform;
        }

        $action = strtolower($this->action);
        if(in_array($action,$this->collections)){
            return 'collection';
        }

        return 'single';
    }

    /**
     * Get user
     *
     * @param  void
     * @return User
     */
    public function getUser()
    {
        return Auth::user();
    }

}
