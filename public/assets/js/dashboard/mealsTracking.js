//Not Used if we will depend on backend integration instead of JSON

// Meals tracking
// Global variables
// let currentFilterType = 'day';
// let allMeals = [];
// let filteredMeals = [];
//
// // Mock meal data
// const mockMealsData = [
//     {
//         id: 1,
//         name: "Greek Yogurt Parfait with Berries",
//         type: "Breakfast",
//         date: "2025-05-28",
//         image: "https://images.unsplash.com/photo-1488477181946-6428a0291777?w=300&h=200&fit=crop",
//         calories: 320,
//         protein: 20,
//         carbs: 35,
//         fats: 8,
//         completed: true
//     },
//     {
//         id: 2,
//         name: "Grilled Chicken Caesar Salad",
//         type: "Lunch",
//         date: "2025-05-28",
//         image: "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&h=200&fit=crop",
//         calories: 450,
//         protein: 35,
//         carbs: 12,
//         fats: 28,
//         completed: true
//     },
//     {
//         id: 3,
//         name: "Salmon with Quinoa and Vegetables",
//         type: "Dinner",
//         date: "2025-05-28",
//         image: "https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=300&h=200&fit=crop",
//         calories: 520,
//         protein: 42,
//         carbs: 35,
//         fats: 22,
//         completed: false
//     },
//     {
//         id: 4,
//         name: "Mixed Nuts and Dark Chocolate",
//         type: "Snack",
//         date: "2025-05-28",
//         image: "https://images.unsplash.com/photo-1599599810694-57a2ca8276a8?w=300&h=200&fit=crop",
//         calories: 180,
//         protein: 6,
//         carbs: 8,
//         fats: 14,
//         completed: false
//     },
//     {
//         id: 5,
//         name: "Oatmeal with Banana",
//         type: "Breakfast",
//         date: "2025-05-27",
//         image: "https://images.unsplash.com/photo-1517673132405-a56a62b18caf?w=300&h=200&fit=crop",
//         calories: 280,
//         protein: 8,
//         carbs: 52,
//         fats: 4,
//         completed: true
//     },
//     {
//         id: 6,
//         name: "Turkey Sandwich",
//         type: "Lunch",
//         date: "2025-05-27",
//         image: "https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=300&h=200&fit=crop",
//         calories: 380,
//         protein: 25,
//         carbs: 45,
//         fats: 12,
//         completed: true
//     },
//     {
//         id: 7,
//         name: "Vegetable Stir Fry",
//         type: "Dinner",
//         date: "2025-05-26",
//         image: "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&h=200&fit=crop",
//         calories: 350,
//         protein: 12,
//         carbs: 48,
//         fats: 15,
//         completed: true
//     },
//     {
//         id: 8,
//         name: "Protein Smoothie",
//         type: "Snack",
//         date: "2025-05-25",
//         image: "https://images.unsplash.com/photo-1511690743698-d9d85f2fbf38?w=300&h=200&fit=crop",
//         calories: 250,
//         protein: 25,
//         carbs: 20,
//         fats: 8,
//         completed: false
//     }
// ];
//
// // Initialize meal tracking
// document.addEventListener('DOMContentLoaded', function () {
//     loadMealsData();
//     setupEventListeners();
//     filterMeals('day');
// });
//
// // Function to load meals data
// function loadMealsData() {
//     allMeals = mockMealsData;
// }
//
// // Function to set up event listeners for meal tracking
// function setupEventListeners() {
//     document.getElementById('showCompleted').addEventListener('change', function () {
//         renderMealsTable();
//     });
// }
//
// // Function to filter meals by period
// function filterMeals(period) {
//     showLoading(true);
//     currentFilterType = period;
//
//     setTimeout(() => {
//         const today = new Date();
//         let startDate, endDate;
//
//         switch (period) {
//             case 'day':
//                 startDate = new Date(today);
//                 endDate = new Date(today);
//                 document.getElementById('currentFilter').textContent = "Today's";
//                 document.getElementById('dateRange').textContent = formatDate(today);
//                 break;
//
//             case 'week':
//                 const startOfWeek = new Date(today);
//                 startOfWeek.setDate(today.getDate() - today.getDay());
//                 const endOfWeek = new Date(startOfWeek);
//                 endOfWeek.setDate(startOfWeek.getDate() + 6);
//                 startDate = startOfWeek;
//                 endDate = endOfWeek;
//                 document.getElementById('currentFilter').textContent = "This Week's";
//                 document.getElementById('dateRange').textContent =
//                     `${formatDate(startOfWeek)} - ${formatDate(endOfWeek)}`;
//                 break;
//
//             case 'month':
//                 startDate = new Date(today.getFullYear(), today.getMonth(), 1);
//                 endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
//                 document.getElementById('currentFilter').textContent = "This Month's";
//                 document.getElementById('dateRange').textContent =
//                     `${formatDate(startDate)} - ${formatDate(endDate)}`;
//                 break;
//         }
//
//         filteredMeals = allMeals.filter(meal => {
//             const mealDate = new Date(meal.date);
//             return mealDate >= startDate && mealDate <= endDate;
//         });
//
//         updateStats();
//         renderMealsTable();
//         showLoading(false);
//     }, 500);
// }
//
// // Function to update meal statistics
// function updateStats() {
//     const total = filteredMeals.length;
//     const completed = filteredMeals.filter(meal => meal.completed).length;
//     const remaining = total - completed;
//     const progressPercent = total > 0 ? Math.round((completed / total) * 100) : 0;
//
//     document.getElementById('totalMeals').textContent = total;
//     document.getElementById('completedMeals').textContent = completed;
//     document.getElementById('remainingMeals').textContent = remaining;
//     document.getElementById('progressPercentage').textContent = `${progressPercent}%`;
//
//     const progressFill = document.getElementById('progressFill');
//     const progressText = progressFill.querySelector('.progress-text');
//     setTimeout(() => {
//         progressFill.style.width = `${progressPercent}%`;
//         progressText.textContent = `${progressPercent}%`;
//     }, 200);
// }
//
// // Function to render meals table
// function renderMealsTable() {
//     const tbody = document.getElementById('mealsTableBody');
//     const showCompleted = document.getElementById('showCompleted').checked;
//     const emptyState = document.getElementById('emptyState');
//     const table = document.getElementById('mealsTable');
//
//     let mealsToShow = filteredMeals;
//     if (!showCompleted) {
//         mealsToShow = filteredMeals.filter(meal => !meal.completed);
//     }
//
//     if (mealsToShow.length === 0) {
//         table.style.display = 'none';
//         emptyState.style.display = 'block';
//         return;
//     }
//
//     table.style.display = 'table';
//     emptyState.style.display = 'none';
//
//     tbody.innerHTML = mealsToShow.map(meal => `
//     <tr class="meal-row ${meal.completed ? 'completed' : ''}">
//         <td>
//             <div class="text-center">
//                 <div class="fw-bold">${formatDate(new Date(meal.date))}</div>
//                 <small class="text-muted">${new Date(meal.date).toLocaleDateString('en-US', {weekday: 'short'})}</small>
//             </div>
//         </td>
//         <td>
//             <div class="d-flex align-items-center justify-content-center">
//                 <img src="${meal.image}" alt="${meal.name}" class="meal-image me-3">
//                 <div class="text-start">
//                     <div class="meal-name">${meal.name}</div>
//                 </div>
//             </div>
//         </td>
//         <td>
//             <span class="badge bg-light text-dark">${meal.type}</span>
//         </td>
//         <td>
//             <div class="macros-container">
//                 <div class="macro-item">
//                     <div class="macro-value">${meal.protein}g</div>
//                     <div class="macro-label">Pro</div>
//                 </div>
//                 <div class="macro-item">
//                     <div class="macro-value">${meal.carbs}g</div>
//                     <div class="macro-label">Carb</div>
//                 </div>
//                 <div class="macro-item">
//                     <div class="macro-value">${meal.fats}g</div>
//                     <div class="macro-label">Fat</div>
//                 </div>
//             </div>
//         </td>
//         <td>
//             <div class="d-flex align-items-center justify-content-center">
//                 <i class="fas fa-fire text-warning me-1"></i>
//                 <strong>${meal.calories}</strong>
//             </div>
//         </td>
//         <td>
//             <span class="status-badge ${meal.completed ? 'status-completed' : 'status-pending'}">
//                 ${meal.completed ?
//         '<i class="fas fa-check me-1"></i>Completed' :
//         '<i class="fas fa-clock me-1"></i>Pending'
//     }
//             </span>
//         </td>
//         <td>
//             <div class="btn-group btn-group-sm" role="group">
//                 ${!meal.completed ?
//         `<button class="btn btn-outline-success btn-sm" onclick="markAsCompleted(${meal.id})" title="Mark as completed">
//                         <i class="fas fa-check"></i>
//                     </button>` : ''
//     }
//                 <button class="btn btn-outline-primary btn-sm" onclick="viewMealDetails(${meal.id})" title="View details">
//                     <i class="fas fa-eye"></i>
//                 </button>
//             </div>
//         </td>
//     </tr>
// `).join('');
// }
//
// // Utility function to format dates
// function formatDate(date) {
//     return date.toLocaleDateString('en-US', {
//         month: 'short',
//         day: 'numeric',
//         year: 'numeric'
//     });
// }
//
// // Function to show or hide loading spinner
// function showLoading(show) {
//     const spinner = document.getElementById('loadingSpinner');
//     const table = document.getElementById('mealsTable');
//     const emptyState = document.getElementById('emptyState');
//
//     if (show) {
//         spinner.style.display = 'flex';
//         table.style.display = 'none';
//         emptyState.style.display = 'none';
//     } else {
//         spinner.style.display = 'none';
//     }
// }
//
// // Function to mark a meal as completed
// function markAsCompleted(mealId) {
//     const mealIndex = allMeals.findIndex(meal => meal.id === mealId);
//     if (mealIndex !== -1) {
//         allMeals[mealIndex].completed = true;
//         filterMeals(currentFilterType);
//         showToast('Meal marked as completed!', 'success');
//     }
// }
//
// // Function to view meal details
// function viewMealDetails(mealId) {
//     const meal = allMeals.find(meal => meal.id === mealId);
//     if (meal) {
//         alert(`Meal Details:\n\nName: ${meal.name}\nType: ${meal.type}\nCalories: ${meal.calories}\nProtein: ${meal.protein}g\nCarbs: ${meal.carbs}g\nFats: ${meal.fats}g\nStatus: ${meal.completed ? 'Completed' : 'Pending'}`);
//     }
// }
//
// // Function to refresh meal data
// function refreshData() {
//     showLoading(true);
//     setTimeout(() => {
//         loadMealsData();
//         filterMeals(currentFilterType);
//         showToast('Data refreshed successfully!', 'success');
//     }, 1000);
// }
//
// // Function to show toast notifications
// function showToast(message, type) {
//     const toast = document.createElement('div');
//     toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
//     toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; opacity: 0; transition: opacity 0.3s ease;';
//     toast.innerHTML = `
//     <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
//     ${message}
// `;
//     document.body.appendChild(toast);
//     setTimeout(() => toast.style.opacity = '1', 100);
//     setTimeout(() => {
//         toast.style.opacity = '0';
//         setTimeout(() => document.body.removeChild(toast), 300);
//     }, 3000);
// }
