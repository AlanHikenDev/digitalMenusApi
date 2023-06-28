<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
	public function login(Request $request)
    {
			//return $request->all();
        $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);
        if (Auth::attempt($request->only('email', 'password'))) {
          Auth::user()->tokens()->where('name', 'spa_login')->delete();
          $token = Auth::user()->createToken('spa_login');
          return [
            'token' => $token->plainTextToken,
            'user' => Auth::user()
          ];
        }
        throw ValidationException::withMessages([
            'email'   =>  ['Revise que exista esta cuenta'],
            'password' => ['Revise que sus credenciales sean correctas']
        ]);
    }

    public function logout() {
      $user = Auth::user();
      if (!empty($user)) {
        $user->tokens()->where('name', 'spa_login')->delete();
        return ["mensaje" => "Se eliminaron los tokens"];
      }
      Auth::logout();
    }

    public function register(Request $request) {
			$data = $request->all();
			$data['password'] = Hash::make($data['password']);
			$data['created_at'] = "2020-10-10";
			//return $data;
			return User::create($data);
    }
}