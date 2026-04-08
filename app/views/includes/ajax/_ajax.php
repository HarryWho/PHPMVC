<script>
    async function doAjaxCall(p_url, formData = null, cb = null) {
        
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
                    alert(response)
                    response.messageBody = cb === null ? response.messageBody : GetResponseText(response);
                    showModal(response, 'modal-success');
                    return response.success;
                } else {
                    showModal(response, 'modal-warning');
                    return false;
                }
            },
            error: function(xhr, status, error) {
                
                console.error("AJAX Error:", error);
                showModal(error, 'error');
            }
        });
    }

    function GetResponseText(response) {

        return `

        <pre>
            Name: ${
                response.messageBody.user_name
            }
            Email: ${
                response.messageBody.user_email
            }
            Role: ${
                response.messageBody.user_role
                    .replace(/_/g, " ")
                    .replace(/\b\w/g, c => c.toUpperCase())
            } 
        </pre>
        `;
    }
</script>