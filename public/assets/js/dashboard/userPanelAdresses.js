$(document).ready(function () {
    initializeCardStyles();

    $(document).on("click", "#addAddressBtn", function () {
        if (getAddressCount() < 3) {
            showAddressForm();
        } else {
            show_address_error_max();
        }
    });

    $(document).on("click", ".edit-address-btn", function () {
        const addressElement = $(this).closest(".card");
        const addressData = getAddressData(addressElement);
        showAddressForm(addressData, addressElement);
    });

    $(document).on("click", ".delete-address-btn", function () {
        const addressElement = $(this).closest(".card");
        deleteAddress(addressElement);
    });

    $(document).on("click", ".set-primary-btn", function () {
        const addressId = $(this).data('id');
        const url = $(this).data('url');
        makeAddressPrimary(addressId, url);
    });


});

function initializeCardStyles() {
    $(".card").each(function () {
        if ($(this).find(".primary-badge").length > 0) {
            $(this).addClass("border-success shadow-lg").removeClass("border-secondary");
        } else {
            $(this).removeClass("border-success shadow-lg").addClass("border-secondary");
        }
    });
}

function getAddressCount() {
    let count = $("#addressList .inner_address").length;
    $("#addressCount").text(count);
    return count;
}

function show_address_error_max() {
    Swal.fire({
        title: "Warning",
        text: "You have reached the maximum count of addresses (3)",
        icon: "warning",
        background: 'var(--white-color)',
        confirmButtonColor: '#28a745',
        customClass: {
            popup: 'swal-custom-shadow'
        }
    });
}

function showAddressForm(addressData = null) {
    Swal.fire({
        title: `<span class="custom-swal-title">${addressData ? 'Edit Address' : 'Add New Address'}</span>`,
        html: `
            <input type="hidden" id="address_id" value="${addressData?.id || ''}" />
            <input type="text" class="swal2-input" id="street_address" placeholder="Street Address" value="${addressData?.street_address || ''}" />
            <input type="text" class="swal2-input" id="town_city" placeholder="Town / City" value="${addressData?.town_city || ''}" />
            <input type="text" inputmode="numeric" maxlength="11" class="swal2-input" id="phone" placeholder="Phone (Egypt)" value="${addressData?.phone || ''}" />
        `,
        showCancelButton: true,
        confirmButtonText: addressData ? 'Update' : 'Save',
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const data = {
                id: $("#address_id").val().trim(),
                street_address: $("#street_address").val().trim(),
                town_city: $("#town_city").val().trim(),
                phone: $("#phone").val().trim()
            };

            const englishOnly = /^[A-Za-z0-9\s\-.,/#]+$/;
            if (!data.street_address || !englishOnly.test(data.street_address)) return Swal.showValidationMessage("Invalid street address.");
            if (!data.town_city || !englishOnly.test(data.town_city)) return Swal.showValidationMessage("Invalid city.");
            if (!/^01\d{9}$/.test(data.phone)) return Swal.showValidationMessage("Phone must be a valid 11-digit Egyptian number.");

            return data;
        }
    }).then(result => {
        if (result.isConfirmed) {
            result.value.id ? updateAddress(result.value) : addAddress(result.value);
        }
    });
}

function getAddressData($el) {
    return {
        id: $el.data("id"),
        street_address: $el.find(".card-title").text().split(":")[1]?.trim(),
        town_city: $el.find(".card-text").html().split("<br>")[1]?.split(":")[1]?.trim(),
        phone: $el.find(".card-text").html().split("<br>")[3]?.split(":")[1]?.trim()
    };
}

function addAddress(data) {
    const url = $("#addAddressBtn").data("url");

    $.post(url, {
        _token: $('meta[name="csrf-token"]').attr("content"),
        ...data
    }).done(response => {
        Swal.fire({ title: "Success", text: "Address added!", icon: "success", confirmButtonColor: '#28a745' })
            .then(() => window.location.reload());
    }).fail(xhr => {
        Swal.fire({ title: "Error", text: xhr.responseJSON?.message || "An error occurred", icon: "error", confirmButtonColor: '#d33' });
    });
}

function updateAddress(data) {
    $.ajax({
        url: `/addresses/${data.id}`,
        type: "PUT",
        data: { _token: $('meta[name="csrf-token"]').attr("content"), ...data }
    }).done(() => {
        Swal.fire({ title: "Updated!", text: "Address updated.", icon: "success", confirmButtonColor: '#28a745' })
            .then(() => window.location.reload());
    }).fail(xhr => {
        Swal.fire({ title: "Error", text: xhr.responseJSON?.message || "Update failed", icon: "error", confirmButtonColor: '#d33' });
    });
}

function deleteAddress($el) {
    const id = $el.data("id");

    Swal.fire({
        title: "Delete this address?",
        text: "This cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/addresses/${id}`,
                type: "DELETE",
                data: { _token: $('meta[name="csrf-token"]').attr("content") }
            }).done(() => {
                Swal.fire({ title: "Deleted", text: "Address removed.", icon: "success", confirmButtonColor: '#28a745' });
                $el.remove();
                getAddressCount();
            }).fail(xhr => {
                Swal.fire({ title: "Error", text: xhr.responseJSON?.message || "Deletion failed", icon: "error", confirmButtonColor: '#d33' });
            });
        }
    });
}

function makeAddressPrimary(id, url) {
    $.post(url, { _token: $('meta[name="csrf-token"]').attr("content") })
        .done(response => {
            Swal.fire({ title: "Success", text: response.message, icon: "success", confirmButtonColor: '#28a745' })
                .then(() => window.location.reload());
        })
        .fail(xhr => {
            Swal.fire({ title: "Error", text: xhr.responseJSON?.message || "Failed to set as primary", icon: "error", confirmButtonColor: '#d33' });
        });
}
