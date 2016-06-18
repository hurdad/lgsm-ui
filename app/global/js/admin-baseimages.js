$(function() {
    $(document).ready(function() {
        console.log('admin-baseimage');

        //js global vars
        var id;
        var table;

        //baseimage edit click handler
        $('td > button[action="edit-baseimage"]').click(function() {

            //hide alert
            $("#edit-baseimage-alert").hide();

            //get baseimage-id
            baseimage_id = $(this).attr('baseimage-id');
            $.ajax({
                type: "GET",
                url: 'db/base_images/' + baseimage_id,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#edit-baseimage-vbox-select").val(response.data.vbox_soap_endpoints_id);
                        $("#edit-baseimage-name-text").val(response.data.name);
                        $("#edit-baseimage-glibc-text").val(response.data.glibc_version);
                        $("#edit-baseimage-arc-select").val(response.data.architecture);
                        $("#edit-baseimage-username-text").val(response.data.ssh_username);
                        $("#edit-baseimage-password-text").val(response.data.ssh_password);
                        $("#edit-baseimage-sshkey-textarea").val(response.data.ssh_key);
                        $("#edit-baseimage-sshport-text").val(response.data.ssh_port);

                        id = response.data.id;
                        $('#edit-baseimage-modal').modal('show');
                    } else {
                        //show alert
                        $("#edit-baseimage-alert").empty();
                        $("#edit-baseimage-alert").append(response.message);
                        $("#edit-baseimage-alert").show();
                    }
                }
            });
        });

        //baseimage edit save
        $("#edit-baseimage-modal-save").click(function() {

            var baseimage = {};
            baseimage.id = id;
            baseimage.vbox_soap_endpoints_id = $("#edit-baseimage-vbox-select").val();
            baseimage.name =  $("#edit-baseimage-name-text").val();
            baseimage.glibc_version = $("#edit-baseimage-glibc-text").val()
            baseimage.architecture = $("#edit-baseimage-arch-select").val();
            baseimage.ssh_username = $("#edit-baseimage-username-text").val();
            baseimage.ssh_password = $("#edit-baseimage-password-text").val();
            baseimage.ssh_key = $("#edit-baseimage-sshkey-textarea").val();
            baseimage.ssh_port = $("#edit-baseimage-sshport-text").val();

            $.ajax({
                type: "PUT",
                url: 'db/base_images/' + id,
                data: baseimage,
                contentType: 'json',
                dataType: 'json',
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                        //show alert
                        $("#edit-baseimage-alert").empty();
                        $("#edit-baseimage-alert").append(response.message);
                        $("#edit-baseimage-alert").show();
                    }
                }
            });
        });

        //baseimage delete click handler
        $('td > button[action="delete-baseimage"]').click(function() {
            console.log('delete-baseimage');
            $('#confirmation-delete-alert').hide();
            id = $(this).attr('baseimage-id');
            table = 'base_images';
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

        //baseimage add click handler
        $('button[action="add-baseimage"]').click(function() {
            console.log('add-baseimage');

            //hide alert
            $("#add-baseimage-alert").hide();

            //clear
            $('#add-baseimage-form').each(function() {
                this.reset();
            });

            //show
            $('#add-baseimage-modal').modal('show');
        });

        //baseimage add
        $("#add-baseimage-modal-save").click(function() {

            var baseimage = {};
            baseimage.id = id;
            baseimage.vbox_soap_endpoints_id = $("#add-baseimage-vbox-select").val();
            baseimage.name =  $("#add-baseimage-name-text").val();
            baseimage.glibc_version = $("#add-baseimage-glibc-text").val()
            baseimage.architecture = $("#add-baseimage-arch-select").val();
            baseimage.ssh_username = $("#add-baseimage-username-text").val();
            baseimage.ssh_password = $("#add-baseimage-password-text").val();
            baseimage.ssh_key = $("#add-baseimage-sshkey-textarea").val();
            baseimage.ssh_port = $("#edit-baseimage-sshport-text").val();
         
            $.ajax({
                type: "POST",
                url: 'db/base_images',
                data: baseimage,
                contentType: 'json',
                dataType: 'json',
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                         //show alert
                        $("#add-baseimage-alert").empty();
                        $("#add-baseimage-alert").append(response.message);
                        $("#add-baseimage-alert").show();
                    }
                }
            });
        });
    });
});
