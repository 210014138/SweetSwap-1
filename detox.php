<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='utf-8' />
  <meta name='viewport' content='width=device-width, initial-scale=1' />
  <title>Detox Calendar</title>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
  <link rel="stylesheet" href="style.css">
  

</head>
<body>

  <div class="tracker-heading">
  <h2>Track your sugar-detox journey!</h2>
  </div>

  <a href="challenges.php" class="go-back-btn">Go Back</a>

  <!--JavaScript FullCalendar-->
  <div id='calendar'></div>
  <div id="iconModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2>Stayed Away from Sugar?</h2>
    <div class="modal-options">
      <button onclick="handleSelection('sugar-free')">Yes</button>
      <button onclick="handleSelection('not-sugar-free')">No</button>
    </div>
    </div>
  </div>

  <script>

    var selectedDate;

    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        headerToolbar: {
          right: 'prev,next'
        },
        select: function(info) {
          var selectedDate = info.startStr.split('T')[0];
          iconModal.style.display = 'block'; // Show the modal
          iconModal.dataset.selectedDate = selectedDate; 
        }
      });
      calendar.render();
    });

    // Function to handle color selection
    function handleSelection(sugarStatus) {
      var selectedDate = iconModal.dataset.selectedDate;
      var dateElement = document.querySelector(`.fc-day[data-date="${selectedDate}"]`);
      if (dateElement) {
     
        // Add new class based on selection
        dateElement.classList.add(sugarStatus);
      }
      closeModal();
    }

     // Function to close the modal
     function closeModal() {
      iconModal.style.display = 'none'; // Hide the modal
    }
  </script>
</body>
</html>
