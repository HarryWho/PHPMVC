<script>
    function doAjaxCall(p_url, formData = null) {

        $.ajax({
            url: p_url,
            type: "POST",
            data: formData,
            processData: false, // required
            contentType: false, // required
            dataType: "json",
            success: function(response) {
                console.log("Server says:", response);
                if (response.success) {
                    showModal(response, 'modal-success');
                    return response.success;
                } else {
                    showModal(response, 'modal-warning');
                    return false;
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    
</script>