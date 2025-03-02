// resources/views/vendor/backpack/crud/variables.blade.php
@extends(backpack_view('blank'))

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6">
      @include('crud::inc.card', [
      'content' => 'Inventory Information',
      'fields' => ['ice_on_hand', 'ice_on_hand_date'],
      ])
      @include('crud::inc.card', [
      'content' => 'Rate Information',
      'fields' => ['sublimation_rate', 'us_exchange'],
      ])
    </div>
    <div class="col-md-6">
      @include('crud::inc.card', [
      'content' => 'Cost Information',
      'fields' => ['border_cost', 'online_box_charge_vancouver', 'online_ice_charge_vancouver', 'shipping_cost_per_tote', 'online_hazmat_cost'],
      ])
      @include('crud::inc.card', [
      'content' => 'Add New Variable',
      'fields' => ['variable_name', 'value'],
      ])
    </div>
  </div>
</div>
@endsection
