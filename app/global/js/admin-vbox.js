$(function() {
    $(document).ready(function() {
        console.log('admin-vbox');

        //js global vars
        var id;
        var table;

        //vbox edit click handler
        $('td > button[action="edit-vbox"]').click(function() {

            //hide alert
            $("#edit-vbox-alert").hide();

            //get vbox-id
            vbox_id = $(this).attr('vbox-id');
            $.ajax({
                type: "GET",
                url: 'db/vbox_soap_endpoints/' + vbox_id,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#edit-vbox-url-text").val(response.data.url);
                        $("#edit-vbox-username-text").val(response.data.username);
                        $("#edit-vbox-password-password").val(response.data.password);
                        $("#edit-vbox-machinefolder-text").val(response.data.machine_folder);
                        id = response.data.id;
                        $('#edit-vbox-modal').modal('show');
                    } else {
                        //show alert
                        $("#edit-vbox-alert").empty();
                        $("#edit-vbox-alert").append(response.message);
                        $("#edit-vbox-alert").show();
                    }
                }
            });
        });

        //vbox edit save
        $("#edit-vbox-modal-save").click(function() {

            var vbox = {};
            vbox.id = id;
            vbox.url = $("#edit-vbox-url-text").val();
            vbox.username = $("#edit-vbox-username-text").val();
            vbox.password = $("#edit-vbox-password-password").val();
            vbox.machine_folder = $("#edit-vbox-machinefolder-text").val();
        
            $.ajax({
                type: "PUT",
                url: 'db/vbox_soap_endpoints/' + id,
                data: vbox,
                contentType: 'json',
                dataType: 'json',
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                        //show alert
                        $("#edit-vbox-alert").empty();
                        $("#edit-vbox-alert").append(response.message);
                        $("#edit-vbox-alert").show();
                    }
                }
            });
        });

        //vbox delete click handler
        $('td > button[action="delete-vbox"]').click(function() {
            console.log('delete-vbox');
            $('#confirmation-delete-alert').hide();
            id = $(this).attr('vbox-id');
            table = 'vbox_soap_endpoints';
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

        //vbox add click handler
        $('button[action="add-vbox"]').click(function() {
            console.log('add-vbox');

            //hide alert
            $("#add-vbox-alert").hide();

            //clear
            $('#add-vbox-form').each(function() {
                this.reset();
            });

            //show
            $('#add-vbox-modal').modal('show');
        });

        //vbox add
        $("#add-vbox-modal-save").click(function() {

            var vbox = {};
            vbox.url = $("#add-vbox-url-text").val();
            vbox.username = $("#add-vbox-username-text").val();
            vbox.password = $("#add-vbox-password-password").val();
            vbox.machine_folder = $("#add-vbox-machinefolder-text").val();
         
            $.ajax({
                type: "POST",
                url: 'db/vbox_soap_endpoints',
                data: vbox,
                contentType: 'json',
                dataType: 'json',
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                         //show alert
                        $("#add-vbox-alert").empty();
                        $("#add-vbox-alert").append(response.message);
                        $("#add-vbox-alert").show();
                    }
                }
            });
        });
    });
});
