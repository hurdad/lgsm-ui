$(function() {
    $(document).ready(function() {
        console.log('admin-game');

        //js global vars
        var id;
        var table;

        //game edit click handler
        $('td > button[action="edit-game"]').click(function() {

            //hide alert
            $("#edit-game-alert").hide();

            //get game-id
            game_id = $(this).attr('game-id');
            $.ajax({
                type: "GET",
                url: 'db/games/' + game_id,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                        $("#edit-game-query-engine-select").val(response.data.query_engines_id);
                        $("#edit-game-name-text").val(response.data.full_name);
                        $("#edit-game-folder-text").val(response.data.folder_name);
                        $("#edit-game-glibc-text").val(response.data.glibc_version_min);
                        $("#edit-game-hidden-checkbox").prop('checked', (response.data.hidden == 1 ? true: false));

                        id = response.data.id;
                        $('#edit-game-modal').modal('show');
                    } else {
                        //show alert
                        $("#edit-game-alert").empty();
                        $("#edit-game-alert").append(response.message);
                        $("#edit-game-alert").show();
                    }
                }
            });
        });

        //game edit save
        $("#edit-game-modal-save").click(function() {

            var game = {};
            game.id = id;
            game.query_engines_id = $("#edit-game-query-engine-select").val();
            game.full_name = $("#edit-game-name-text").val();
            game.folder_name = $("#edit-game-folder-text").val();
            game.glibc_version_min = $("#edit-game-glibc-text").val();
            game.hidden = $("#edit-game-hidden-checkbox").is(':checked') ? 1: 0;

            $.ajax({
                type: "PUT",
                url: 'db/games/' + id,
                data: game,
                contentType: 'json',
                dataType: 'json',
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                        //show alert
                        $("#edit-game-alert").empty();
                        $("#edit-game-alert").append(response.message);
                        $("#edit-game-alert").show();
                    }
                }
            });
        });

        //game delete click handler
        $('td > button[action="delete-game"]').click(function() {
            console.log('delete-game');
            $('#confirmation-delete-alert').hide();
            id = $(this).attr('game-id');
            table = 'games';
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

        //game add click handler
        $('button[action="add-game"]').click(function() {

            //hide alert
            $("#add-game-alert").hide();

            //clear
            $('#add-game-form').each(function() {
                this.reset();
            });

            //show
            $('#add-game-modal').modal('show');
        });

        //game add
        $("#add-game-modal-save").click(function() {

            var game = {};
            game.query_engines_id = $("#add-game-query-engine-select").val();
            game.full_name = $("#add-game-name-text").val();
            game.folder_name = $("#add-game-folder-text").val();
            game.glibc_version_min = $("#add-game-glibc-text").val();
            game.hidden = $("#add-game-hidden-checkbox").is(':checked') ? 1: 0;

            $.ajax({
                type: "POST",
                url: 'db/games',
                data: game,
                contentType: 'json',
                dataType: 'json',
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                         //show alert
                        $("#add-game-alert").empty();
                        $("#add-game-alert").append(response.message);
                        $("#add-game-alert").show();
                    }
                }
            });
        });
    });
});
