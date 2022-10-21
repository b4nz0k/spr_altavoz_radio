<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\Controller;

use App\Providers\RouteServiceProvider;

class UserController extends Controller
{
    use RegistersUsers;
    protected $redirectTo = RouteServiceProvider::HOME;

    public function index()
    {
        try {
            if (auth()->user()->nivel == 4) {
                $usuarios = User::select('id', 'name', 'email', 'nivel', 'estatus', 'estacion_radio_id')
                ->with('estacion_radio');
            }
            else {
                $usuarios = User::select('id', 'name', 'email', 'nivel', 'estatus', 'estacion_radio_id')
                ->where('estacion_radio_id', auth()->user()->estacion_radio_id)
                ->with('estacion_radio');
                $usuarios->attr = '$usuarios->estacion_radio;';

            }


            return response()->json($usuarios->paginate(5));

            } catch (\Throwable $th) {
                return response()->json([
                    'answer' => false,
                    'msg' => 'Ocurrio un error.',
                ]);
        }

    }
    public function search(Request $request) {
        try {
            // return response()->json($request->all());
            $this->validate($request, [
                'search' => 'required'
            ]);

            $se = $request->input('search');
            $busqueda = User::where('name', 'like', '%'.$se.'%')
                                ->orwhere('email', 'like', '%'.$se.'%');
            // return response()->json('asignando variable search');

            return $busqueda->paginate(5);
        } catch (\Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'nivel' => 'required'
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nivel' => $request->nivel
        ]);
        return response()->json([
            'answer' => true,
            'msg' => 'El registro se ha realizado exitosamente.',
        ]);
    }



    public function edit($id)
    {
        if (auth()->user()->nivel > 3) {
            $user = User::find($id);
            return response()->json($user);
        }
        else {
            return response()->json([
                'answer' => false,
                'msg' => 'No tienes Acceso a esta funcion.',
            ]);
        }
    }

    public function update(Request $request)
    {
        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($request->id);
        $user->update($input);

        $user->save();
        // return redirect()->route('usuarios.index');
        return response()->json([
            'answer' => true,
            'msg' => 'El registro se ha actualizado exitosamente.',
        ]);
    }

    public function destroy($id)
    {

        if (User::find($id)->delete()) {
            // return redirect()->route('usuarios.index');
            return response()->json([
                'answer' => true,
                'msg' => 'El registro se ha eliminado exitosamente.',
            ]);
        }
    }
}
