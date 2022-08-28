<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Http\Request;

class FetchDataController extends Controller
{
    public function getAllUsers(){
        $users = User::paginate(10);
        if (count($users)>0) {
            return response()->sendSuccess('success', UsersResource::collection($users));
        }else{
            return response()->sendSuccess('Not Found Data',[],204);
        }
    }
    public function search($statment){
        $data =User::where('firstName','like','%'.$statment.'%')->orWhere('lastName','like','%'.$statment.'%')->orWhere('email','like','%'.$statment.'%')->paginate(5)->setpath('');
        if (count($data)>0){
        return response()->sendSuccess('success',UsersResource::collection($data));
        }else{
            return response()->sendSuccess('Not Found Data',[],204);

        }
    }
}
