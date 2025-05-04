<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$sql_photographer = $pdo->prepare('SELECT * FROM business_profiles WHERE id = ?');
$sql_photographer->execute([$data['id']]);
$photographer = $sql_photographer->fetch();
if (!$photographer) {
    header('location: ../index.php');
    exit;
}
$sql_date = $pdo->prepare('SELECT date, duration_minutes FROM appointments WHERE photographer_id = ?');
$sql_date->execute([$data['id']]);
$date = $sql_date->fetchAll();
?>
<!-- Hidden input just to attach flatpickr to -->
<div id="appointmentDateTime" style="display:none;"></div>

<!-- Calendar container with modern styling -->
<div class="calendar-container">
    <div class="calendar-header">
        <h1>Availability</h1>
    </div>
    <div id="calendarDisplay" class="form-group">
        <div id="calendar-inline"></div>
    </div>
    <div class="calendar-legend">
        <div class="legend-item">
            <span class="legend-color busy"></span>
            <span class="legend-text">Busy periods</span>
        </div>
        <div class="legend-item">
            <span class="legend-color available"></span>
            <span class="legend-text">Available</span>
        </div>
    </div>
</div>

<!-- FLATPICKR -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Toastify -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
  const busySlots = [
    <?php
    foreach ($date as $row) {
        echo "{ date: '" . $row['date'] . "', duration: " . $row['duration_minutes'] . " },";
    }
    ?>
  ];
  
  const busyRanges = busySlots.map(slot => {
    const start = new Date(slot.date);
    const end = new Date(start.getTime() + slot.duration * 60000);
    return { start, end };
  });
  
  document.addEventListener('DOMContentLoaded', function() {
    flatpickr("#appointmentDateTime", {
      enableTime: false,
      dateFormat: "Y-m-d",
      inline: true,
      appendTo: document.getElementById("calendar-inline"),
      clickOpens: false,
      minDate: "today",
      locale: {
        firstDayOfWeek: 1 // Start week on Monday
      },
      onDayCreate: function (dObj, dStr, fp, dayElem) {
        const date = dayElem.dateObj.toISOString().split('T')[0];
        const slotsForDay = busySlots.filter(slot => slot.date.startsWith(date));
        
        if (slotsForDay.length > 0) {
          dayElem.classList.add("busy-day");
          
          const times = slotsForDay.map(slot => {
            const start = new Date(slot.date);
            const end = new Date(start.getTime() + slot.duration * 60000);
            const format = d => {
              let hours = d.getHours();
              let minutes = d.getMinutes();
              const ampm = hours >= 12 ? 'PM' : 'AM';
              hours = hours % 12;
              hours = hours ? hours : 12;
              minutes = minutes < 10 ? '0' + minutes : minutes;
              return hours + ':' + minutes + ' ' + ampm;
            };
            return `${format(start)} - ${format(end)}`;
          });
          
          const tooltip = document.createElement("div");
          tooltip.className = "custom-tooltip";
          tooltip.innerHTML = "<div class='tooltip-header'>Busy Times</div>" + 
                              times.map(time => `<div class="tooltip-time">${time}</div>`).join("");
          dayElem.appendChild(tooltip);
        }
      }
    });
  });
</script>

<style>
  /* Modern styling for calendar container */
  .calendar-container {
    max-width: 100%;
    width: 100%;
    margin: 0 auto;
    padding: 15px;
    background-color: #ffffff;
    border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
	margin-bottom:20px;
  }
  
  .calendar-header {
    text-align: center;
    margin-bottom: 15px;
  }
  
  .calendar-header h1 {
    color: #333;
    font-weight: 600;
    margin-bottom: 5px;
    font-size: 1.2rem;
  }
  
  .calendar-header p {
    color: #666;
    margin-top: 0;
    font-size: 0.85rem;
  }
  
  /* Flatpickr customization */
  .flatpickr-calendar {
    width: 100% !important;
    max-width: 100%;
    border-radius: 12px;
    box-shadow: none;
    border: none;
    background: transparent;
    margin-bottom: 15px;
    font-size: 0.9rem;
  }
  
  .flatpickr-months {
    background-color: #f8f9fa;
    border-radius: 12px 12px 0 0;
    padding: 8px 0;
  }
  
  .flatpickr-month {
    height: 40px;
  }
  
  .flatpickr-current-month {
    padding-top: 0;
    font-size: 1rem;
    font-weight: 600;
  }
  
  .flatpickr-weekdays {
    background-color: #f8f9fa;
    padding: 5px 0;
  }
  
  span.flatpickr-weekday {
    font-weight: 600;
    color: #555;
    font-size: 0.8rem;
  }
  
  .flatpickr-day {
    box-sizing: border-box !important;
    border-radius: 50% !important;
    margin: 2px !important;
    height: 32px !important;
    width: 32px !important;
    max-width: 32px !important;
    min-width: 32px !important;
    line-height: 32px !important;
    border: none !important;
    transition: all 0.2s ease !important;
    pointer-events: auto !important;
    font-size: 0.85rem !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
    position: relative !important;
    flex: 0 0 32px !important;
	pointer-events: none !important;  
  }
  
  .flatpickr-day:hover {
    background-color: #f0f0f0;
	pointer-events: auto !important;
  }
  
  .flatpickr-day.selected {
    background-color: #4CAF50;
    border-color: #4CAF50;
    color: white;
    font-weight: bold;
  }
  
  .flatpickr-day.today {
    border: 2px solid #4CAF50;
    color: #4CAF50 !important;
    font-weight: bold;
  }
  
  .flatpickr-day.busy-day {
	 pointer-events: auto !important;
    background-color: rgba(255, 76, 76, 0.15);
    color: #ff4c4c;
    border: none;
    position: relative;
    font-weight: 600;
    border-radius: 50%;
  }
  
  .flatpickr-day.busy-day::after {
    content: '';
    position: absolute;
    bottom: 6px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 4px;
    background-color: #ff4c4c;
    border-radius: 50%;
  }
  
  /* Tooltips */
  .custom-tooltip {
    display: none;
    position: absolute;
    bottom: 120%;
    left: 50%;
    transform: translateX(-50%);
    background-color: #fff;
    color: #333;
    padding: 8px;
    font-size: 11px;
    white-space: nowrap;
    border-radius: 8px;
    z-index: 9999;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    min-width: 140px;
    text-align: center;
  }
  
  .tooltip-header {
    font-weight: 600;
    margin-bottom: 3px;
    color: #ff4c4c;
  }
  
  .tooltip-time {
    padding: 2px 0;
    border-bottom: 1px solid #eee;
    font-size: 10px;
  }
  
  .tooltip-time:last-child {
    border-bottom: none;
  }
  
  .flatpickr-day.busy-day:hover .custom-tooltip {
    display: block;
  }
  
  .flatpickr-innerContainer,
  .flatpickr-days,
  .dayContainer,
  .flatpickr-day {
    overflow: visible !important;
    width: 100% !important;
    max-width: none !important;
  }
  
  .dayContainer {
    width: 100% !important;
    min-width: 100% !important;
    max-width: 100% !important;
    display: flex !important;
    flex-wrap: wrap !important;
    justify-content: space-around !important;
    padding: 0 !important;
    outline: 0 !important;
    text-align: center !important;
  }
  
  .flatpickr-days {
    width: 100% !important;
    max-width: 100% !important;
    padding: 0 !important;
  }
  
  .flatpickr-calendar.inline {
    display: block;
    width: 100% !important;
    box-shadow: none !important;
  }
  
  .flatpickr-weekdays, .flatpickr-weekdaycontainer {
    width: 100% !important;
    display: flex !important;
    justify-content: space-around !important;
  }
  
  span.flatpickr-weekday {
    width: 32px !important;
    max-width: 32px !important;
    flex: 0 0 32px !important;
    display: inline-block !important;
    text-align: center !important;
  }
  
  /* Calendar legend */
  .calendar-legend {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #eee;
  }
  
  .legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
  }
  
  .legend-color {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
  }
  
  .legend-color.busy {
    background-color: rgba(255, 76, 76, 0.15);
    border: 1px solid #ff4c4c;
  }
  
  .legend-color.available {
    background-color: white;
    border: 1px solid #ddd;
  }
  
  .legend-text {
    font-size: 11px;
    color: #666;
  }
  
  /* Toastify customization */
  .toastify {
    border-radius: 8px !important;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    font-weight: 500 !important;
  }
  .flatpickr-innerContainer,
  .flatpickr-days,
  .flatpickr-day {
    overflow: visible !important;
	z-index:100;
  }
</style>