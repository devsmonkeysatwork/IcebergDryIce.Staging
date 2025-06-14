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

    $this->crud->removeAllButtonsFromStack('line');
    $this->crud->addButtonFromView('line', 'view_button', 'view-button', 'beginning');


  }

  protected function setupCreateOperation()
  {
    CRUD::setValidation(\App\Http\Requests\VariableRequest::class);

    CRUD::field('name');
    CRUD::field('value');
  }

    public function view($id)
    {
        $this->crud->hasAccessOrFail('update'); // or 'view'

        $entry = \App\Models\Variable::findOrFail($id);

        return view('vendor.backpack.crud.variable-edit', [
            'entry' => $entry,
            'crud' => $this->crud,
        ]);
    }

    public function updateFromView($id)
    {
        $this->crud->hasAccessOrFail('update');
        $data = request()->all();

        $entry = \App\Models\Variable::findOrFail($id);
        $entry->update($data);

        \Alert::success('Variable updated successfully.')->flash();
        return redirect()->route('variable.view', $id);
    }

    public function deleteFromView($id)
    {
        $this->crud->hasAccessOrFail('delete');

        \App\Models\Variable::findOrFail($id)->delete();

        \Alert::success('Variable Deleted.')->flash();

        return redirect($this->crud->route);
    }


  protected function setupUpdateOperation()
  {
    $this->setupCreateOperation();
  }
}
