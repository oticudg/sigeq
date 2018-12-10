<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ { UserStoreRequest, UserUpdateRequest, ChangePasswordRequest };
use App\User;
use App\Models\{ Specialty };
use App\Models\Permisologia\Role;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('onlyAjax')->except(['initWithOneUser']);
        // $this->middleware('can:user,index')->only(['index', 'dataForRegister']);
        // $this->middleware('can:user,show')->only(['show']);
        // $this->middleware('can:user,destroy')->only(['destroy']);
        // $this->middleware('can:user,initWithOneUser')->only(['initWithOneUser']);
    }

    /**
     * Muestra una lista de recursos en json o la vista.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::dataForPaginate();

        $users->each(function ($u) {
            $rol = '';
            foreach ($u->roles as $r) {
                $rol .= '<span class="badge">' . $r->name . '</span>';
            }
            $u->rol = $rol;
            $u->fullName = $u->fullName();
        });

        return $this->dataWithPagination($users);
    }

    /**
     * Almacena un recurso recién creado en el almacenamiento.
     *
     * @param  \App\Http\Requests\UserStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $data['roles'] = $this->idsOfRol($data['roles']);
        $user = new User($data);
        $user->password = bcrypt($data['password']);
        $user->save();
        $user->roles()->attach($data['roles']);
        $user->assignPermissionsOneUser($data['roles']);
        $user->update_pivot($data['speciality_id'], 'specialties', 'specialty_id');
        return response()->json($user);
    }

    /**
     * Mostrar el recurso especificado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $user->fullName = $user->fullName();
        $user->roles->pluck('name')->toArray();
        $specialties = $user->specialties->each(function ($s) {
            $s->text = $s->intervention->name . ' - ' . $s->name;
        })->pluck('text')->toArray();
        unset($user->specialties);
        $user->specialities = $specialties;
        return response()->json($user);
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     *
     * @param  \App\Http\Requests\UserUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        if($request->id == 1) return response(['errors' => 'Error al modificar usuario'], 422);

        $data = $request->validated();
        $data['roles'] = $this->idsOfRol($data['roles']);

        if( !empty($request->password) ){
            $data['password'] = bcrypt($this->validate($request, [
                'password' => 'string|min:6|confirmed'
            ])['password']);
        }

        $user = User::findOrFail($id)->fill($data);
        $user->update_pivot($data['roles'], 'roles', 'role_id');
        $user->update_pivot($data['speciality_id'], 'specialties', 'specialty_id');
        $user->assignPermissionsOneUser($data['roles']);

        return response()->json($user->save());
    }

    /**
     * Elimina el recurso especificado del almacenamiento.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id === 1) return response(['msg' => 'Error al modificar usuario'], 422);
        $user = User::findOrFail($id)->delete();
        return response()->json($user);
    }

    /**
     * se inicia la session con un recurso especificado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function initWithOneUser($id)
    // {
    //     if ($id == 1 || \Auth::user()->id != 1) return abort(401, 'Unauthorized.');
    //     \Auth::loginUsingId($id);
    //     return redirect()->to('/');
    // }

    /**
     * Retorna los datos que se usaran para crear y editar.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataForRegister()
    {
        $roles = Role::all()->pluck('name');
        $specialties = Specialty::get(['id', 'name as text', 'intervention_id']);
        $specialties->each(function ($s) {
            $s->text = $s->intervention->name . ' - ' . $s->text;
            unset($s->intervention, $s->intervention_id);
        });
        return response()->json(compact('roles', 'specialties'));
    }

}
