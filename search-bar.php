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
  width: 8px;
  height: 7px;
  border-left: 3px solid white;
  border-bottom: 3px solid white;
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

.search-dropdown-content div {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 16px;
  text-decoration: none;
  color: #111;
  font-weight: 500;
  transition: background 0.3s;
  cursor:pointer;
}

.search-dropdown-content div:hover {
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




.suggestion-list {
	display:none;
  position: absolute;
  top: 0px !important; /* Align with bottom of search bar */
  left: 0;
  right: 0;
  background: rgba(90, 90, 90, 1);
  backdrop-filter: blur(3px);
  border-radius: 23px 23px 23px 23px; /* Rounded only on bottom corners */
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
  padding: 8px;
  margin-top: 20px; /* No margin to connect with search bar */
  z-index: 990; /* Just below search bar z-index */
  max-height: 350px;
  overflow-y: auto;
  border: none;
  transition: all 0.3s ease;
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 255, 255, 0.5) transparent;
  border-top: 1px solid rgba(255, 255, 255, 0.1); /* Subtle divider */
}

.suggestion-list::-webkit-scrollbar {
  width: 6px;
}

.suggestion-list::-webkit-scrollbar-track {
  background: transparent;
}

.suggestion-list::-webkit-scrollbar-thumb {
  background-color: #ccc;
  border-radius: 6px;
}

.suggestion-list li {
  display: flex;
  align-items: center;
  padding: 12px 15px;
  border-radius: 8px;
  margin: 6px 0;
  font-size: 15px;
  transition: all 0.3s ease;
  color: white;
  justify-content: space-between;
  border-bottom: none;
  background-color: rgba(255, 255, 255, 0.1);
}

.suggestion-list li:hover {
  background-color: rgba(255, 255, 255, 0.2);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.suggestion-list .user-info {
  display: flex;
  align-items: center;
  gap: 14px;
  flex: 1;
}

.suggestion-list .profile-image {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid rgba(255, 255, 255, 0.7);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.username-edit {
  font-family: 'Poppins', sans-serif;
  font-weight: 500;
  color: rgba(255, 255, 255, 0.9);
  text-decoration: none;
  transition: all 0.2s ease;
  font-size: 15px;
  letter-spacing: 0.2px;
}

.username-edit:hover {
  color: white;
  text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
}

.action-buttons {
  display: flex;
  gap: 8px;
}

.btn-action {
  font-family: 'Poppins', sans-serif;
  font-weight: 500;
  font-size: 13px;
  padding: 6px 12px;
  border-radius: 20px;
  border: none;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.book-btn {
  background: linear-gradient(to right, #2980b9, #3498db);
  color: white;
}

.book-btn:hover {
  background: linear-gradient(to right, #2573a7, #2980b9);
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.chat-btn {
  background: linear-gradient(to right, #2d3436, #636e72);
  color: white;
}

.chat-btn:hover {
  background: linear-gradient(to right, #222, #444);
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-action i {
  width: 14px;
  height: 14px;
  margin-right: 5px;
}

.chat-btn .feather {
  width: 14px;
  height: 14px;
  margin-right: 5px;
}

.empty-result {
  padding: 20px;
  text-align: center;
  color: #777;
  font-style: italic;
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
            <div onclick="selectOption('photos', 'image')"><i data-feather="image"></i>Photos</div>
            <div onclick="selectOption('photographers', 'user')"><i data-feather="user"></i> Photographers</div>
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
		<ul id="photographer-suggestions" class="suggestion-list"></ul>

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
  // Re-initialize Feather icons when suggestions are loaded
  const originalAjaxSuccess = $.ajax;
  $.ajax = function() {
    const originalSuccess = arguments[0].success;
    if (originalSuccess) {
      arguments[0].success = function(data) {
        const result = originalSuccess.apply(this, arguments);
        if (typeof feather !== 'undefined') {
          setTimeout(() => feather.replace(), 10);
        }
        return result;
      };
    }
    return originalAjaxSuccess.apply($, arguments);
  };
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
