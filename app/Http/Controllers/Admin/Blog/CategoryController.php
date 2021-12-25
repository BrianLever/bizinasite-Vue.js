<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Admin\AdminController;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends AdminController
{
    public function __construct(BlogCategory $model)
    {
        $this->model = $model;
        $this->sortModel = $this->model;
    }

    public function index()
    {
        if(request()->wantsJson())
        {
            $categories = $this->model->with(["tags:blog_tags.id,blog_tags.name", "media"])
                ->withCount("subscribers")
                ->get();

            $activeCats = $categories->where('status', 1);
            $inactiveCats = $categories->where('status', 0);

            $all = view('components.admin.blogCategory', [
                'categories'=>$categories,
                'selector'=>"datatable-all"
            ])->render();

            $active = view('components.admin.blogCategory', [
                'categories'=>$activeCats,
                'selector'=>"datatable-active"
            ])->render();

            $inactive = view('components.admin.blogCategory', [
                'categories'=>$inactiveCats,
                'selector'=>"datatable-inactive"
            ])->render();

            $count['all'] = $categories->count();
            $count['active'] = $activeCats->count();
            $count['inactive'] = $inactiveCats->count();

            return response()->json([
                'status'=>1,
                'all'=>$all,
                'active'=>$active,
                'inactive'=>$inactive,
                'count'=>$count
            ]);
        }
        $tags = BlogTag::status(1)->get();
        return view(self::$viewDir.'blog.category', compact('tags'));
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                $this->model->storeRule($request),
                $this->model::CUSTOM_VALIDATION_MESSAGE
            );
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors()
                ]);
            }
            $category = $this->model->storeItem($request);

            return response()->json(['status' => 1, 'data' => $category]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())]
            ]);
        }
    }
}
