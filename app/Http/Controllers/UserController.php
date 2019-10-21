<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //
    function store(Request $request){
        $this->validate($request, [
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'names' => 'required|min:2|max:191',
            'paternal_surname' => 'required|min:2|max:191',
            'maternal_surname' => 'min:2|max:191',
            'age' => 'integer',
            'permissions' => 'required|array|between:6,6',
            'role' => ['required',Rule::in(['administrador','operador','desarrollador']),'exists:roles,name'],
        ]);

        //verify if at least one permit is true
        $st = 0;
        foreach ($request->permissions as $key => $permission) {
           if($permission > 0){
                $st++;
           } 
        }
        if($st <= 0){
            return [
                'success' => false,
                'error' => 'Almost 1 of permissions needs to be true'
            ];
        }

        //Save User
        $n_user = new User;
        $n_user->username = $request->username;
        $n_user->email = $request->email;
        $n_user->names = $request->names;
        $n_user->paternal_surname = $request->paternal_surname;
        $n_user->maternal_surname = $request->maternal_surname;
        $n_user->age = $request->age;
        $n_user->save();
        
        $role = Role::where('name', $request->role)->first();
        
        //Asign permissions
        $perm = $request->permissions;
        $permissions = [
            'create' => $perm[0],
            'read' => $perm[1],
            'update' => $perm[2],
            'delete' => $perm[3],
            'copy' => $perm[4],
            'activate' => $perm[5],
        ];
        
        //Save user Role with permissions
        $n_user->role()->attach($role->id, $permissions);

        return ['success' => true];
    }
    
    function update(Request $request,$id){
        $request->request->add(['id' => $id]);
        $this->validate($request, [
          //  'username' => 'required|unique:users',
           // 'email' => 'required|email|unique:users',
            'id'=> 'required|exists:users,id',
            'names' => 'min:2|max:191',
            'paternal_surname' => 'min:2|max:191',
            'maternal_surname' => 'min:2|max:191',
            'age' => 'integer',
            'permissions' => 'required_with:role|array|between:6,6',
            'role' => ['required_with:permissions',Rule::in(['administrador','operador','desarrollador']),'exists:roles,name'],
        ]);

        //Save User
        $n_user = User::find($id);
        $n_user->names = isset($request->names) ? $request->names : $n_user->names;
        $n_user->paternal_surname = isset($request->paternal_surname)? $request->paternal_surname: $n_user->paternal_surname;
        $n_user->maternal_surname =isset($request->maternal_surname) ? $request->maternal_surname :$n_user->maternal_surname ;
        $n_user->age = isset($request->age) ? $request->age :  $n_user->age;
        $n_user->save();

        //verify if at least one permit is true
        if(isset($request->permissions)){
            $st = 0;
            foreach ($request->permissions as $key => $permission) {
               if($permission > 0){
                    $st++;
               } 
            }
            if($st <= 0){
                return [
                    'success' => false,
                    'error' => 'Almost 1 of permissions needs to be true'
                ];
            }

            //Asign permissions
            $perm = $request->permissions;
            $permissions = [
                'create' => $perm[0],
                'read' => $perm[1],
                'update' => $perm[2],
                'delete' => $perm[3],
                'copy' => $perm[4],
                'activate' => $perm[5],
            ];
            $role = Role::where('name', $request->role)->first();
        
        
        
            //Save user Role with permissions
            $n_user->role()->detach();
            $n_user->role()->attach($role->id, $permissions);
        }

    
        return ['success' => true];
    }


    function show($id){
        $request->request->add(['id' => $id]);
        $this->validate($request, [
              'id'=> 'required|exists:users,id'
        ]);
        $user = User::with('role')->find($id);
        return [
            'success' => true,
            'users' => $user
        ]; 
    }

    function index(){
        $users = User::with('role')->get();
        return [
            'success' => true,
            'users' => $users
        ]; 
    }

    function getByRole(Request $request){
        $this->validate($request, [
            'role' => ['required',Rule::in(['administrador','operador','desarrollador']),'exists:roles,name'],
        ]);
        
        $role = $request->role;

        $users = User::with('role')->whereHas('role', function($q) use($role) {
            $q->where('name', $role);
        })->get();
        
        return [
            'success' => true,
            'users' => $users
        ]; 
    }  

    function getByPermission(Request $request){
        $this->validate($request, [
            'permission' => ['required',Rule::in(['create','read','update','delete','copy','activate'])],
        ]);
        
        $permission = 'role_user.'.$request->permission;

        $users = User::with('role')->whereHas('role', function($q) use($permission) {
            $q->where($permission, 1);
        })->get();
        
        return [
            'success' => true,
            'users' => $users
        ]; 
    } 
    
    function getByStatus(Request $request){
        $this->validate($request, [
            'status' =>'required|boolean',
        ]);

        $users = User::with('role')->where('status_', $request->status)->get();
        return [
            'success' => true,
            'users' => $users
        ]; 
    }

    function destroy($id){
         $request->request->add(['id' => $id]);
         $this->validate($request, [
              'id'=> 'required|exists:users,id'
         ]);
         $user = User::find($id);
         $user->status_ = 0;
         $user->save();

         return ['success' => true];
    }
}
