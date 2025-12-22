function showCompanyApprovalAlert(message) {
    const alertBox = document.getElementById('showCompanyApproval');
    if (!alertBox) return;
    alertBox.textContent = message + ' until you are approved by the system you will not be able to use any features';
    alertBox.classList.remove('d-none');
}
async function secureFetch(url, options = {}) {
    const response = await fetch(url, {
        ...options,
        headers: {
            'Accept': 'application/json',
            ...(options.headers || {}),
        }
    });

    const data = await response.json();

    if (response.status === 403 && data.message) {
        showCompanyApprovalAlert(data.message);
        return null;
    }

    if (response.status === 401) {
        console.log(data);
        window.location.href = '/';
        throw new Error('Unauthorized');
    }

    return data;
}
