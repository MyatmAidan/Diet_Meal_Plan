</div>
</div>

<script src="../assets/js/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/theme.js"></script>
<script>
    $(function() {
        $('#toggleSidebar').on('click', function() {
            $('#wrapper').toggleClass('sidebar-hidden');
        });
    });

    function deleteFun(id, type, recommendation_id = null) {
        Swal.fire({
            title: '<?= __("Are you sure?") ?>',
            text: "<?= __("You won't be able to revert this!") ?>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<?= __("Yes, delete it!") ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: './delete_method.php',
                    type: 'POST',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify({
                        [type]: id,
                        recommendation_id
                    }),
                    success: function(response) {
                        console.log(response);
                        Swal.fire(
                            '<?= __("Deleted!") ?>',
                            response.message || '<?= __("The item has been deleted.") ?>',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                        console.error(status);
                        console.error(error);
                        Swal.fire(
                            '<?= __("Error!") ?>',
                            xhr.responseJSON.message,
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
</body>

</html>