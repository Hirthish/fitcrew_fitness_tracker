/**************************************
 * LOAD FOOD LIST FROM DATABASE
 **************************************/
fetch("api/get_foods.php")
  .then(res => res.json())
  .then(data => {
    const select = document.getElementById("foodSelect");
    select.innerHTML = `<option value="">Select food</option>`;

    data.forEach(item => {
      select.innerHTML += `
        <option value="${item.food_name}">
          ${item.food_name}
        </option>`;
    });
  });


/**************************************
 * ADD FOOD & STORE IN DATABASE
 **************************************/
function addFoodFromDB() {
  const food = document.getElementById("foodSelect").value;
  const grams = Number(document.getElementById("grams").value);

  if (!food || grams <= 0) {
    alert("Select food & enter valid grams");
    return;
  }

  fetch(`api/get_calories.php?food=${encodeURIComponent(food)}`)
    .then(res => res.json())
    .then(data => {

      const calPer100g = Number(data.calories);

      if (isNaN(calPer100g)) {
        alert("Calories not found for this food");
        throw new Error("Calories missing");
      }

      const totalCal = Math.round((calPer100g / 100) * grams);

      return fetch("api/add_calorie_intake.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `food=${encodeURIComponent(food)}&grams=${grams}&calories=${totalCal}`
      });
    })
    .then(res => res.json())
    .then(response => {

      console.log("SERVER:", response);

      if (response.status === "success") {
        loadTodayFoods();
        loadWeeklyCalories();
        updateDailyProgress();

      } else {
        alert("Error: " + (response.msg || "Unknown error"));
      }
    })
    .catch(err => {
      console.error(err);
      alert("Server error. Check console.");
    });
}

function loadWeeklyCalories() {

  fetch("api/get_weekly_calories.php")
    .then(res => res.json())
    .then(data => {

      const list = document.getElementById("weeklyCaloriesList");
      list.innerHTML = "";

      if (!data || data.length === 0) {
        list.innerHTML = `
          <li class="list-group-item text-center">
            No calorie intake logged this week
          </li>`;
        return;
      }

      data.forEach(day => {
        list.innerHTML += `
          <li class="list-group-item d-flex justify-content-between">
            <span>${day.intake_date}</span>
            <strong>${day.total_calories} kcal</strong>
          </li>`;
      });
    });
}


/**************************************
 * CLEAR ALL CALORIES (DATABASE)
 **************************************/
function clearCalories() {
  if (!confirm("Are you sure you want to clear all calorie data?")) return;

  fetch("api/clear_calorie_intake.php")
    .then(res => res.json())
    .then(response => {

      console.log("CLEAR RESPONSE:", response);

      if (response.status === "success") {

        document.getElementById("todayFoodList").innerHTML = `
          <li class="list-group-item text-center text-muted">
            No food added today
          </li>
        `;

        document.getElementById("totalCalories").innerText = 0;

        const bar = document.getElementById("calorieProgress");
        bar.style.width = "0%";
        bar.innerText = "0%";
        bar.className = "progress-bar bg-success";

        loadWeeklyCalories();
      } else {
        alert(response.msg);
      }
    })
    .catch(err => console.error("Clear error:", err));
}


function updateDailyProgress() {

  const DAILY_GOAL = 2200;

  fetch("api/get_today_calories.php")
    .then(res => res.json())
    .then(data => {
      console.log("Updating progress...");

      const total = Number(data.total) || 0;

      // Update text
      document.getElementById("totalCalories").innerText = total;
      document.getElementById("goalText").innerText = DAILY_GOAL;

      // Calculate percent
      const percent = Math.min((total / DAILY_GOAL) * 100, 100);

      // Update progress bar
      const bar = document.getElementById("calorieProgress");
      bar.style.width = percent + "%";
      bar.innerText = Math.round(percent) + "%";

      // Color change
      bar.classList.remove("bg-success", "bg-danger");
      bar.classList.add(percent < 100 ? "bg-success" : "bg-danger");
    })
    .catch(err => console.error("Progress error:", err));
    
}
function loadTodayFoods() {
  fetch("api/get_today_foods.php")
    .then(res => res.json())
    .then(data => {
      const list = document.getElementById("todayFoodList");
      list.innerHTML = "";

      if (!data || data.length === 0) {
        list.innerHTML = `
          <li class="list-group-item text-center text-muted">
            No food added today
          </li>`;
        return;
      }

      data.forEach(item => {
        list.innerHTML += `
          <li class="list-group-item d-flex justify-content-between">
            ${item.food_name} (${item.grams}g)
            <strong>${item.calories} kcal</strong>
          </li>
        `;
      });
    });
}



window.onload = function () {
  loadTodayFoods();
  loadWeeklyCalories();
  updateDailyProgress();
};
