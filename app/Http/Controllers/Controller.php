<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

      public function _agregar(Object $model, Array $requestData) {
			$query = $model->newModelQuery();
			return $query->create($requestData);
		}
		
    public function _actualizar(Object $model, Array $requestData) {
			$query = $model->newModelQuery();
			$primary = $model->getKeyName();
			return $query->where($primary,$requestData[$primary])->update($requestData);
		}
    
    public function _ver(Object $model, Array $requestData) {
			$query = $model->newModelQuery();
			$primary = $model->getKeyName();
			$query->findorfail($requestData[$primary]);
			if(isset($requestData['relations']) && count($requestData['relations']) > 0) {
				foreach($requestData['relations'] as $rel) {
					$query->with($rel);
				}
			}
			return $query->first();
		}
		
    public function _listar(Object $model, Array $requestData) {
			$query = $model->newModelQuery();
			if(isset($requestData['conditions']) && count($requestData['conditions']) > 0) {
				foreach($requestData['conditions'] as $cond) {
					if(count($cond) == 2) { // ['id',2]
						$query->where($cond[0],$cond[1]);
					}
					if(count($cond) == 3) { // ['fecha','>=','2020-10-10']
						$query->where($cond[0],$cond[1],$cond[2]);
					}
				}
			}
			if(isset($requestData['relations']) && count($requestData['relations']) > 0) {
				$query->with($requestData['relations']);
			}
			return $query->get();
		}
		
		private function _normalizePaginateData(&$pagineConfig, &$inputData) {
      $pagineConfig = array_replace([
        'valids_sort'    => ['id'],
        'columns_search' => ['id'],
        'per_page'       => 15,
        'columns'        => ['*'],
      ], $pagineConfig);
      if (!isset($pagineConfig['model'])) {
        abort(400, 'Es necesario que se le pase la clase del modelo');
      }
      // 
      $inputData = array_replace([
        "current_page" => 1,
        "per_page"     => $pagineConfig['per_page'],
        "sort_column"  => "id",
        "sort_order"   => "asc",
        "query"        => "",
        "relations"    => [],
        "conditions"    => [] // ejem. [['id','>',25],['created_at','<=','2020-01-01'], ['role','admin'], ...]
      ], $inputData);
    }
		
	public function _paginar($pagineConfig, $inputData) {
			// Normalizando $inputData
      $this->_normalizePaginateData($pagineConfig, $inputData);
      // Cargando el modelo.
      $model = new $pagineConfig['model']();
      $query = $model->newModelQuery();
      // Opciones de busqueda esto si existen un string en el "query" de
      // Nuestro información recibida en nuestro data
      $search = $inputData['query'];
      if (!empty($search)) {
        foreach($pagineConfig['columns_search'] as $cName) {
          $query->orWhere($cName, 'like', '%' . $search . '%');
        }
      }
      // valores de busqueda
      if (isset($inputData['conditions']) && count($inputData['conditions']) > 0) {
        foreach ($inputData['conditions'] as $c) {
          if(count($c) == 2) {
            $query->where($c[0], $c[1]);
          } else if(count($c) == 3) {
            $query->where($c[0], $c[1], $c[2]);
          }
        }// foreach
      }
      // Buscamos relaciones
      if (isset($inputData['relations']) && count($inputData['relations']) > 0) {
        $query->with($inputData['relations']);
      }
      $sql = $query->toSql();
      $data = $query
        // Ordenamos los valores
        ->orderBy(
          $inputData['sort_column'],
          $inputData['sort_order']
        )
        // Realizamos el pagine y enviamos el resultado a un arreglo
        ->paginate(
          $inputData['per_page']      , // $perPage
          $pagineConfig['columns']   , // $columns
          'page'                   , // $pageName
          $inputData['current_page']
        )->toArray();
      // Devuelve un arreglo con estos datos
      return [
        'controls' => [
          'current_page' => $data['current_page'],
          'per_page'     => $data['per_page'],
          'total'        => $data['total'],
          'last_page'    => $data['last_page'],
          'query'        => $inputData['query'],
          'sort_column'  => $inputData['sort_column'],
          'sort_order'   => $inputData['sort_order'],
          'sql'   			 => $sql
        ],
        'data'         => $data['data'],
      ];
	}
}
