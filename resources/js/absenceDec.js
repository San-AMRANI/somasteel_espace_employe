
$(document).ready(function () {
   $('table.shift-table').DataTable({
      "language": {
         "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/French.json",
         // "info": ""  // Remove the info text
      },
      searching: true,
      paging:false,
      responsive : true
   });

   // $('#shift-table-result').DataTable({
   //    "language": {
   //       "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/French.json",
   //    },
   //    searching: true,
   //    paging: false,
   //    dom: '<"top"fi>'
   // });
   function filterTable() {
      var serviceFilter = $('#service-filter').val().toLowerCase();
      var presenceFilter = $('#presence-filter').val().toLowerCase();

      // Iterate over each table row
      $('#shift-table-result tbody tr').each(function () {
         var row = $(this);
         var service = row.find('td').eq(2).text().toLowerCase(); // Service column
         var presence = row.find('td').eq(3).text().trim().toLowerCase(); // Présence column

         // Check if the row matches all the filters
         var isServiceMatch = serviceFilter === "" || service.indexOf(serviceFilter) !== -1;
         var isPresenceMatch = presenceFilter === "" || presence.indexOf(presenceFilter) !== -1;

         // Show or hide the row based on filter matches
         if (isServiceMatch && isPresenceMatch) {
            row.show();
         } else {
            row.hide();
         }
      });
   }

   // Event listeners for select elements
   $('#service-filter, #presence-filter').on('change', function () {
      filterTable();
   });

   // Trigger initial filtering to ensure all data is displayed on load
   filterTable();
});

document.addEventListener('DOMContentLoaded', function () {
   document.querySelectorAll('.btn-status').forEach(button => {
      button.addEventListener('click', function () {
         // Get the user ID from the button's data attributes
         let userId = this.getAttribute('data-user-id');
         let status = this.getAttribute('data-status');

         // Remove active class from both Présent and Absent buttons for this user
         let buttonsGroup = document.getElementById('attendance-buttons-' + userId);
         buttonsGroup.querySelectorAll('.btn-status').forEach(btn => btn.classList.remove('active'));

         // Add active class to the clicked button
         this.classList.add('active');

         // Update the hidden input with the selected status
         document.getElementById('status-' + userId).value = status;
      });
   });

   function updateSelectedUsers() {
      selectedUsersDiv.innerHTML = ''; // Clear existing badges

      selectedUserIds.forEach(userId => {
         const option = Array.from(userSelect.options).find(opt => opt.value == userId);
         if (option) {
            const userName = option.text;

            const badge = document.createElement('span');
            badge.className = 'badge bg-primary me-2 mb-2';
            badge.textContent = userName;

            const closeButton = document.createElement('button');
            closeButton.className = 'btn-close ms-1';
            closeButton.ariaLabel = 'Close';
            closeButton.type = 'button';
            closeButton.addEventListener('click', function () {
               // Deselect user in the select element
               option.selected = false;
               selectedUserIds.delete(option.value);
               updateSelectedUsers(); // Refresh badges
            });

            badge.appendChild(closeButton);
            selectedUsersDiv.appendChild(badge);
         }
      });
   }



   function updateClock() {
      const now = new Date();
      const day = String(now.getDate()).padStart(2, '0');
      const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
      const year = String(now.getFullYear()).slice(-2); // Get last two digits of year
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      const timeString = `${hours}:${minutes}`;
      const dateString = `${day}-${month}-${year}`;
      document.querySelector('.time').textContent = timeString;
      document.querySelector('.date').textContent = dateString;
   }

   setInterval(updateClock, 1000);
   updateClock(); // Initial call to display the clock immediately

   // const navLinks = document.querySelectorAll('.shift.nav-link');
   // const cardBodies = document.querySelectorAll('.shift-card-body');

   // // Function to set the active card
   // function setActiveCard(shiftId) {
   //    // Hide all card bodies
   //    cardBodies.forEach(body => body.classList.add('d-none'));

   //    // Show the selected card body
   //    const activeCardBody = document.getElementById(`shift-card-body-${shiftId}`);
   //    if (activeCardBody) {
   //       activeCardBody.classList.remove('d-none');
   //    }
   // }

   // Set up event listeners for nav links
   navLinks.forEach(link => {
      link.addEventListener('click', function (event) {
         event.preventDefault();

         // Remove 'active' class from all nav links
         navLinks.forEach(link => link.classList.remove('active'));

         // Add 'active' class to the clicked nav link
         this.classList.add('active');

         // Get shift ID from the data attribute
         const shiftId = this.dataset.shiftId;

         // Store the selected shift ID in localStorage
         localStorage.setItem('lastSelectedShift', shiftId);

         // Display the corresponding card
         setActiveCard(shiftId);
      });
   });

   // Retrieve the last selected shift ID from localStorage
   const lastSelectedShift = localStorage.getItem('lastSelectedShift');

   if (lastSelectedShift) {
      // Make the corresponding card visible
      setActiveCard(lastSelectedShift);

      // Set the 'active' class on the corresponding nav link
      const activeNavLink = document.querySelector(`.shift.nav-link[data-shift-id="${lastSelectedShift}"]`);
      if (activeNavLink) {
         activeNavLink.classList.add('active');
      }
   } else {
      // Default behavior if no shift was previously selected
      if (navLinks.length > 0) {
         // Optionally, you can set the first shift as default
         const defaultShiftId = navLinks[0].dataset.shiftId;
         setActiveCard(defaultShiftId);
         navLinks[0].classList.add('active');
      }
   }

   const attendanceButtons = document.querySelectorAll('.btn-status');
   attendanceButtons.forEach(button => {
      button.addEventListener('click', function () {
         const userId = this.closest('tr').id.split('-')[2];
         const shiftId = this.closest('form').querySelector('input[name="shift_id"]').value;
         const attendanceStatus = this.dataset.status;

         // If the button is already active, deactivate it
         if (this.classList.contains('active')) {
            this.classList.remove('active');
            document.getElementById(`status_${userId}_${shiftId}`).value = ''; // Clear the hidden input value
         } else {
            // Remove active class from all buttons in the same row
            const rowButtons = document.querySelectorAll(`#attendance-buttons-${userId} .btn-status`);
            rowButtons.forEach(btn => btn.classList.remove('active'));

            // Add active class to the clicked button
            this.classList.add('active');

            // Set the hidden input value
            document.getElementById(`status_${userId}_${shiftId}`).value = attendanceStatus;
         }
      });
   });
   
});
