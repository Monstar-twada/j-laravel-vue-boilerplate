<?php

namespace App\Http\Controllers\Web;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;

abstract class WebController extends Controller
{

    /**
     * The Eloquent repository instance.
     *
     * @var \Illuminate\Database\Eloquent\repository
     */
    protected $repository;

    /**
     * The Transformer instance
     *
     * @var Illuminate\Http\Resources\Json\JsonResource
     */
    protected $transformer;

    /**
     * The Request
     *
     * @var Illuminate\Http\Request
     */
    protected $request;

    /**
     * Initialize per_page
     *
     * @param Request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->boot();
    }

    public function boot()
    {

    }

}
