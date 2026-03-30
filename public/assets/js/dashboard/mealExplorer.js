// Global state
let currentWeek = null;
let currentDay = null;
let currentMealType = null;

// Mock data structure
//     const mealsData = {
//     1: { // Week 1
//     1: { // Day 1 (Monday)
//     breakfast: {
//     id: 1,
//     name: "Greek Yogurt Parfait",
//     image: "https://images.unsplash.com/photo-1488477181946-6428a0291777?w=300&h=200&fit=crop",
//     calories: 320,
//     protein: 20,
//     carbs: 35,
//     fats: 8,
//     arrived: true
// },
//     snack1: {
//     id: 2,
//     name: "Mixed Nuts",
//     image: "https://images.unsplash.com/photo-1599599810694-57a2ca8276a8?w=300&h=200&fit=crop",
//     calories: 180,
//     protein: 6,
//     carbs: 8,
//     fats: 14,
//     arrived: true
// },
//     lunch: {
//     id: 3,
//     name: "Grilled Chicken Salad",
//     image: "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&h=200&fit=crop",
//     calories: 450,
//     protein: 35,
//     carbs: 12,
//     fats: 28,
//     arrived: false
// },
//     snack2: {
//     id: 4,
//     name: "Apple with Peanut Butter",
//     image: "https://images.unsplash.com/photo-1619546813926-a78fa6372cd2?w=300&h=200&fit=crop",
//     calories: 200,
//     protein: 8,
//     carbs: 20,
//     fats: 12,
//     arrived: false
// },
//     dinner: {
//     id: 5,
//     name: "Salmon with Quinoa",
//     image: "https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=300&h=200&fit=crop",
//     calories: 520,
//     protein: 42,
//     carbs: 35,
//     fats: 22,
//     arrived: false
// }
// },
//     2: { // Day 2 (Tuesday)
//     breakfast: {
//     id: 6,
//     name: "Oatmeal with Berries",
//     image: "https://images.unsplash.com/photo-1517673132405-a56a62b18caf?w=300&h=200&fit=crop",
//     calories: 280,
//     protein: 8,
//     carbs: 52,
//     fats: 4,
//     arrived: true
// },
//     // Add more meals for other types...
// }
//     // Add more days...
// },
//     2: { // Week 2
//     // Add week 2 data...
// }
//     // Add more weeks...
// };
let mealsData;
const dayNames = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

// In public/js/mealExplorer.js
function initializeMealExplorer(meals) {
    // Store the meals data for use in filtering
    // let mealsDatatest = meals;
    mealsData = meals;
    // Example: Log the data to verify it’s received
    console.log("test", meals);

}

// Note: The function is called from the Blade template with the meals data
function selectWeek(weekNumber) {
    currentWeek = weekNumber;
    currentDay = null;
    currentMealType = null;

    // Update active state
    document.querySelectorAll('#weekFilter .filter-pill').forEach(pill => {
        pill.classList.remove('active');
    });
    event.target.classList.add('active');

    // Show day filter
    document.getElementById('weekFilter').classList.add('d-none');
    document.getElementById('dayFilter').classList.remove('d-none');
    document.getElementById('mealTypeFilter').classList.add('d-none');

    // Populate days
    const dayOptions = document.getElementById('dayOptions');
    dayOptions.innerHTML = '';

    for (let i = 1; i <= 7; i++) {
        const dayPill = document.createElement('span');
        dayPill.className = 'filter-pill';
        dayPill.textContent = `${dayNames[i - 1]} (Day ${i})`;
        dayPill.onclick = () => selectDay(i);
        dayOptions.appendChild(dayPill);
    }

    // Update breadcrumb
    updateBreadcrumb([`Week ${weekNumber}`, 'Select Day']);

    // Clear meals display
    showEmptyState('Choose a day to continue');
}

function selectDay(dayNumber) {
    currentDay = dayNumber;
    currentMealType = null;

    // Update active state
    document.querySelectorAll('#dayFilter .filter-pill').forEach(pill => {
        pill.classList.remove('active');
    });
    event.target.classList.add('active');

    // Show meal type filter
    document.getElementById('dayFilter').classList.add('d-none');
    document.getElementById('mealTypeFilter').classList.remove('d-none');

    // Update breadcrumb
    updateBreadcrumb([`Week ${currentWeek}`, `${dayNames[dayNumber - 1]}`, 'Select Meal Type']);

    // Clear meals display
    showEmptyState('Choose a meal type to view meals');
}

function selectMealType(mealType) {
    currentMealType = mealType;

    // Update active state
    document.querySelectorAll('#mealTypeFilter .filter-pill').forEach(pill => {
        pill.classList.remove('active');
    });
    event.target.classList.add('active');

    // Update breadcrumb
    const mealTypeNames = {
        breakfast: 'Breakfast',
        snack1: 'Snack 1',
        lunch: 'Lunch',
        snack2: 'Snack 2',
        dinner: 'Dinner'
    };
    updateBreadcrumb([
        `Week ${currentWeek}`,
        `${dayNames[currentDay - 1]}`,
        mealTypeNames[mealType]
    ]);

    // Display meals
    displayMeals();
}

function displayMeals() {
    const mealsDisplay = document.getElementById('mealsDisplay');

    // Check if data exists
    const weekData = mealsData[currentWeek];
    if (!weekData) {
        showEmptyState('No meals planned for this week yet');
        return;
    }

    const dayData = weekData[currentDay];
    if (!dayData) {
        showEmptyState('No meals planned for this day yet');
        return;
    }

    const meal = dayData[currentMealType];
    if (!meal) {
        showEmptyState('No meal planned for this meal type yet');
        return;
    }

    // Display the meal card
    mealsDisplay.innerHTML = `
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6">
                        <div class="card meal-card h-100 ${meal.arrived ? 'border-success' : 'border-warning'}">
                            <div class="position-relative">
                                <img src="${meal.image}"
                                     class="card-img-top ${meal.arrived ? 'grayscale' : ''}"
                                     alt="${meal.name}"
                                     style="height: 200px; object-fit: cover;">

                                <span class="meal-type-badge badge bg-primary">${currentMealType.charAt(0).toUpperCase() + currentMealType.slice(1)}</span>

                                <span class="delivery-status badge ${meal.arrived ? 'bg-success' : 'bg-warning'}">
                                    ${meal.arrived ? '✓ Arrived' : '📦 In Transit'}
                                </span>

                                ${meal.arrived ? '<div class="arrived-overlay">Delivered ✓</div>' : ''}
                            </div>

                            <div class="card-body text-center">
                                <h5 class="card-title">${meal.name}</h5>

                                <div class="meal-macros d-flex justify-content-center mb-3">
                                    <div class="macros">
                                        <div class="cal">
                                            <div class="num">${meal.calories}</div>
                                            <div class="title">kcal</div>
                                        </div>
                                        <div class="cal">
                                            <div class="num">${meal.fats}</div>
                                            <div class="title">fat</div>
                                        </div>
                                        <div class="cal">
                                            <div class="num">${meal.protein}</div>
                                            <div class="title">protein</div>
                                        </div>
                                        <div class="cal">
                                            <div class="num">${meal.carbs}</div>
                                            <div class="title">carb</div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            `;
}

function backToWeeks() {
    currentDay = null;
    currentMealType = null;

    document.getElementById('weekFilter').classList.remove('d-none');
    document.getElementById('dayFilter').classList.add('d-none');
    document.getElementById('mealTypeFilter').classList.add('d-none');

    updateBreadcrumb(['Select Week']);
    showEmptyState('Select filters above to view meals');
}

function backToDays() {
    currentMealType = null;

    document.getElementById('dayFilter').classList.remove('d-none');
    document.getElementById('mealTypeFilter').classList.add('d-none');

    updateBreadcrumb([`Week ${currentWeek}`, 'Select Day']);
    showEmptyState('Choose a day to continue');
}

function updateBreadcrumb(items) {
    const breadcrumb = document.getElementById('filterBreadcrumb');
    breadcrumb.innerHTML = items.map((item, index) =>
        `<li class="breadcrumb-item ${index === items.length - 1 ? 'active' : ''}">${item}</li>`
    ).join('');
}

function showEmptyState(message) {
    document.getElementById('mealsDisplay').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-utensils"></i>
                    <h5>${message}</h5>
                </div>
            `;
}

function viewMealDetails(mealId) {
    alert('Viewing meal details for meal ID: ' + mealId);
}

function trackDelivery(mealId) {
    alert('Tracking delivery for meal ID: ' + mealId);
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function () {
    showEmptyState('Select filters above to view meals');

});
