<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
	public function agregar (Request $request) {
		$data = $request->all();
		$data['password'] = Hash::make($data['password']);
		return $this->_agregar(new User(), $data);
	}
	
	public function listar(Request $request) {
		return $this->_listar(new User(), $request->all());
	}
	
	public function actualizar(Request $request) {
		$data = $request->all();
		if(isset($data['password'])) {
			$data['password'] = Hash::make($data['password']);
		}
		return $this->_actualizar(new User(), $data);
	}

	public function getbyId(Request $request) {
		$id = $request->id;
		$user = User::where('id', $id)->first();
       	return $user;
	}

}
