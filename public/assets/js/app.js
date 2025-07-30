// Global App JavaScript

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

// Format number
function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Show loading
function showLoading() {
    Swal.fire({
        title: 'Please wait...',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
}

// Hide loading
function hideLoading() {
    Swal.close();
}

// Confirm delete
function confirmDelete(url, message = 'Are you sure you want to delete this item?') {
    Swal.fire({
        title: 'Confirm Delete',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

// Ajax request helper
function ajaxRequest(url, method, data, successCallback, errorCallback) {
    $.ajax({
        url: url,
        method: method,
        data: data,
        dataType: 'json',
        success: function(response) {
            if (successCallback) {
                successCallback(response);
            }
        },
        error: function(xhr, status, error) {
            if (errorCallback) {
                errorCallback(xhr, status, error);
            } else {
                Swal.fire('Error', 'An error occurred. Please try again.', 'error');
            }
        }
    });
}

// Initialize tooltips
$(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
});

// Auto dismiss alerts after 5 seconds
$(document).ready(function() {
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 5000);
});