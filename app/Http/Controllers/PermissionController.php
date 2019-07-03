<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

//Importing laravel-permission models
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Session;

class PermissionController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $permissions = Permission::orderBy('id','ASC')->paginate(20);
        return view(env('THEME').'.admin.permissions.index',compact('permissions'))
            ->with('i', ($request->input('page', 1) - 1) * 20);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $permissions = Permission::get(); //Get all roles

        return view(env('THEME') . '.admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $this->validate($request, [
                'name' => 'required|max:40',
            ]);
        } catch (ValidationException $e) {
            dd('store permission error '.$e.' in '.__CLASS__);
        }

        $name = $request['name'];
        $permission = new Permission();
        $permission->name = $name;
        $permission->save();

        return redirect()->route('permissions.index', app()->getLocale())
            ->with('success',
                'Permission'. $permission->name.' added!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($locale, $id) {
        return redirect('permissions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($locale, $id) {
        $permission = Permission::findOrFail($id);
        return view(env('THEME') . '.admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $locale, $id) {
        $permission = Permission::findOrFail($id);
        try {
            $this->validate($request, [
                'name' => 'required|max:40',
            ]);
        } catch (ValidationException $e) {
            dd('update permission error '.$e.' in '.__CLASS__);
        }
        $input = $request->all();
        $permission->fill($input)->save();

        return redirect()->route('permissions.index', app()->getLocale())
            ->with('success',
                'Permission '. $permission->name.' updated!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($locale, $id) {
        $permission = Permission::findOrFail($id);
        //Make it impossible to delete this specific permission
        if ($permission->name == "Administer roles & permissions") {
            return redirect()->route('permissions.index')
                ->with('flash_message',
                    'Cannot delete this Permission!');
        }
        $permission->delete();
        return redirect()->route('permissions.index', app()->getLocale())
            ->with('success',
                'Permission deleted!');

    }
}
