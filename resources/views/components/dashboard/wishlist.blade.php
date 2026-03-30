<section class="cart__section section--padding">
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
                        <a class="nav-link text-white" href="user-addresses">Addresses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="user-wishlist">Wishlist</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="login">Log Out</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-9 p-4">
                <h4 class="mb-4">Wishlist</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-success">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th class="text-center">Stock Status</th>
                                <th class="text-end">Add to Cart</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img class="border-radius-5 me-2" src="assets/img/product/product1.png" alt="product" style="width: 50px;">
                                        <div>
                                            <h6 class="mb-0">Fresh-whole-fish</h6>
                                            <small>Color: Blue, Weight: 2 Kg</small>
                                        </div>
                                    </div>
                                </td>
                                <td>£65.00</td>
                                <td class="text-center"><span class="badge bg-success">In Stock</span></td>
                                <td class="text-end">
                                    <button class="btn btn-success btn-sm">Add to Cart</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between mt-3">
                        <a class="text-success" href="index.html">Continue shopping</a>
                        <a class="text-success" href="shop.html">View All Products</a>
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