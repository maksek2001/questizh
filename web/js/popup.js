const BootstrapSwal = Swal.mixin({
	customClass: {
		confirmButton: 'btn btn-primary',
		cancelButton: 'btn btn-outline-secondary',
		denyButton: 'btn btn-danger',
	},
	buttonsStyling: false
});

const Toast = Swal.mixin({
	toast: true,
	position: 'top-end',
    iconColor: 'white',
    customClass: {
      popup: 'colored-toast'
    },
	showConfirmButton: false,
	timer: 3000,
	timerProgressBar: true,
	didOpen: (toast) => {
		toast.addEventListener('mouseenter', Swal.stopTimer)
		toast.addEventListener('mouseleave', Swal.resumeTimer)
	}
});

$(document).ready(function () {
    $('.confirm-link').on('click', function (e) {
        e.preventDefault();

        let message = $(this).attr('data-message');
        BootstrapSwal.fire({
            text: message ? message : 'Вы действительно хотите выполнить это действие?',
            confirmButtonText: 'Да',
            denyButtonText: 'Нет',
            icon: 'question',
            showDenyButton: true,
            showCloseButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.location.href = $(this).attr('href');
            }
        });
    });
});