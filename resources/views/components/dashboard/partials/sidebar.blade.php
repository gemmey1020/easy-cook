 <!-- <div class="col-md-3 col-lg-2 p-4 rounded-start">
    <h4 class="mb-4">My Profile</h4>
    <ul class="nav flex-column" id="profile-tabs">
       <a class="nav-link" href="{{ route('user-dashboard.dashboard') }}">Dashboard</a>
<a class="nav-link" href="{{ route('user-dashboard.profile') }}">Profile</a>
<a class="nav-link" href="{{ route('user-dashboard.orders') }}">Order History</a>
<a class="nav-link" href="{{ route('user-dashboard.subscriptions') }}">Subscriptions</a>
<a class="nav-link" href="{{ route('user-dashboard.meals.tracking') }}">Meals Tracking</a>
<a class="nav-link" href="{{ route('user-dashboard.macronutrients') }}">Macronutrients</a>
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link btn btn-link text-danger px-0">Log Out</button>
            </form>
        </li>
    </ul>
</div> -->
<aside class="sidebar">
  <h4 class="mb-4 text-white">My Profile</h4>
  <ul class="nav flex-column" id="profile-tabs">
    <li class="nav-item">
      <a
        href="{{ route('user-dashboard.dashboard') }}"
        class="nav-link {{ request()->routeIs('user-dashboard.dashboard') ? 'active' : '' }}"
      >
        Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a
        href="{{ route('user-dashboard.profile') }}"
        class="nav-link {{ request()->routeIs('user-dashboard.profile') ? 'active' : '' }}"
      >
        Profile
      </a>
    </li>
      <a class="nav-link {{ request()->routeIs('user-dashboard.assessment') ? 'active' : '' }}" href="{{ route('user-dashboard.assessment') }}">My Assessment</a>
      <a class="nav-link {{ request()->routeIs('user-dashboard.orders') ? 'active' : '' }}" href="{{ route('user-dashboard.orders') }}">Order History</a>
<a class="nav-link {{ request()->routeIs('user-dashboard.subscriptions') ? 'active' : '' }}" href="{{ route('user-dashboard.subscriptions') }}">Subscriptions</a>
<a class="nav-link {{ request()->routeIs('user-dashboard.meals.tracking') ? 'active' : '' }}" href="{{ route('user-dashboard.meals.tracking') }}">Meals Tracking</a>
<!-- <a class="nav-link {{ request()->routeIs('user-dashboard.macronutrients') ? 'active' : '' }}" href="{{ route('user-dashboard.macronutrients') }}">Macronutrients</a></form> -->

    <li class="nav-item">
      <form class="d-flex align-items-center  align-items-md-start" method="POST" action="{{ route('logout') }}">
        @csrf
        <button
          type="submit"
          class="nav-link bg-danger w-50 text-white"
          style="border:none;"
        >
          Log Out
        </button>
      </form>
    </li>
  </ul>
</aside>
