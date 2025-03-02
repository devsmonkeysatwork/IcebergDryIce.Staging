<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Requests\VariableRequest;

/**
 * Class VariablesCrudController
 * @package App\Http\Controllers\Admin
 */
class VariablesCrudController extends CrudController
{
  use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

  public function setup()
  {
    CRUD::setModel(\App\Models\Variable::class);
    CRUD::setRoute(config('backpack.base.route_prefix') . '/variables');
    CRUD::setEntityNameStrings('variable', 'variables');
    CRUD::setCreateView('vendor.backpack.crud.variable-create');

  }

  protected function setupListOperation()
  {
    CRUD::column('name');
    CRUD::column('value');
  }

  protected function setupCreateOperation()
  {
    CRUD::setValidation(\App\Http\Requests\VariableRequest::class);

    CRUD::field('name');
    CRUD::field('value');
  }

  protected function setupUpdateOperation()
  {
    $this->setupCreateOperation();
  }
}
