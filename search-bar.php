<style>
.search-container {
  width: 100%;
  position: relative;
  max-width: 850px;
  padding: 0 20px;
  margin: 0 auto;
}

.search-bar-wrapper {
  position: relative;
  width: 100%;
  height: 46px;
  border-radius: 23px;
  background-color: rgba(90, 90, 90, 0.2);
  backdrop-filter: blur(3px);
  display: flex;
  align-items: center;
  padding: 0 15px;
  gap: 10px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.search-bar-wrapper:hover {
  background-color: rgba(150, 150, 150, 0.25);
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
}

.search-bar-wrapper:focus-within {
  background-color: rgba(150, 150, 150, 0.3);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  transform: translateY(-1px);
}

.search-icon {
  color: white;
  width: 20px;
  height: 20px;
  margin-right: 10px;
  opacity: 0.9;
  transition: all 0.3s ease;
}

.search-bar-wrapper:focus-within .search-icon {
  opacity: 1;
  transform: scale(1.05);
}

.search-input {
  background: transparent;
  border: none;
  height: 100%;
  width: 100%;
  color: white;
  font-size: 16px;
  padding: 0;
  transition: all 0.3s ease;
}

.search-input::placeholder {
  color: rgba(255, 255, 255, 1);
  transition: color 0.3s ease;
}

.search-bar-wrapper:hover .search-input::placeholder {
  color: rgba(230, 230, 230, 0.9);
}

.search-bar-wrapper:focus-within .search-input::placeholder {
  color: rgba(10, 10, 10, 1);
}

.search-input:focus {
  outline: none;
}

/* Adding a pulsing animation when clicked */
@keyframes pulse {
  0% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4); }
  70% { box-shadow: 0 0 0 6px rgba(255, 255, 255, 0); }
  100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
}

.search-bar-wrapper:active {
  animation: pulse 0.8s;
}

/* Modified dropdown styles to integrate better */
.search-dropdown {
  position: relative;
  margin-right: 5px;
  height: 100%;
  display: flex;
  align-items: center;
}

.search-dropdown-btn {
  background: transparent;
  color: #000;
  padding: 4px 12px;
  font-size: 15px;
  border: none;
  border-radius: 16px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: background 0.3s;
}

.search-dropdown-btn:hover {
  background: rgba(255, 255, 255, 0.0);
}

.arrow {
  width: 7px;
  height: 7px;
  border-left: 2px solid white;
  border-bottom: 2px solid white;
  transform: rotate(-45deg);
  transition: transform 0.3s ease;
  margin-left: 2px;
}

.search-dropdown.active .arrow {
  transform: rotate(135deg);
}

.search-dropdown-content {
  position: absolute;
  top: 100%;
  left: 0;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(8px);
  border-radius: 10px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  z-index: 10;
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all 0.3s ease;
  min-width: 160px;
  margin-top: 5px;
}

.search-dropdown-content a {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 16px;
  text-decoration: none;
  color: #111;
  font-weight: 500;
  transition: background 0.3s;
}

.search-dropdown-content a:hover {
  background: rgba(150, 150, 150, 0.2);
}

.search-dropdown.active .search-dropdown-content {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

/* Add divider between dropdown and search input */
.divider {
  height: 26px;
  width: 1px;
  background: rgba(0, 0, 0, 0.2);
  margin: 0 5px;
}

/* Make icons consistent */
.feather {
  width: 16px;
  height: 16px;
}







/* Suggestion List Styles */
.suggestion-list {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
  padding: 0;
  margin-top: 6px;
  z-index: 1000;
  max-height: 250px;
  overflow-y: auto;
  border: 1px solid #e0e0e0;
}

.suggestion-list li {
  display: flex;
  align-items: center;
  padding: 12px 16px;
  font-size: 15px;
  cursor: pointer;
  transition: background-color 0.2s ease;
  color: #333;
  justify-content:space-between;
}

.suggestion-list li:hover {
  background-color: #f5f5f5;
}

.suggestion-list li a {
  text-decoration: none;
  color: inherit;
  
}




</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="container mt-5">
  <div class="search-container">
    <form id="searchForm" method="GET" action="">
      <div class="search-bar-wrapper">
        <!-- Dropdown -->
        <div class="search-dropdown" id="dropdown">
          <button type="button" class="search-dropdown-btn" onclick="toggleDropdown()">
            <span id="selected-option" style = "color:white;">
              <i data-feather="image"></i> Photos
            </span>
            <span class="arrow"></span>
          </button>
          <div class="search-dropdown-content">
            <a href="#" onclick="selectOption('photos', 'image')"><i data-feather="image"></i> Photos</a>
            <a href="#" onclick="selectOption('photographers', 'user')"><i data-feather="user"></i> Photographers</a>
          </div>
        </div>

        <!-- Divider -->
        <div class="divider"></div>

        <!-- Hidden input to store the selected type -->
        <input type="hidden" name="type" id="search-type" value="photos">

        <!-- Search Icon -->
        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>

        <!-- Search Input -->
        <input type="text" name="query" class="search-input" placeholder="What are you looking for?" value="<?= $_GET['query'] ?? '' ?>">
		<!-- Photographer Suggestions -->
		<ul id="photographer-suggestions" class="suggestion-list" style="display:none; position:absolute; z-index:1000;left:250px;top:50px; background:#fff; border:1px solid #ccc; width:45%; list-style:none; margin:0; padding:0; max-height: 200px; overflow-y: auto;"></ul>

      </div>
    </form>
  </div>
</div>

<!-- Feather Icons -->
<script src="https://unpkg.com/feather-icons"></script>

<script>
  function toggleDropdown() {
    document.getElementById("dropdown").classList.toggle("active");
  }

  function selectOption(option, icon) {
    const capitalized = option.charAt(0).toUpperCase() + option.slice(1);
    document.getElementById("selected-option").innerHTML = `<i data-feather="${icon}"></i> ${capitalized}`;
    document.getElementById("search-type").value = option.toLowerCase();
    document.getElementById("dropdown").classList.remove("active");
    feather.replace(); // Re-render icons
  }

  window.addEventListener("click", function (e) {
    const dropdown = document.getElementById("dropdown");
    if (!dropdown.contains(e.target)) {
      dropdown.classList.remove("active");
    }
  });

  // Change form action based on selected type
  document.getElementById("searchForm").addEventListener("submit", function (e) {
    const type = document.getElementById("search-type").value.toLowerCase();
    if (type === "photos") {
      this.action = "searching_photos.php";
    } else if (type === "photographers") {
      this.action = "photographers.php";
    }
  });

  // Initial icon rendering
  feather.replace();
</script>



<script>
  const searchInput = document.querySelector(".search-input");
  const searchTypeInput = document.getElementById("search-type");
  const suggestionsBox = document.getElementById("photographer-suggestions");

  searchInput.addEventListener("input", function () {
    const query = this.value.trim();
    const type = searchTypeInput.value;

    if (query.length > 0  && type === "photographers") {
      $.ajax({
        url: "searching_photographers_handler.php",
        method: "POST",
        data: { query: query },
        success: function (data) {
          suggestionsBox.innerHTML = data;
          suggestionsBox.style.display = "block";
        }
      });
    } else {
      suggestionsBox.style.display = "none";
    }
  });

  document.addEventListener("click", function (e) {
    if (!e.target.closest(".search-bar-wrapper")) {
      suggestionsBox.style.display = "none";
    }
  });
</script>
