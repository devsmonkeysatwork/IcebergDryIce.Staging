@extends(backpack_view('blank'))

@section('content')
<div class="container">
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-6 mb-3">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Month</h5>
              <p class="card-text">1,000 lbs<br>IN VOLUME</p>
              <p class="card-text">$30,000<br>IN SALES</p>
              <p class="card-text">$25,000<br>IN REVENUE</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Today</h5>
              <p class="card-text">50 lbs<br>IN VOLUME</p>
              <p class="card-text">$1,500<br>IN SALES</p>
              <p class="card-text">$1,200<br>IN REVENUE</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">On Hand</h5>
          <p class="card-text">1,405 lbs<br>DRY ICE ON HAND</p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">To Be Received</h5>
          <p class="card-text">1,200 lbs<br>DRY ICE TO BE RECEIVED</p>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Date</th>
            <th scope="col">Start of Day</th>
            <th scope="col">Warehouse Orders</th>
            <th scope="col">Praxair Supply Orders</th>
            <th scope="col">Online Orders</th>
            <th scope="col">To be Received</th>
            <th scope="col">End of Day</th>
            <th scope="col">Sublimation</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>07/10/2024</td>
            <td>1405</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>1405</td>
            <td>56</td>
          </tr>
          <tr>
            <td>07/09/2024</td>
            <td>1349</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>1349</td>
            <td>54</td>
          </tr>
          <tr>
            <td>07/08/2024</td>
            <td>1295</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>1295</td>
            <td>52</td>
          </tr>
          <tr>
            <td>07/07/2024</td>
            <td>1243</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>1243</td>
            <td>50</td>
          </tr>
          <tr>
            <td>07/06/2024</td>
            <td>1193</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>1193</td>
            <td>48</td>
          </tr>
          <tr>
            <td>07/05/2024</td>
            <td>1145</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>1145</td>
            <td>46</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
