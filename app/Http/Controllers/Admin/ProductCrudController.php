<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Product;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');
        CRUD::setCreateView('vendor.backpack.crud.product-create');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.
        $this->crud->removeAllButtonsFromStack('line');
        $this->crud->addButtonFromView('line', 'view_button', 'view-button', 'beginning');


        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }
    public function view($id)
    {
        $this->crud->hasAccessOrFail('update'); // or 'view'

        $entry = \App\Models\Product::findOrFail($id);

        return view('vendor.backpack.crud.product-edit', [
            'entry' => $entry,
            'crud' => $this->crud,
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    public function updateFromView($id)
    {
        $this->crud->hasAccessOrFail('update');
        $data = request()->all();

        $entry = \App\Models\Product::findOrFail($id);
        $entry->update($data);

        \Alert::success('Product updated successfully.')->flash();
        return redirect()->route('products.view', $id);
    }

    public function deleteFromView($id)
    {
        $this->crud->hasAccessOrFail('delete');

        \App\Models\Product::findOrFail($id)->delete();

        \Alert::success('Product Deleted.')->flash();

        return redirect($this->crud->route);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function getAllProducts()
    {
        return response()->json(Product::select('id', 'product_name', 'price', 'unit')->get());
    }


}
