// Event listener for opening subscription details modal
$(document).on('click', '.subscription-details-btn', function () {
    const subscriptionId = $(this).data('subscription-id');
    openSubscriptionDetails(subscriptionId);
});



// Show Subscription Modal
function openSubscriptionDetails(subscriptionId) {
    $('#subscriptionModal').modal('show');
    console.log(`Opening details for subscription ID: ${subscriptionId}`);

    loadSubscriptionDetails(subscriptionId);
}

// Load subscription details (mocked with timeout)
function loadSubscriptionDetails(subscriptionId) {
    const modalContent = $('#subscription-details-content');
    modalContent.html(`
        <div class="text-center py-5">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading subscription details...</p>
        </div>
    `);

    setTimeout(() => {
        const subscriptionDetails = {
            id: subscriptionId,
            name: "Premium Fitness Plan",
            description: "Complete nutrition plan with workout guidance and personal coaching support",
            status: "active",
            progress: 65,
            startDate: "2024-01-15",
            endDate: "2024-04-15",
            totalMeals: 90,
            completedMeals: 58,
            price: "$299",
            calories: 2200,
            protein: "150g",
            carbs: "250g",
            fats: "80g",
            image: "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&h=400&fit=crop"
        };
        displaySubscriptionDetails(subscriptionDetails);
    }, 800);
}

// Display subscription details content
function displaySubscriptionDetails(details) {
    const content = `...`; // 🔁 Use full HTML string from your original code here

    $('#subscription-details-content').html(content);

    setTimeout(() => {
        loadSubscriptionMeals(details.id);
        loadMealCalendar(details.id);
    }, 500);
}

// Load subscription meals (mock data)
function loadSubscriptionMeals(subscriptionId) {
    const meals = []; // 🔁 Use your original `meals` array here

    const mealsHTML = meals.map(meal => `...`).join(''); // Use your card layout

    $('#subscription-meals').html(`<div class="row g-3">${mealsHTML}</div>`);
}

// Load meal calendar view
function loadMealCalendar(subscriptionId) {
    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];

    const mealData = { ... }; // 🔁 Use your meal date mapping here

    let calendarHTML = `...`; // 🔁 Build calendar rows (as per your original code)

    $('#meal-calendar').html(calendarHTML);
}

// Show extra details in SweetAlert
function showMoreDetails() {
    Swal.fire({
        title: 'Subscription Details',
        html: `...`, // 🔁 Use your modal content here
        confirmButtonColor: '#28a745',
        confirmButtonText: 'Got it!'
    });
}
