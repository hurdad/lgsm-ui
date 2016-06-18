$(function() {
    $(document).ready(function() {
        console.log('admin-worker');

        //js global vars
        var id;
        var table;
        var function_name;

        //worker edit click handler
        $('td > button[action="edit-worker"]').click(function() {

            //hide alert
            $("#edit-worker-alert").hide();

            //get worker-id
            worker_id = $(this).attr('worker-id');
            $.ajax({
                type: "GET",
                url: 'db/workers/' + worker_id,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#edit-worker-function-text").val(response.data.function_name); 
                        function_name = response.data.function_name;
                        $("#edit-worker-count-text").val(response.data.worker_count);
                        $("#edit-worker-enabled-checkbox").prop('checked', (response.data.enabled == 1 ? true: false));
                       
                        id = response.data.id;
                        $('#edit-worker-modal').modal('show');
                    } else {
                        //show alert
                        $("#edit-worker-alert").empty();
                        $("#edit-worker-alert").append(response.message);
                        $("#edit-worker-alert").show();
                    }
                }
            });
        });

        //worker edit save
        $("#edit-worker-modal-save").click(function() {

            var worker = {};
            worker.id = id;
            worker.function_name = function_name;
            worker.worker_count = $("#edit-worker-count-text").val();
            worker.enabled = $("#edit-worker-enabled-checkbox").is(':checked') ? 1: 0;

            $.ajax({
                type: "PUT",
                url: 'db/workers/' + id,
                data: worker,
                contentType: 'json',
                dataType: 'json',
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                        //show alert
                        $("#edit-worker-alert").empty();
                        $("#edit-worker-alert").append(response.message);
                        $("#edit-worker-alert").show();
                    }
                }
            });
        });

    });
});
