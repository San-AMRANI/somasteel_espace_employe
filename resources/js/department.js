$(document).ready(function () {
    $('#createDepartmentForm').on('submit', function (event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        $(this).addClass('was-validated');
    });
	
	var departmentCards = document.querySelectorAll('.department-card');
    
    departmentCards.forEach(function(card, index) {
        if (index % 2 === 0) {
            card.classList.add('gray-background');
        } else {
            card.classList.add('white-background');
        }
    });
    
    var departmentFilter = document.getElementById('departmentFilter');
    var departmentItems = document.querySelectorAll('.department-item');

    // Event listener for input change
    departmentFilter.addEventListener('input', filterDepartments);

    // Filtering function
    function filterDepartments() {
        var filterValue = departmentFilter.value.trim().toLowerCase();

        departmentItems.forEach(function (item) {
            var departmentName = item.querySelector('.card-title').textContent.trim().toLowerCase();

            // Check if the department matches the search filter
            var isVisible = departmentName.includes(filterValue);

            // Adjust visibility based on the filter
            item.style.display = isVisible ? '' : 'none';
        });
    }
});