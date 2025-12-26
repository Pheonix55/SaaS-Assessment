import './bootstrap';
import Swal from 'sweetalert2';

window.showSweetAlert = async function ({
    title = '',
    message = '',
    icon = 'info',
    position = 'center',
    autoHide = false,
    hideAfter = 3000,
    buttons = [] // [{text, color, callback, showLoader}]
} = {}) {

    // If no custom buttons, just show normal alert
    if (!buttons.length) {
        await Swal.fire({
            title,
            text: message,
            icon,
            position,
            timer: autoHide ? hideAfter : undefined,
            timerProgressBar: autoHide,
            showConfirmButton: !autoHide,
            allowOutsideClick: !autoHide
        });
        return;
    }
    
    // Show alert with empty confirm button to create actions container
    const result = await Swal.fire({
        title,
        text: message,
        icon,
        position,
        showConfirmButton: true,
        confirmButtonText: '', // hide the default button text
        didOpen: () => {
            // Remove default confirm button
            const confirmBtn = Swal.getConfirmButton();
            if (confirmBtn) confirmBtn.style.display = 'none';

            // Add custom buttons
            const container = Swal.getActions();
            container.innerHTML = '';
            buttons.forEach(btn => {
                const buttonEl = document.createElement('button');
                buttonEl.textContent = btn.text;
                buttonEl.className = `swal2-styled ${btn.color || 'swal2-confirm'}`;
                buttonEl.style.margin = '0 5px';
                buttonEl.addEventListener('click', async () => {
                    if (btn.showLoader) Swal.showLoading();
                    Swal.close();
                    if (typeof btn.callback === 'function') await btn.callback();
                });
                container.appendChild(buttonEl);
            });
        },
        allowOutsideClick: false
    });

    return result;
};

import "./supportChat";
