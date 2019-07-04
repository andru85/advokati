<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Auth;


class RoleController extends Controller
{
    public $user;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        /*$this->middleware(function ($request, $next) {

            $this->user = Auth::user();
            $id = Auth::id();
            $user = $this->user;
            if(!$user->hasRole('admin')) {
                abort(403);
            }

            if (session_id() == '') {
                @session_start();
            }
            $_SESSION['isLoggedIn'] = Auth::check() ? true : false;
            return $next($request);
        });*/
        //$user = Auth::user();
        //dd($user);
        $this->middleware('permission:role-list');
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::orderBy('id','ASC')->with('permissions')->paginate(20);
        return view(env('THEME').'.admin.roles.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 20);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view(env('THEME').'.admin.roles.create', compact('permission'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|unique:roles,name',
                //'permission' => 'required',
            ]);
        } catch (ValidationException $e) {
            dd('store role error '.$e.' in '.__CLASS__);
        }


        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));


        return redirect()->route('roles.index', app()->getLocale())
            ->with('success', 'Role created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($locale, $id)
    {
        $role = Role::find($id);
        if ($role!=null) {
            $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
                ->where("role_has_permissions.role_id", $id)
                ->get();
            return view(env('THEME') . '.admin.roles.show', compact('role', 'rolePermissions'));
        } else {
            abort(404);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($locale, $id)
    {
        $role = Role::find($id);
        if ($role!=null) {
            $permission = Permission::get();
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
                ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                ->all();

            return view(env('THEME') . '.admin.roles.edit', compact('role', 'permission', 'rolePermissions'));
        } else {
            abort(404);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $locale, $id)
    {

        try {
            $this->validate($request, [
                'name' => 'required|unique:roles,name,'.$id,
                //'permission' => 'required',
            ]);
        } catch (ValidationException $e) {
            dd('update role error '.$e.' in '.__CLASS__);
        }
        //dd($id);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();


        $role->syncPermissions($request->input('permission'));


        return redirect()->route('roles.index', app()->getLocale())
            ->with('success','Role updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($locale, $id)
    {
        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('roles.index', app()->getLocale())
            ->with('success','Role deleted successfully');
    }
}
