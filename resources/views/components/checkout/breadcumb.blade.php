
<div class="breadcumb-wrapper" data-bg-src="assets/img/check-out/check-out.webp">
    <div class="overlay"></div>
    <div class="container z-index-common">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Checkout</h1>
            <ul class="breadcumb-menu">
                <li><a href="{{url('/')}}">Home</a></li>
                <li class="active">Checkout</li>
            </ul>
        </div>
    </div>
</div>
<style>
    .active {
        color: #258a43 !important;
        font-weight: 800 !important;
    }

    .breadcumb-menu {
        background: none;
    }

    .breadcumb-wrapper {
        position: relative;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 150px 0;

        /* text-align: center; */
    }

    .breadcumb-title {
        background: none;
        color: #fff;
    }

    .breadcumb-wrapper .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        /* Adjust transparency */
        z-index: 1;
    }

    .breadcumb-content {
        position: relative;
        z-index: 2;
    }
</style>
