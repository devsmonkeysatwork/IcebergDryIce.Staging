<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="{{ url('/') }}">IcebergDryIce</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">About</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('/services') }}">Services</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Contact</a></li>
      @guest
      <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
      @else
      <li class="nav-item"><a class="nav-link" href="{{ backpack_url() }}">Admin</a></li>
      <!-- <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
          Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li> -->
      @endguest
    </ul>
  </div>
</nav>
