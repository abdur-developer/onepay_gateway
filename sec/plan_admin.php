<!-- Pricing Plans -->
 <div class="text-center mb-5">
    <button class="btn btn-primary" onclick="openModal()">Add New Plan</button>
</div>
<style>
    .badge-x{
        position: absolute;
        top: 10px;
        right: -30px;
        background-color: var(--accent-color);
        color: white;
        padding: 0.25rem 2rem;
        font-size: 0.75rem;
        font-weight: 600;
        transform: rotate(45deg);
        transform-origin: center;
    }
</style>
<div class="row g-4">
    
    <?php
        $sql = "SELECT * FROM plans WHERE status = 'active' ORDER BY price ASC";
        $result = $pdo->query($sql);
        if (!$result) {
            die("Query failed: " . $pdo->errorInfo()[2]);
        }
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
            <div class="col-lg-4">
              <div class="card p-4 highlight">
                  <span class="badge-x"><?=$row['badgeName']?></span>
                  <div class="card-body text-center">
                      <h4 class="card-title"><?=$row['name']?></h4>
                      <p class="card-subtitle text-muted mb-4"><?=$row['description']?></p>

                      <div class="price-display text-danger mb-2">৳<?=$row['price']?></div>
                      <p class="text-muted mb-4">for <?=$row['duration']?> Days</p>
                      
                      <button class="btn btn-danger w-100" onclick="edit('<?=$row['id']?>')">
                          <i class="fas fa-edit me-1"></i> Edit Plan
                      </button>
                  </div>
              </div>
          </div>
        <?php } ?>

    
</div>
<style>
    :root {
      --primary-color: #4361ee;
      --success-color: #2ecc71;
      --danger-color: #e74c3c;
      --dark-color: #2c3e50;
      --light-color: #ecf0f1;
      --border-color: #dfe6e9;
      --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s ease;
    }
    /* Modal styling */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      opacity: 0;
      visibility: hidden;
      transition: var(--transition);
    }

    .modal-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    .modal {
        background-color: white;
        border-radius: 0.75rem;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        transform: translateY(20px);
        transition: var(--transition);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        position: relative;
        padding: 0;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        margin: 0 1rem; /* Add margin for small screens */
        z-index: 1021;
        scrollbar-width: none;
    }
    .modal::-webkit-scrollbar {
        display: none;
    }
    .modal-overlay.active .modal {
      transform: translateY(0);
    }

    .modal-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .modal-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--dark-color);
    }

    .modal-close {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: #7f8c8d;
      transition: var(--transition);
    }

    .modal-close:hover {
      color: var(--danger-color);
    }

    .modal-body {
      padding: 1.5rem;
    }

    .modal-footer {
      padding: 1rem 1.5rem;
      border-top: 1px solid var(--border-color);
      display: flex;
      justify-content: flex-end;
      gap: 1rem;
    }

    /* Form styling */
    .form-group {
      margin-bottom: 1.25rem;
    }

    .form-label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--dark-color);
    }

    .form-control {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid var(--border-color);
      border-radius: 0.5rem;
      font-size: 1rem;
      transition: var(--transition);
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
    }

    textarea.form-control {
      min-height: 100px;
      resize: vertical;
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
      .modal {
        width: 95%;
        margin: 0 auto;
      }
      
      .modal-footer {
        flex-direction: column;
      }
      
      .modal-footer .btn {
        width: 100%;
      }
    }
</style>
<div id="modalOverlay" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h2 class="modal-title">Package Details</h2>
            <button class="modal-close" id="closeModalBtn">&times;</button>
        </div>
        <div class="modal-body">
            <form id="packageForm" method="GET">
                <input type="hidden" name="packageId" id="packageId" value="33" />
                <div class="form-group">
                    <label for="packageName" class="form-label">Package Name</label>
                    <input type="text" id="packageName" class="form-control" placeholder="Enter package name" required />
                </div>
                <div class="form-group">
                    <label for="packageDescription" class="form-label">Description</label>
                    <textarea id="packageDescription" class="form-control" placeholder="Enter package description"></textarea>
                </div>
                <div class="form-group">
                    <label for="badgeName" class="form-label">Badge Name</label>
                    <input type="text" id="badgeName" class="form-control" placeholder="Enter badge name" />
                </div>
                <div class="form-group">
                    <label for="web_limit" class="form-label">Website Limit</label>
                    <input type="number" id="web_limit" class="form-control" placeholder="Enter web limit" />
                </div>
                <div class="form-group">
                    <label for="validityDays" class="form-label">Validity (Days)</label>
                    <input type="number" id="validityDays" class="form-control" placeholder="Enter validity in days" min="1" />
                </div>
                <div class="form-group">
                    <label for="price" class="form-label">Price ($)</label>
                    <input type="number" id="price" class="form-control" placeholder="0.00" step="0.01" min="0" />
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button id="cancelBtn" class="btn btn-danger">Cancel</button>
            <button id="saveBtn" class="btn btn-success">
                <svg class="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Save Package
            </button>
        </div>
    </div>
</div>
<script>
    // DOM Elements
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const saveBtn = document.getElementById('saveBtn');
    const modalOverlay = document.getElementById('modalOverlay');
    const packageForm = document.getElementById('packageForm');

    // Open modal
    edit = (id) => {
        document.getElementById('packageId').value = id;
        modalOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        // Fetch package data if needed (e.g., via AJAX)
        fetchPackageData(id);
    };
    function openModal() {
        modalOverlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Disable scrolling
        packageForm.reset(); // Reset form when opening
        document.getElementById('packageId').value = ''; // Clear package ID
    }

    // Fetch package data (mock function, replace with actual AJAX call)
    function fetchPackageData(id) {

      fetch('sec/getPackageData.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id })
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (data) {
          populateForm(data);
        } else {
          console.error('Package data not found');
        }
      })
      .catch(error => console.error('Error fetching package data:', error));
    }

    function populateForm(data) {
      document.getElementById('packageName').value = data.name;
      document.getElementById('packageDescription').value = data.description;
      document.getElementById('badgeName').value = data.badgeName;
      document.getElementById('web_limit').value = data.web_limit;
      document.getElementById('validityDays').value = data.duration;
      document.getElementById('price').value = data.price;
    }

    // Close modal
    function closeModal() {
      modalOverlay.classList.remove('active');
      document.body.style.overflow = ''; // Re-enable scrolling
      packageForm.reset(); // Reset form when closing
    }

    // Close modal when clicking X, Cancel, or outside the modal
    closeModalBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', (e) => {
      if (e.target === modalOverlay) {
        closeModal();
      }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modalOverlay.classList.contains('active')) {
        closeModal();
      }
    });

    // Save package
    saveBtn.addEventListener('click', (e) => {
      e.preventDefault();
      
      if (!packageForm.checkValidity()) {
        packageForm.reportValidity();
        return;
      }

      const packageData = {
        id: document.getElementById('packageId').value || null, // Use null if no ID
        name: document.getElementById('packageName').value,
        description: document.getElementById('packageDescription').value,
        badgeName: document.getElementById('badgeName').value,
        web_limit: document.getElementById('web_limit').value,
        validityDays: parseInt(document.getElementById('validityDays').value),
        price: parseFloat(document.getElementById('price').value).toFixed(2)
      };

      //sec/savePackage.php send package data to server
      fetch('sec/savePackage.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(packageData)
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (!data.success) {
          throw new Error(data.message || 'Failed to save package data');
        }else {
          window.location.reload(); // Reload the page to reflect changes
        }
      })
      .catch(error => console.error('Error saving package data:', error));
    });

    // Focus first input when modal opens
    modalOverlay.addEventListener('transitionend', () => {
      if (modalOverlay.classList.contains('active')) {
        document.getElementById('packageId').focus();
      }
    });
  </script>