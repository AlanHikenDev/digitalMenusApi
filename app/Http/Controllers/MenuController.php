<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    //
      public function listar (Request $request) {
			return $this->_listar(new Menu(), $request->all());
		}
		
		public function ver (Request $request) {
			return $this->_ver(new Menu(), $request->all());
		}
		
		public function agregar(Request $request) {
			$archivo = $this->moverArchivo($request->data_img);
			$request["data_img"] = $archivo["path"];
			$request["videos"] = $archivo["url"]; 
			return $this->_agregar(new Menu(), $request->all());
		}
		
    public function actualizar(Request $request ) {
			return $this->_actualizar(new Menu(), $request->all());
		}
		
	public function paginar(Request $request) {
			$pagineConfig = [
				'valids_sort'    => ['id','estado', 'municipio', 'precio_renta', 'precio_venta'],
				'columns_search' => ['id','estado', 'municipio', 'precio_renta', 'precio_venta'],
				'per_page'       => 15,
				'columns'        => ['*'],
				'model'          => Menu::class
			];
			return $this->_paginar(
				$pagineConfig,
        $request->all()
			);
	}
	
	public function buscarporurl(Request $request){
		//$req = "".$request;
	   //$imagen = DB::table('menus')->where('url_dir', 'VTM86Eq9gc');
	   $imagen = Menu::where('url_dir', $request->url_buscar)->first();
       return $imagen;
	}
	public function buscarporidusr(Request $request){
		$id = $request->user_id;
	   //$imagen = DB::table('menus')->where('url_dir', 'VTM86Eq9gc');
	   $menus = Menu::where('user_id', $id)->get();
       return $menus;
	}

	private function moverArchivo($file) {
			// se debe crear una carpeta public/imagenes
			$nombre = $file->hashName();
			$nuevoPath = public_path('imagenes').'/'.$nombre;
			$file->move(public_path('imagenes'),$nombre);
			return ["path" => $nuevoPath, "url" => url("imagenes/".$nombre)];
	}

	public function buscarporidMenu(Request $request){
		$id = $request->id;
	   //$imagen = DB::table('menus')->where('url_dir', 'VTM86Eq9gc');
	   $menu = Menu::where('id', $request->menuidr)->first();
       return $menu;
	}

	public function imagenupdate(Request $request){
		$archivo = $this->moverArchivo($request->data_img);
			//$request["data_img"] = $archivo["path"];
			//$request["videos"] = $archivo["url"]; 

	   $id = $request->id;
	   //$imagen = DB::table('menus')->where('url_dir', 'VTM86Eq9gc');
	   $menu = Menu::where('id', $id)->first();
	   $menu->data_img = $archivo["path"];
	   $menu->texto = $request->texto;
	   $menu->videos = $archivo["url"]; 
	   $menu->update();
	}
	
}
