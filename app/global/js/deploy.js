$(function() {
    $(document).ready(function() {

		console.log('deploy');

        $("#vm-alert").hide();

        $("#add-vm-vbox-select").change(function() {
            console.log('change');
        }); 

	    //vm add click handler
        $('button[action="add-vm"]').click(function() {
            console.log('add-vm');

            //hide alert
            $("#add-vm-alert").hide();

            //clear
            $('#add-vm-form').each(function() {
                this.reset();
            });

 			//set game id
            $("#add-vm-game-select").val($(this).attr('game-id'));

            //get game-id
            game_id = $(this).attr('game-id');
            $.ajax({
                type: "GET",
                url: 'util/services/' + game_id,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                        $('#add-vm-service-select').empty();
                        $.each(response.data, function (i, item) {
                            $('#add-vm-service-select').append($('<option>', { 
                                value: item.id,
                                text : item.script_name 
                            }));
                        });
                   
                    } else{
                        //show alert
                        $("#add-vbox-alert").empty();
                        $("#add-vbox-alert").append(response.message);
                        $("#add-vbox-alert").show();
                    }

                      //show
                    $('#add-vm-modal').modal('show');
               }
           });
        });

        //vm add
        $("#add-vm-modal-save").click(function() {
        	console.log('vm-save');

        	var vm = {};
        	vm.vbox_soap_endpoints_id = $("#add-vm-vbox-select").val();
        	vm.games_id = $("#add-vm-game-select").val();
        	vm.github_id = $("#add-vm-github-select").val();
            vm.cpu = $("#add-vm-cpu-text").val();
            vm.mem = $("#add-vm-mem-text").val();
            vm.services = $("#add-vm-service-select").val();
            vm.image_id = $("#add-vm-image-select").val();
         
            $.ajax({
                type: "POST",
                url: 'virtualbox/add',
                data: vm,
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

        //vm options button clicks
        $('#vm-options li').click(function(e) {
            var vm_id = $(this).attr('vm-id');
            var op =  $(this).attr('op');

            if(op) {
                $.ajax({
                    type: "POST",
                    url: 'virtualbox/' + op + '/' + vm_id,
                    contentType: 'json',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success)
                            window.location.reload();
                        else{
                             //show alert
                            $("#vm-alert").empty();
                            $("#vm-alert").append(response.message);
                            $("#vm-alert").show();
                        }
                    }
                });
            }
        });

        //service options button clicks
        $('#service-options li').click(function(e) {
            var vm_id = $(this).attr('vm-id');
            var op =  $(this).attr('op');

            if(op) {
                $.ajax({
                    type: "POST",
                    url: 'service/' + op + '/' + vm_id,
                    contentType: 'json',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success)
                            window.location.reload();
                        else{
                             //show alert
                            $("#vm-alert").empty();
                            $("#vm-alert").append(response.message);
                            $("#vm-alert").show();
                        }
                    }
                });
            }
        });
    });
});