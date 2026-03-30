@foreach ($addresses as $address)
<div class="card my-5 border {{ $address->primary ? 'border-success shadow-lg' : 'border-secondary' }} inner_address"
    data-id="{{ $address->id }}">
    <div class="card-body position-relative">
        <h5 class="card-title">
            Street Address: {{ $address->street_address }}
        </h5>
        <p class="card-text">
{{--            Apartment/Suite/Unit: {{ $address->apartment_suite_unit ?? 'N/A' }}--}}
{{--            <br>--}}
            Town/City: {{ $address->town_city }}
            <br>
{{--            Postcode: {{ $address->postcode }} <br>--}}
            Phone: {{ $address->phone }}
        </p>
        <!-- style="{{ $address->primary ? 'display: none;' : '' }}" -->
        @if($address->primary == 1)
        <span
            class="primary-badge text-success fw-bold position-absolute top-0 end-0 m-3">✅</span>
        @endif
    </div>
`
    <div class="card-footer d-flex justify-content-end">
        <button
            class="btn btn-sm btn-secondary me-2 edit-address-btn border-0 shadow-none"
            data-id="{{ $address->id }}">Edit</button>

        <button
            class="btn btn-sm btn-danger me-2 delete-address-btn border-0 shadow-none"
            data-id="{{ $address->id }}">Delete</button>

        @if(!$address->primary == 1)

        <button
            class="btn btn-sm btn-success border-0 shadow-none set-primary-btn"
            data-id="{{ $address->id }}"
            data-url="{{ route('addresses.makePrimary', $address->id) }}">
            Set as Primary
        </button>

        @endif
    </div>
</div>
@endforeach
