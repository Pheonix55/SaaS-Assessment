import Swal from 'sweetalert2';

// Reusable function
export function showCustomAlert({
    message = '',
    confirmText = null,
    confirmCallback = null,
    cancelButton = false,
    cancelText = 'Cancel',
    autoHide = false,
    hideAfter = 3000
} = {}) {
    const swalOptions = {
        text: message,
        // icon: 'info',
        showConfirmButton: !!confirmText,
        confirmButtonText: confirmText || 'OK',
        showCancelButton: cancelButton,
        cancelButtonText: cancelText,
        allowOutsideClick: false,
        allowEscapeKey: false,
        timer: autoHide ? hideAfter : undefined
    };

    Swal.fire(swalOptions).then((result) => {
        if (result.isConfirmed && typeof confirmCallback === 'function') {
            confirmCallback();
        }
    });
}
