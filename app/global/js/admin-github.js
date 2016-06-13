$(function() {
    $(document).ready(function() {
        console.log('admin-github');

        //js global vars
        var id;
        var table;

        //github edit click handler
        $('td > button[action="edit-github"]').click(function() {

            //hide alert
            $("#edit-github-alert").hide();

            //get github-id
            github_id = $(this).attr('github-id');
            $.ajax({
                type: "GET",
                url: 'db/github/' + github_id,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#edit-github-url-text").val(response.data.url);
                        $("#edit-github-branch-text").val(response.data.branch);
                        $("#edit-github-username-text").val(response.data.username);
                        $("#edit-github-sshkey-textarea").val(response.data.ssh_key);
                        id = response.data.id;
                        $('#edit-github-modal').modal('show');
                    } else {
                        //show alert
                        $("#edit-github-alert").empty();
                        $("#edit-github-alert").append(response.message);
                        $("#edit-github-alert").show();
                    }
                }
            });
        });

        //github edit save
        $("#edit-github-modal-save").click(function() {

            var github = {};
            github.id = id;
            github.url = $("#edit-github-url-text").val();
            github.branch = $("#edit-github-branch-text").val();
            github.username = $("#edit-github-username-text").val();
            github.ssh_key = $("#edit-github-sshkey-textarea").val();
        
            $.ajax({
                type: "PUT",
                url: 'db/github/' + id,
                data: github,
                contentType: 'json',
                dataType: 'json',
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                        //show alert
                        $("#edit-github-alert").empty();
                        $("#edit-github-alert").append(response.message);
                        $("#edit-github-alert").show();
                    }
                }
            });
        });

        //github delete click handler
        $('td > button[action="delete-github"]').click(function() {
            console.log('delete-github');
            $('#confirmation-delete-alert').hide();
            id = $(this).attr('github-id');
            table = 'github';
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

        //github add click handler
        $('button[action="add-github"]').click(function() {
            console.log('add-github');

            //hide alert
            $("#add-github-alert").hide();

            //clear
            $('#add-github-form').each(function() {
                this.reset();
            });

            //show
            $('#add-github-modal').modal('show');
        });

        //github add
        $("#add-github-modal-save").click(function() {

            var github = {};
            github.id = id;
            github.url = $("#add-github-url-text").val();
            github.branch = $("#add-github-branch-text").val();
            github.username = $("#add-github-username-text").val();
            github.sshkey = $("#add-github-sshkey-textarea").val();
         
            $.ajax({
                type: "POST",
                url: 'db/github',
                data: github,
                contentType: 'json',
                dataType: 'json',
                success: function(response) {
                    if (response.success)
                        window.location.reload();
                    else{
                         //show alert
                        $("#add-github-alert").empty();
                        $("#add-github-alert").append(response.message);
                        $("#add-github-alert").show();
                    }
                }
            });
        });
    });
});
