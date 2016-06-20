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

            //hide
            $("#add-vm-alert").empty();

        	var vm = {};
        	vm.vbox_soap_endpoints_id = $("#add-vm-vbox-select").val();
        	vm.games_id = $("#add-vm-game-select").val();
        	vm.github_id = $("#add-vm-github-select").val();
            vm.cpu = $("#add-vm-cpu-text").val();
            vm.memory_mb = $("#add-vm-mem-text").val();
            vm.services = $("#add-vm-service-select").val();
            vm.image_id = $("#add-vm-image-select").val();

            //must select at least one service
            if(vm.services == null){
                console.log('err');
                //show alert
                $("#add-vm-alert").append("Must select at least one service!");
                $("#add-vm-alert").show();
                return;
            }

            //cpu greater than zero
            if(vm.cpu.search(/^[1-9][0-9]*$/) == -1){
                //show alert
                $("#add-vm-alert").append("CPU must be number greater than 0");
                $("#add-vm-alert").show();
                return;
            }

            //mem greater than zero
            if(vm.memory_mb.search(/^[1-9][0-9]*$/) == -1){
                //show alert
                $("#add-vm-alert").append("Memory (MB) must be number greater than 0");
                $("#add-vm-alert").show();
                return;
            }

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
                        $("#add-vm-alert").empty();
                        $("#add-vm-alert").append(response.message);
                        $("#add-vm-alert").show();
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

        //resize button handler
        var vm_id;
        $('li[action="resize"]').click(function() {
            console.log('resize');

            //get virtualbox id
            vm_id = $(this).attr('vm-id');

              //hide alert
            $("#resize-alert").hide();

            //get github-id
            vm_id = $(this).attr('vm-id');
            $.ajax({
                type: "GET",
                url: 'db/virtualboxes/' + vm_id,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#resize-cpu-text").val(response.data.cpu);
                        $("#resize-mem-text").val(response.data.memory_mb);
                        $('#resize-modal').modal('show');
                    } else {
                        //show alert
                        $("#resize-alert").empty();
                        $("#resize-alert").append(response.message);
                        $("#resize-alert").show();
                    }
                }
            });
        });

        //resize save
        $("#resize-modal-save").click(function() {

            //hide
            $("#resize-alert").empty();

            //get values
            var cpu = $("#resize-cpu-text").val();
            var mem = $("#resize-mem-text").val();

            //cpu greater than zero
            if(cpu.search(/^[1-9][0-9]*$/) == -1){
                //show alert
                $("#resize-alert").append("CPU must be number greater than 0");
                $("#resize-alert").show();
                return;
            }

            //mem greater than zero
            if(mem.search(/^[1-9][0-9]*$/) == -1){
                //show alert
                $("#resize-alert").append("Memory (MB) must be number greater than 0");
                $("#resize-alert").show();
                return;
            }
         
            $.ajax({
                type: "POST",
                url: 'virtualbox/resize/' + vm_id + '/' + cpu + '/' + mem,
                contentType: 'json',
                dataType: 'json',
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                         //show alert
                        $("#resize-alert").empty();
                        $("#resize-alert").append(response.message);
                        $("#resize-alert").show();
                    }
                }
            });
        });

        //delete click handler
        $('li[action="delete"]').click(function() {
            console.log('delete-button');
            $('#confirmation-delete-alert').hide();
            vm_id = $(this).attr('vm-id');
            $('#confirmation-delete-modal').modal('show');
        });

        //delete confirmation confirm
        $("#continue-delete-button").click(function() {
            $.ajax({
                type: "POST",
                url: 'virtualbox/delete/' + vm_id,
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
        });
    });
});