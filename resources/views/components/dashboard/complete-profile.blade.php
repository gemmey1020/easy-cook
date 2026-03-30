<div class="th-checkout-wrapper space-top space-extra-bottom">
    <div class="container">

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="registerForm" action="{{ route('complete-profile.post') }}" method="post" class="woocommerce-checkout mt-40">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="h4">Complete Your Profile</h2>
                    <div class="row">
                        <div class="col-12 form-group">
                            <input type="text" class="form-control" value="{{ old('company_name') }}"
                                name="company_name" placeholder="Your Company Name" required />
                            @if ($errors->has('company_name'))
                                <span class="text-danger">{{ $errors->first('company_name') }}</span>
                            @endif
                        </div>

                        <div class="col-12 form-group">
                            <input type="text" class="form-control" value="{{ old('street_address') }}"
                                name="street_address" placeholder="Street Address" required />
                            @if ($errors->has('street_address'))
                                <span class="text-danger">{{ $errors->first('street_address') }}</span>
                            @endif
                            <input type="text" class="form-control" value="{{ old('apartment_suite_unit') }}"
                                name="apartment_suite_unit" placeholder="Apartment, suite, unit etc. (optional)"
                                required />
                            @if ($errors->has('apartment_suite_unit'))
                                <span class="text-danger">{{ $errors->first('apartment_suite_unit') }}</span>
                            @endif
                        </div>
                        <div class="col-12 form-group">
                            <input type="text" class="form-control" value="{{ old('town_city') }}" name="town_city"
                                placeholder="Town / City" required />
                            @if ($errors->has('town_city'))
                                <span class="text-danger">{{ $errors->first('town_city') }}</span>
                            @endif
                        </div>

                        <div class="col-md-6 form-group">
                            <input
                                type="text"
                                class="form-control"
                                name="postal_code"
                                value="{{ old('postal_code') }}"
                                placeholder="Postal Code (e.g., 12345)"
                                pattern="[0-9]{5}"
                                maxlength="5"
                                inputmode="numeric"
                                title="Egyptian postal code must be exactly 5 digits"
                            />
                            @if ($errors->has('postal_code'))
                                <span class="text-danger">{{ $errors->first('postal_code') }}</span>
                            @endif
                        </div>


                        <div class="col-12 form-group">
                            <button type="submit" class="th-btn rounded-2">Complete Profile</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>


<script>
    document.querySelector('input[name="postal_code"]').addEventListener('input', function(e) {
        let value = e.target.value;
        value = value.replace(/\D/g, '');
        if (value.length > 5) {
            value = value.slice(0, 5);
        }
        e.target.value = value; // Update the input value
        if (!/^\d{5}$/.test(value) && value.length === 5) {
            e.target.setCustomValidity('Please enter a valid 5-digit Egyptian postal code');
        } else {
            e.target.setCustomValidity('');
        }
    });
</script>
