<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Admin\AdminController;
use App\Models\BaseModel;
use Illuminate\Http\Request;
use Validator;

class BasicController extends AdminController
{

    public function index()
    {
        $basic = option("basic", null);
        return view(self::$viewDir . "setting.basic", compact("basic"));
    }
    public function rule($request)
    {
        $rule['name'] = 'required|max:45';
        $rule['sign'] = 'max:45';
        $rule['loading_color'] = 'max:191';
        $rule['loading_time'] = 'nullable|integer';
        $rule['loading'] = 'required|in:0,1,2,3,4,5,6,7';
        $rule['logo'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        $rule['favicon'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';

        return $rule;
    }

    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), $this->rule($request));
        if($validation->fails())
        {
            return response()->json(['status'=>0, 'data'=>$validation->errors()]);
        }

        $basic = option("basic", null);
        $basic['name'] = $request->name;
        $basic['sign'] = $request->sign;
        $basic['loading'] = $request->loading;
        $basic['loading_color'] = $request->loading_color;
        $basic['loading_time'] = $request->loading_time;

        $path = config("custom.storage_name.setting");

        if($logo=$request->logo)
        {
            $logo_name = "logo." . $logo->getClientOriginalExtension();
            $basic['logo'] = BaseModel::fileUpload($logo, $logo_name, $path);
        }

        if($favicon=$request->favicon)
        {
            $favicon_name = "favicon." . $favicon->getClientOriginalExtension();

            $basic['favicon'] = BaseModel::fileUpload($favicon, $favicon_name, $path);
        }

        option(['basic'=>$basic]);
        option(['register'=>$request->register? 1:0]);
        option(['verification'=>$request->verification? 1:0]);

        return response()->json([
            'status' => 1,
            'data' => 1
        ]);

    }
}
