<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public $model;

    public function __construct()
    {
        $this->model = new Page();
    }

    public function index($url = null)
    {
        $website = tenant();

        if($website->version ===  1){
            if($url){
                $page = $this->model->where('url', $url)
                    ->where("status", 1)
                    ->firstorfail();
            }else{
                $page = $this->model->where('url', null)
                    ->orWhere('url','')
                    ->orWhere('url','/')
                    ->where("status", 1)
                    ->firstorfail();
            }

            $data = optional($page->data);

            $seo['meta_title'] = $data['meta_title'];
            $seo['meta_description'] = extractDesc($data['meta_description']);
            $seo['meta_keywords'] = $data['meta_keywords'];
            $seo['meta_image'] = $page->getFirstMediaUrl("seo");


            if($page->type==='builder')
            {
                return view('frontend.builder', compact('page', 'data', 'seo'));
            }elseif($page->type==='box') {
                return view('frontend.box', compact('page', 'data', 'seo'));
            }elseif($page->type==='legal'||$page->type==='termsAndConditions') {
                return view('frontend.legal', compact('page', 'data', 'seo'));
            }
        }

        if(\request()->wantsJson()){
            return $this->jsonSuccess([
                'template'=> $website
            ]);
        }

        return view('frontend.v2', compact('website'));
    }
}
