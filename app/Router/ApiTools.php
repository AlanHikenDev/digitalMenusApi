<?php

namespace App\Router;

use Illuminate\Support\Facades\Route;

class ApiTools {
  public static $methods = [
    "GET" => [],
    "POST" => [
      "ver"       => "ver",
      "listar"  => "listar",
      "agregar"   => "agregar",
      "actualizar" => "actualizar",
      "paginar"    => "paginar",
    ]
  ];

  /**
   * Simula una funcionalidad parecida a:
   *
   * Route::resource('productos', 'ProductosController');
   */
  public static function restMethods($resource, $controller, $exclude = []) {
    foreach(ApiTools::$methods as $method => $params) {
      foreach($params as $key => $path) {
        if (array_search($key, $exclude) !== false) {
          continue;
        }
        $url = "/${resource}/${path}";
        $handle = "${controller}@${key}";
        $name = "${resource}.${key}";
        if ($method == "GET") {
          Route::get($url, $handle)->name($name);
        } else {
          Route::post($url, $handle)->name($name);;
        }
      }
    }
  }
}
// ejemplo
// Route::resource('photos', 'PhotoController');
// API::genURLs('productos', 'ProductosController');