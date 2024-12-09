<?php

namespace App\Http\Controllers;

use App\CPU\FileManager;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private const FOLDER_PATH_LOCAL = 'images/users';
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $users = User::where('name', 'like', '%'.$search.'%')->paginate(10);
        return view('users.index', compact('users','search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        if(strlen($request->password) < 8 || strlen($request->password) > 16){
            return back()->with('error', 'La contraseña debe tener entre 8 y 16 caracteres');
        }
        $user = $request->all();
        $user['password'] = bcrypt($request->password);
        //upload image
        $fileImage  = $request->file('avatar');
        if ($fileImage) {
            $url = FileManager::upload($fileImage, $this::FOLDER_PATH_LOCAL);
            if ($url == 'Error33') {
                return back()->with('error', 'Error url!');
            }else {
                $user['avatar'] = $url;
            }
        }
        User::create($user);
        return redirect()->route('users.index')->with('success', 'Usuario creado con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'password' => 'required|string|min:8',
            'password_confirm' => 'required|same:password',
            'role' => 'required|string|in:admin,user',
        ]);

        if(strlen($request->password) < 8 || strlen($request->password) > 16){
            return back()->with('error', 'La contraseña debe tener entre 8 y 16 caracteres');
        }
        $user = User::find($id);
        $user['password'] = bcrypt($request->password);
        $user['name'] = $request->name;
        $user['email'] = $request->email;
        $user['role'] = $request->role;
        //upload image
        $fileImage  = $request->file('avatar');
        if ($fileImage) {
            FileManager::delete($user->avatar, 'key_image');
            $url = FileManager::upload($fileImage, $this::FOLDER_PATH_LOCAL);
            if ($url == 'Error33') {
                return back()->with('error', 'Error url!');
            }
            $user['avatar'] = $url;
        }
        $user->save();
        return redirect()->route('users.index')->with('success', 'Usuario actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //si no existe el usuario, se debe mostrar un mensaje de error
        $user = User::find($id);
        if(!$user){
            return redirect()->route('users.index')->with('error', 'usuario no encontrado');
        }
        FileManager::delete($user->avatar, 'key_image');
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado con éxito');
    }
    // muestra el perfil del usuario autenticado
    public function profile()
    {
        $user = auth()->user();
        return view('users.profile', compact('user'));
    }
}
