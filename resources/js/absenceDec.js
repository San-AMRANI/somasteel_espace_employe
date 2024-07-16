
function exportToExcel() {
   const table = document.getElementById('result-shifts-table');
   const ws = XLSX.utils.table_to_sheet(table);
   const wb = XLSX.utils.book_new();
   XLSX.utils.book_append_sheet(wb, ws, 'Shifts Data');
   const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });
   function s2ab(s) {
      const buf = new ArrayBuffer(s.length);
      const view = new Uint8Array(buf);
      for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
      return buf;
   }
   FileSaver.saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'shifts.xlsx');
}

document.addEventListener('DOMContentLoaded', function () {
   const exportButton = document.getElementById('export-button');
   if (exportButton) {
      exportButton.addEventListener('click', function () {
         exportToExcel();
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

   const navLinks = document.querySelectorAll('.shift.nav-link');
   const cardBodies = document.querySelectorAll('.shift-card-body');

   navLinks.forEach(link => {
      link.addEventListener('click', function (event) {
         event.preventDefault();
         navLinks.forEach(link => link.classList.remove('active'));
         this.classList.add('active');

         const shiftId = this.dataset.shiftId;
         cardBodies.forEach(body => body.classList.add('d-none'));
         document.getElementById(`shift-card-body-${shiftId}`).classList.remove('d-none');
      });
   });

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
