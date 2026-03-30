 <section class="my__account--section section--padding">
 <div class="container my-5">
    <div class="card shadow-sm border-0">
        <div class="row g-0">
            <div class="col-lg-3 bg-success text-white p-4 rounded-start">
                <h4 class="mb-4">My Profile</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white " href="user-dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="user-addresses">Addresses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="user-wishlist">Wishlist</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="login">Log Out</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-9 p-4">
                <h4 class="mb-4">Addresses</h4>
                <button class="btn btn-success mb-4" type="button">
                    Add a new address
                </button>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Default</h5>
                        <p class="card-text">
                            Admin <br />
                            Dhaka <br />
                            Dhaka 12119 <br />
                            Bangladesh
                        </p>
                        <a class="btn btn-outline-success" href="my-account-2">
                            View Addresses (1)
                        </a>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button class="btn btn-sm btn-secondary me-2" type="button">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger" type="button">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
      </section>
      <style>
    body {
        background-color: #f8f9fa;
    }

    .nav-link.active, .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 5px;
    }

    .card {
        border-radius: 15px;
        overflow: hidden;
    }
</style>