$(function() {
    $(document).ready(function() {
        console.log('admin-service');

        //js global vars
        var id;
        var table;

        //service edit click handler
        $('td > button[action="edit-service"]').click(function() {
            console.log('edit-service');

            //hide alert
            $("#edit-service-alert").hide();

            //get service-id
            service_id = $(this).attr('service-id');
            $.ajax({
                type: "GET",
                url: 'db/services/' + service_id,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        console.log(response);

                        $("#edit-service-name-text").val(response.data.full_name);
      

                        id = response.data.id;
                        $('#edit-service-modal').modal('show');
                    } else {
                        //show alert
                        $("#edit-service-alert").empty();
                        $("#edit-service-alert").append(response.message);
                        $("#edit-service-alert").show();
                    }
                }
            });
        });

        //service edit save
        $("#edit-service-modal-save").click(function() {

            var service = {};
            service.id = id;
            service.full_name = $("#edit-game-name-text").val();
        
            $.ajax({
                type: "PUT",
                url: 'db/services/' + id,
                data: service,
                contentType: 'json',
                dataType: 'json',
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                        //show alert
                        $("#edit-service-alert").empty();
                        $("#edit-service-alert").append(response.message);
                        $("#edit-service-alert").show();
                    }
                }
            });
        });

        //service delete click handler
        $('td > button[action="delete-service"]').click(function() {
            console.log('delete-service');
            $('#confirmation-delete-alert').hide();
            id = $(this).attr('service-id');
            table = 'services';
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

        //service add click handler
        $('button[action="add-service"]').click(function() {

            //hide alert
            $("#add-service-alert").hide();

            //clear
            $('#add-service-form').each(function() {
                this.reset();
            });

            //show
            $('#add-service-modal').modal('show');
        });

        //service add
        $("#add-service-modal-save").click(function() {

            var service = {};
            service.id = id;
            service.full_name = $("#add-game-name-text").val();
         
            $.ajax({
                type: "POST",
                url: 'db/services',
                data: service,
                contentType: 'json',
                dataType: 'json',
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                         //show alert
                        $("#add-service-alert").empty();
                        $("#add-service-alert").append(response.message);
                        $("#add-service-alert").show();
                    }
                }
            });
        });
    });
});
