<?php

namespace App\Traits;
use Illuminate\Http\Resources\Json\JsonResource;

trait ResourceTransformer
{

    /**
     * Transform Item
     *
     * @param Model $data, Transformer $transformer
     * @return array
     */
    public function transformItem($data = null, $transformer = null, $key = null)
    {
        if(!$transformer){
            return [ 'data' => $data];
        }

        $transformer->resource=$data;
        //$transformer->wrap($key);
        $result = $transformer->toArray($data);
        return $result ;
    }

    /**
     * Transform Collection
     *
     * @param Model $data, Transformer $transformer
     * @return array
     */
    public function transformCollection($data = null, $transformer = null, $key = null)
    {
        if(!$transformer){
            return [ 'data' => $data];
        }

        $transformer->resource = $data;
        //$transformer->wrap(str_plural($key));
        $result = $transformer->collection($data);

        return $result;
    }

    /**
     * Transform Collection
     *
     * @param Model $data, Transformer $transformer
     * @return array
     */
    public function transformResource($data = null, $transformer = null, $key = null){
        if(!$transformer){
            return [ 'data' => $data];
        }

        $transformer->resource=$data;
        //$transformer->wrap($key);
        $resource = $transformer->toArray($data);
        $result = $this->transformRecursively($resource);
        return $result ;
}

    /**
     * Transform Collection
     *
     * @param Model $data, Transformer $transformer
     * @return array
     */
    public function transformResourceCollection($data = null, $transformer = null, $key = null){
        if(!$transformer){
            return [ 'data' => $data];
        }
        $request = null;
        $resource = $this->transformCollection($data,$transformer,$key)->toArray($request);
        $result = $this->transformRecursively($resource);

        return $result;
    }

    public function transformRecursively($resource,$request=null){
        array_walk_recursive($resource,function(&$item,$key) use($request){
            if($item instanceof JsonResource){
                $item = $item->toArray($request);
            }
        });
        return $resource;
    }

}
