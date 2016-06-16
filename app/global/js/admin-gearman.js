$(function() {
    $(document).ready(function() {
        console.log('admin-gearman');

        //js global vars
        var id;
        var table;

        //gearman edit click handler
        $('td > button[action="edit-gearman"]').click(function() {

            //hide alert
            $("#edit-gearman-alert").hide();

            //get gearman-id
            gearman_id = $(this).attr('gearman-id');
            $.ajax({
                type: "GET",
                url: 'db/gearman_job_servers/' + gearman_id,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#edit-gearman-hostname-text").val(response.data.hostname);
                        $("#edit-gearman-port-text").val(response.data.port);
                        $("#edit-gearman-enabled-checkbox").prop('checked', (response.data.enabled == 1 ? true: false));
                       
                        id = response.data.id;
                        $('#edit-gearman-modal').modal('show');
                    } else {
                        //show alert
                        $("#edit-gearman-alert").empty();
                        $("#edit-gearman-alert").append(response.message);
                        $("#edit-gearman-alert").show();
                    }
                }
            });
        });

        //gearman edit save
        $("#edit-gearman-modal-save").click(function() {

            var gearman = {};
            gearman.id = id;
            gearman.hostname = $("#edit-gearman-hostname-text").val();
            gearman.port = $("#edit-gearman-port-text").val();
            gearman.enabled = $("#edit-gearman-enabled-checkbox").is(':checked') ? 1: 0;

            $.ajax({
                type: "PUT",
                url: 'db/gearman_job_servers/' + id,
                data: gearman,
                contentType: 'json',
                dataType: 'json',
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                        //show alert
                        $("#edit-gearman-alert").empty();
                        $("#edit-gearman-alert").append(response.message);
                        $("#edit-gearman-alert").show();
                    }
                }
            });
        });

        //gearman delete click handler
        $('td > button[action="delete-gearman"]').click(function() {
            console.log('delete-gearman');
            $('#confirmation-delete-alert').hide();
            id = $(this).attr('gearman-id');
            table = 'gearman_job_servers';
            $('#confirmation-delete-modal').modal('show');
        });

        //delete confirmation confirm
        $("#continue-delete-button").click(function() {
            //send DELETE
            $.ajax({
                type: 'DELETE',
                url: 'db/' + table + '/' + id,
                dataType: "json",
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                        //show alert
                        $("#confirmation-delete-alert").empty();
                        $("#confirmation-delete-alert").append(response.message);
                        $("#confirmation-delete-alert").show();
                    }
                }
            });
        });

        //gearman add click handler
        $('button[action="add-gearman"]').click(function() {
            console.log('add-gearman');

            //hide alert
            $("#add-gearman-alert").hide();

            //clear
            $('#add-gearman-form').each(function() {
                this.reset();
            });

            //show
            $('#add-gearman-modal').modal('show');
        });

        //gearman add
        $("#add-gearman-modal-save").click(function() {

            var gearman = {};
            gearman.hostname = $("#add-gearman-hostname-text").val();
            gearman.port = $("#add-gearman-port-text").val();
            gearman.enabled = $("#add-gearman-enabled-checkbox").is(':checked') ? 1: 0;
         
            $.ajax({
                type: "POST",
                url: 'db/gearman_job_servers',
                data: gearman,
                contentType: 'json',
                dataType: 'json',
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                         //show alert
                        $("#add-gearman-alert").empty();
                        $("#add-gearman-alert").append(response.message);
                        $("#add-gearman-alert").show();
                    }
                }
            });
        });
    });
});
