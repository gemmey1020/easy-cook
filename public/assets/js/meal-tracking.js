// Initial Variables
let currentFilterType = "day";
let allMeals = [];
let filteredMeals = [];
let currentWeek = null;
let currentDay = null;
let currentMealType = null;
const mealsData = [];
const dayNames = [
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
    "Sunday",
];

// Utility: Format date
function formatDate(date) {
    return date.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric",
    });
}

// Payment Status Alert
let paymentStatus = "{{ session('payment_status') }}";
let authResult = "{{ session('auth_result') }}";

if (paymentStatus === "paid") {
    Swal.fire({
        title: "Payment Successful!",
        text: "Your payment has been successfully processed.",
        icon: "success",
    });
} else if (paymentStatus === "failed") {
    Swal.fire({
        title: "Payment Failed",
        text: "There was an issue processing your payment. Please try again.",
        icon: "error",
    });
}

// Meal Filtering & Rendering Logic
function fetchMealData() {
    return fetch("/data.json")
        .then((response) => {
            if (!response.ok)
                throw new Error(
                    `Failed to fetch meal data: ${response.statusText}`
                );
            return response.json();
        })
        .catch((error) => {
            console.error("Error fetching meal data:", error);
            return null;
        });
}

function loadMealsFromLocalStorage() {
    try {
        const meals = localStorage.getItem("meals");
        if (meals) {
            allMeals = JSON.parse(meals);
        } else {
            allMeals = mockMealsData;
        }
    } catch (error) {
        console.error("Error loading meals from localStorage:", error);
        allMeals = mockMealsData;
    }

    filterMeals(currentFilterType);
}

function observeLocalStorage() {
    window.addEventListener("storage", function (event) {
        if (event.key === "meals") {
            console.log("Local storage 'meals' updated:", event.newValue);
            loadMealsFromLocalStorage();
        }
    });
}

function loadMealsData() {
    allMeals = []; // Back-end source placeholder
}

function setupEventListeners() {
    document
        .getElementById("showCompleted")
        .addEventListener("change", renderMealsTable);
}

function filterMeals(period) {
    showLoading(true);
    currentFilterType = period;

    setTimeout(() => {
        const today = new Date();
        let startDate, endDate;

        switch (period) {
            case "day":
                startDate = endDate = new Date(today);
                document.getElementById("currentFilter").textContent =
                    "Today's";
                document.getElementById("dateRange").textContent =
                    formatDate(today);
                break;
            case "week":
                startDate = new Date(
                    today.setDate(today.getDate() - today.getDay())
                );
                endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + 6);
                document.getElementById("currentFilter").textContent =
                    "This Week's";
                document.getElementById(
                    "dateRange"
                ).textContent = `${formatDate(startDate)} - ${formatDate(
                    endDate
                )}`;
                break;
            case "month":
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                endDate = new Date(
                    today.getFullYear(),
                    today.getMonth() + 1,
                    0
                );
                document.getElementById("currentFilter").textContent =
                    "This Month's";
                document.getElementById(
                    "dateRange"
                ).textContent = `${formatDate(startDate)} - ${formatDate(
                    endDate
                )}`;
                break;
        }

        filteredMeals = allMeals.filter((meal) => {
            const mealDate = new Date(meal.date);
            return mealDate >= startDate && mealDate <= endDate;
        });

        updateStats();
        renderMealsTable();
        showLoading(false);
    }, 500);
}

function updateStats() {
    const total = filteredMeals.length;
    const completed = filteredMeals.filter((meal) => meal.completed).length;
    const remaining = total - completed;
    const progressPercent =
        total > 0 ? Math.round((completed / total) * 100) : 0;

    document.getElementById("totalMeals").textContent = total;
    document.getElementById("completedMeals").textContent = completed;
    document.getElementById("remainingMeals").textContent = remaining;
    document.getElementById(
        "progressPercentage"
    ).textContent = `${progressPercent}%`;

    const progressFill = document.getElementById("progressFill");
    const progressText = progressFill.querySelector(".progress-text");
    setTimeout(() => {
        progressFill.style.width = `${progressPercent}%`;
        progressText.textContent = `${progressPercent}%`;
    }, 200);
}

function renderMealsTable() {
    const tbody = document.getElementById("mealsTableBody");
    const showCompleted = document.getElementById("showCompleted").checked;
    const emptyState = document.getElementById("emptyState");
    const table = document.getElementById("mealsTable");

    let mealsToShow = showCompleted
        ? filteredMeals
        : filteredMeals.filter((meal) => !meal.completed);

    if (mealsToShow.length === 0) {
        table.style.display = "none";
        emptyState.style.display = "block";
        return;
    }

    table.style.display = "table";
    emptyState.style.display = "none";

    tbody.innerHTML = mealsToShow
        .map(
            (meal) => `
        <tr class="meal-row ${meal.completed ? "completed" : ""}">
            <td>
                <div class="text-center">
                    <div class="fw-bold">${formatDate(
                        new Date(meal.date)
                    )}</div>
                    <small class="text-muted">${new Date(
                        meal.date
                    ).toLocaleDateString("en-US", { weekday: "short" })}</small>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center justify-content-center">
                    <img src="${meal.image}" alt="${
                meal.name
            }" class="meal-image me-3">
                    <div class="text-start">
                        <div class="meal-name">${meal.name}</div>
                    </div>
                </div>
            </td>
            <td><span class="badge bg-light ">${meal.type}</span></td>
            <td>
                <div class="macros-container">
                    <div class="macro-item"><div class="macro-value">${
                        meal.protein
                    }g</div><div class="macro-label">Pro</div></div>
                    <div class="macro-item"><div class="macro-value">${
                        meal.carbs
                    }g</div><div class="macro-label">Carb</div></div>
                    <div class="macro-item"><div class="macro-value">${
                        meal.fats
                    }g</div><div class="macro-label">Fat</div></div>
                </div>
            </td>
            <td><div class="d-flex align-items-center justify-content-center"><i class="fas fa-fire text-warning me-1"></i><strong>${
                meal.calories
            }</strong></div></td>
            <td><span class="status-badge ${
                meal.completed ? "status-completed" : "status-pending"
            }">${
                meal.completed
                    ? '<i class="fas fa-check me-1"></i>Completed'
                    : '<i class="fas fa-clock me-1"></i>Pending'
            }</span></td>
            <td>
                <div class="btn-group btn-group-sm" role="group">
                    ${
                        !meal.completed
                            ? `<button class="btn btn-outline-success btn-sm" onclick="markAsCompleted(${meal.id})"><i class="fas fa-check"></i></button>`
                            : ""
                    }
                    <button class="btn btn-outline-primary btn-sm" onclick="viewMealDetails(${
                        meal.id
                    })"><i class="fas fa-eye"></i></button>
                </div>
            </td>
        </tr>
    `
        )
        .join("");
}

function markAsCompleted(mealId) {
    const mealIndex = allMeals.findIndex((meal) => meal.id === mealId);
    if (mealIndex !== -1) {
        allMeals[mealIndex].completed = true;
        filterMeals(currentFilterType);
        showToast("Meal marked as completed!", "success");
    }
}

function viewMealDetails(mealId) {
    const meal = allMeals.find((meal) => meal.id === mealId);
    if (meal) {
        alert(
            `Meal Details:\n\nName: ${meal.name}\nType: ${
                meal.type
            }\nCalories: ${meal.calories}\nProtein: ${meal.protein}g\nCarbs: ${
                meal.carbs
            }g\nFats: ${meal.fats}g\nStatus: ${
                meal.completed ? "Completed" : "Pending"
            }`
        );
    }
}

function refreshData() {
    showLoading(true);
    setTimeout(() => {
        loadMealsData();
        filterMeals(currentFilterType);
        showToast("Data refreshed successfully!", "success");
    }, 1000);
}

// Weekly Filter Logic
function selectWeek(weekNumber) {
    currentWeek = weekNumber;
    currentDay = null;
    currentMealType = null;

    document
        .querySelectorAll("#weekFilter .filter-pill")
        .forEach((pill) => pill.classList.remove("active"));
    event.target.classList.add("active");

    document.getElementById("weekFilter").classList.add("d-none");
    document.getElementById("dayFilter").classList.remove("d-none");
    document.getElementById("mealTypeFilter").classList.add("d-none");

    const dayOptions = document.getElementById("dayOptions");
    dayOptions.innerHTML = "";
    for (let i = 1; i <= 7; i++) {
        const dayPill = document.createElement("span");
        dayPill.className = "filter-pill";
        dayPill.textContent = `${dayNames[i - 1]} (Day ${i})`;
        dayPill.onclick = () => selectDay(i);
        dayOptions.appendChild(dayPill);
    }

    updateBreadcrumb([`Week ${weekNumber}`, "Select Day"]);
    showEmptyState("Choose a day to continue");
}

function selectDay(dayNumber) {
    currentDay = dayNumber;
    currentMealType = null;

    document
        .querySelectorAll("#dayFilter .filter-pill")
        .forEach((pill) => pill.classList.remove("active"));
    event.target.classList.add("active");

    document.getElementById("dayFilter").classList.add("d-none");
    document.getElementById("mealTypeFilter").classList.remove("d-none");

    updateBreadcrumb([
        `Week ${currentWeek}`,
        `${dayNames[dayNumber - 1]}`,
        "Select Meal Type",
    ]);
    showEmptyState("Choose a meal type to view meals");
}

function selectMealType(mealType) {
    currentMealType = mealType;

    document
        .querySelectorAll("#mealTypeFilter .filter-pill")
        .forEach((pill) => pill.classList.remove("active"));
    event.target.classList.add("active");

    const mealTypeNames = {
        breakfast: "Breakfast",
        snack1: "Snack 1",
        lunch: "Lunch",
        snack2: "Snack 2",
        dinner: "Dinner",
    };

    updateBreadcrumb([
        `Week ${currentWeek}`,
        `${dayNames[currentDay - 1]}`,
        mealTypeNames[mealType],
    ]);
    displayMeals();
}

function displayMeals() {
    const mealsDisplay = document.getElementById("mealsDisplay");
    const weekData = mealsData[currentWeek];

    if (
        !weekData ||
        !weekData[currentDay] ||
        !weekData[currentDay][currentMealType]
    ) {
        showEmptyState("No meal planned for this selection");
        return;
    }

    const meal = weekData[currentDay][currentMealType];

    mealsDisplay.innerHTML = `
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="card meal-card h-100 ${
                    meal.arrived ? "border-success" : "border-warning"
                }">
                    <div class="position-relative">
                        <img src="${meal.image}" class="card-img-top ${
        meal.arrived ? "grayscale" : ""
    }" alt="${meal.name}" style="height: 200px; object-fit: cover;">
                        <span class="meal-type-badge badge bg-primary">${currentMealType}</span>
                        <span class="delivery-status badge ${
                            meal.arrived ? "bg-success" : "bg-warning"
                        }">${
        meal.arrived ? "✓ Arrived" : "📦 In Transit"
    }</span>
                        ${
                            meal.arrived
                                ? '<div class="arrived-overlay">Delivered ✓</div>'
                                : ""
                        }
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">${meal.name}</h5>
                        <div class="meal-macros d-flex justify-content-center mb-3">
                            <div class="macros">
                                <div class="cal"><div class="num">${
                                    meal.calories
                                }</div><div class="title">kcal</div></div>
                                <div class="cal"><div class="num">${
                                    meal.fats
                                }</div><div class="title">fat</div></div>
                                <div class="cal"><div class="num">${
                                    meal.protein
                                }</div><div class="title">protein</div></div>
                                <div class="cal"><div class="num">${
                                    meal.carbs
                                }</div><div class="title">carb</div></div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-outline-success btn-sm" onclick="viewMealDetails(${
                                meal.id
                            })"><i class="fas fa-eye me-1"></i>View Details</button>
                            ${
                                !meal.arrived
                                    ? `<button class="btn btn-outline-info btn-sm" onclick="trackDelivery(${meal.id})"><i class="fas fa-truck me-1"></i>Track</button>`
                                    : ""
                            }
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
    document.getElementById("weekFilter").classList.remove("d-none");
    document.getElementById("dayFilter").classList.add("d-none");
    document.getElementById("mealTypeFilter").classList.add("d-none");
    updateBreadcrumb(["Select Week"]);
    showEmptyState("Select filters above to view meals");
}

function backToDays() {
    currentMealType = null;
    document.getElementById("dayFilter").classList.remove("d-none");
    document.getElementById("mealTypeFilter").classList.add("d-none");
    updateBreadcrumb([`Week ${currentWeek}`, "Select Day"]);
    showEmptyState("Choose a day to continue");
}

function updateBreadcrumb(items) {
    const breadcrumb = document.getElementById("filterBreadcrumb");
    breadcrumb.innerHTML = items
        .map(
            (item, index) =>
                `<li class="breadcrumb-item ${
                    index === items.length - 1 ? "active" : ""
                }">${item}</li>`
        )
        .join("");
}

// function showEmptyState(message) {
//     document.getElementById("mealsDisplay").innerHTML = `
//         <div class="empty-state">
//             <i class="fas fa-utensils"></i>
//             <h5>${message}</h5>
//         </div>
//     `;
// }

function trackDelivery(mealId) {
    alert("Tracking delivery for meal ID: " + mealId);
}

// Init Page
document.addEventListener("DOMContentLoaded", function () {
    showEmptyState("Select filters above to view meals");
    loadMealsData();
    setupEventListeners();
    filterMeals("day");
});
