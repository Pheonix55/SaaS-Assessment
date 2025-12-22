<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SweetAlert2 Test</title>

    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Bootstrap CSS (optional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-5">

    <h2>SweetAlert2 Test Page</h2>

    <div class="my-3">
        <button id="btn1" class="btn btn-info me-2">Auto-close Info</button>
        <button id="btn2" class="btn btn-warning me-2">Confirm Dialog</button>
        <button id="btn3" class="btn btn-primary me-2">Multiple Buttons</button>
        <button id="btn4" class="btn btn-success me-2">Positioned Alert</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Auto-close info alert
            document.getElementById('btn1').addEventListener('click', () => {
                // Auto-close alert
                showSweetAlert({
                    message: 'Saved successfully!',
                    icon: 'success',
                    autoHide: true,
                    hideAfter: 2000
                });
            });

            // Confirmation dialog
            document.getElementById('btn2').addEventListener('click', () => {
                // Simple alert
                showSweetAlert({
                    message: 'This is a simple info message'
                });
            });

            // Multiple buttons with callbacks
            document.getElementById('btn3').addEventListener('click', () => {


                // Confirmation
                showSweetAlert({
                    title: 'Are you sure?',
                    message: 'You will not be able to revert this!',
                    icon: 'warning',
                    confirmText: 'Yes, delete it!',
                    cancelText: 'Cancel',
                    onConfirm: () => console.log('Deleted!'),
                    onCancel: () => console.log('Cancelled')
                });



            });

            // Positioned alert
            document.getElementById('btn4').addEventListener('click', () => {
                showSweetAlert({
                    title: 'Top-end Alert',
                    message: 'This alert appears at top-end',
                    icon: 'success',
                    position: 'top-end',
                    autoHide: true,
                    hideAfter: 2500
                });
            });

        });
    </script>

    <!-- Bootstrap JS (optional, for styling) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
