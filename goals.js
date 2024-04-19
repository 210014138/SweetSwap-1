let allGoals = [
  "Eat one green vegetable 🥦",
  "Drink 5 glasses of water 💧",
  "Eat only 1️⃣ sweet treat 🤯",
  "Play outside for at least 30 minutes 🏃",
  "Limit screen time to 1 hour per day 🕛"
];

// Function to populate all goals list
function populateAllGoals() {
  const allGoalsList = document.getElementById('allGoals');
  allGoalsList.innerHTML = '';
  allGoals.forEach(goal => {
      const li = createGoalListItem(goal);
      allGoalsList.appendChild(li);
  });
}

// Function to create a list of goals
function createGoalListItem(goalText) {
  const li = document.createElement('li');
  li.textContent = goalText;
  li.classList.add('goal-item'); 
  li.addEventListener('click', function() {
      highlightGoal(li);
  });
  return li;
}

// Function to highlight a goal
function highlightGoal(goal) {
  if (goal.style.backgroundColor === 'lightgreen') {
      goal.style.backgroundColor = ''; // Reset the background colour if already green
  } else {
      goal.style.backgroundColor = 'lightgreen'; // Set background colour to green
  }
}


// Add all the goals to the page when the page loads
document.addEventListener('DOMContentLoaded', populateAllGoals);
