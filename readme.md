<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Install proyect

Configure your .env file and run the next commands:

-composer install

-php artisan migrate:refresh --seed

-php artisan serve


## Endpoints

User Api Resources

*if all is well all routes return you a ['succes'=>true]

-----------------  ------------------

Store

Description : Store user with role.

Method:Post

Parameters: 'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'names' => 'required|min:2|max:191',
            'paternal_surname' => 'required|min:2|max:191',
            'maternal_surname' => 'min:2|max:191',
            'age' => 'integer',
            'permissions' => 'required|array|between:6,6',
            'role' => ['required',Rule::in(['administrador','operador','desarrollador']),'exists:roles,name']
            
Url:localhost:8000/api/users

-----------------  ------------------

Index 

Description : Return all users.

Method:get

Parameters: none

Url:localhost:8000/api/users

-----------------  ------------------

Update 

Description : Update user's data.

Method:put

Parameters: 'names' => 'min:2|max:191',
            'paternal_surname' => 'min:2|max:191',
            'maternal_surname' => 'min:2|max:191',
            'age' => 'integer',
            'permissions' => 'required_with:role|array|between:6,6',
            'role' => ['required_with:permissions',Rule::in(['administrador','operador','desarrollador']),'exists:roles,name']
            
Url:localhost:8000/api/users/{user_id}

-----------------  -----------------

Show 

Description : Return user by id.

Method:get

Parameters: none

Url:localhost:8000/api/users/{user_id}

-----------------  ------------------

Destroy 

Description : Delete user.

Method:delete

Parameters: none

Url:localhost:8000/api/users/{user_id}

-----------------  ------------------

GetByRole 

Description : Get all users by role.

Method:get

Parameters:  'role' => ['required',Rule::in(['administrador','operador','desarrollador']),'exists:roles,name'],

Url:localhost:8000/api/u/role

-----------------  ------------------

GetByPermission 

Description : Get all users by permission.

Method:get

Parameters:  'permission' => ['required',Rule::in(['create','read','update','delete','copy','activate'])],

Url:localhost:8000/api/u/permission

-----------------  ------------------

GetByStatus 

Description : Get all users by status.

Method:get

Parameters:   'status' =>'required|boolean',

Url:localhost:8000/api/u/status

