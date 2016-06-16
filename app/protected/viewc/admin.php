<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>lgsm-ui</title>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <style type="text/css">
      .jumbotron {
        margin-top: 80px;
        border-radius: 0;
        text-align: center;
      }
      .table td {
         text-align: center;   
      }
      .table th {
         text-align: center;   
      }
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">lgsm-ui</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="status">Status</a></li>
            <li><a href="deploy">Deploy</a></li>
            <li class="active"><a href="admin">Admin</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
      <div class="jumbotron">
        <h1>Configure Settings</h1>
      </div>
      <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a data-target="#games" data-toggle="tab">Games</a></li>
        <li><a data-target="#services" data-toggle="tab">Services</a></li>
        <li><a data-target="#virtualbox" data-toggle="tab">VirtualBox</a></li>
        <li><a data-target="#github" data-toggle="tab">GitHub</a></li>
        <li><a data-target="#base-images" data-toggle="tab">Base Images</a></li>
        <li><a data-target="#gearman" data-toggle="tab">Gearman Servers</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="games">
          <div class="btn-group pull-right">
            <button class="btn btn-success" action="add-game">Add Game</a>
          </div>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Git Folder Path</th>
                <th>Hidden</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
<?php foreach($this->data['games'] as $g): ?>                
                  <tr>
                    <td ><?php echo $g['full_name'];?></td>
                    <td ><?php echo $g['folder_name'];?></td>
                    <td ><?php echo ($g['hidden'] == 1) ? "true" : "false"; ;?></td>
                    <td width="90"><button class="btn btn-sm btn-info" action="edit-game" game-id="<?php echo $g['id'];?>" type="button">Edit</a></td>
                    <td width="90"><button class="btn btn-sm btn-danger" action="delete-game" game-id="<?php echo $g['id'];?>" type="button">Delete</a></td>
                  </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="tab-pane" id="services">
          <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?php $cnt=0; foreach($this->data['services'] as $g => $services): ?>
            <div class="panel panel-primary">
              <div class="panel-heading clearfix" role="tab" id="heading<?php echo $cnt ?>">
                <div class="btn-group pull-right">
                  <button class="btn btn-success" action="add-service" game-id="<?php echo explode("|", $g)[1];?>">Add Service</a>
                </div>
                <h4 class="panel-title" style="padding-top: 7.5px;">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $cnt ?>" aria-expanded="true" aria-controls="collapse<?php echo $cnt ?>">
                    <?php echo explode("|", $g)[0] . " (" . count($services) . ")";?>
                  </a>
                </h4>
              </div>
              <div id="collapse<?php echo $cnt ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $cnt ?>">
                <div class="panel-body">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Script Name</th>
                        <th>Port</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                     <tbody>
<?php foreach($services as $s): ?>                
                      <tr>
                        <td><?php echo $s['script_name'];?></td>
                        <td><?php echo $s['port'];?></td>
                        <td width="90"><button class="btn btn-sm btn-info" action="edit-service" service-id="<?php echo $s['id'];?>" type="button">Edit</a></td>
                        <td width="90"><button class="btn btn-sm btn-danger" action="delete-service" service-id="<?php echo $s['id'];?>" type="button">Delete</a></td>
                      </tr>
<?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>        
<?php $cnt++; endforeach; ?>
          </div>
        </div>
        <div class="tab-pane" id="virtualbox">
          <div class="btn-group pull-right">
            <button class="btn btn-success" action="add-vbox">Add Virtualbox Soap Server</a>
          </div>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>url</th>
                <th>username</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
<?php foreach($this->data['vbox_soap_endpoints'] as $e): ?>                
                  <tr>
                    <td><?php echo $e['url'];?></td>
                    <td><?php echo $e['username'];?></td>
                    <td width="90"><button class="btn btn-sm btn-info" action="edit-vbox" vbox-id="<?php echo $e['id'];?>" type="button">Edit</a></td>
                    <td width="90"><button class="btn btn-sm btn-danger" action="delete-vbox" vbox-id="<?php echo $e['id'];?>" type="button">Delete</a></td>
                  </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="tab-pane" id="github">
          <div class="btn-group pull-right">
            <button class="btn btn-success" action="add-github">Add Github Repo</a>
          </div>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>url</th>
                <th>branch</th>
                <th>username</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
<?php foreach($this->data['gits'] as $g): ?>                
                  <tr>
                    <td><?php echo $g['url'];?></td>
                    <td><?php echo $g['branch'];?></td>
                    <td><?php echo $g['username'];?></td>
                    <td width="90"><button class="btn btn-sm btn-info" action="edit-github" github-id="<?php echo $g['id'];?>" type="button">Edit</a></td>
                    <td width="90"><button class="btn btn-sm btn-danger" action="delete-github" github-id="<?php echo $g['id'];?>" type="button">Delete</a></td>
                  </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="tab-pane" id="base-images">
          <div class="btn-group pull-right">
            <button class="btn btn-success" action="add-baseimage">Add Image</a>
          </div>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Virtual Box VM</th>
                <th>Architecture</th>
                <th>GLIBC version</th>
                <th>SSH Username</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
<?php foreach($this->data['base_images'] as $i): ?>                
                  <tr>
                    <td><?php echo $i['name'];?></td>
                    <td><?php echo $i['architecture'];?></td>
                    <td><?php echo $i['glibc_version'];?></td>
                    <td><?php echo $i['username'];?></td>
                    <td width="90"><button class="btn btn-sm btn-info" action="edit-baseimage" baseimage-id="<?php echo $i['id'];?>" type="button">Edit</a></td>
                    <td width="90"><button class="btn btn-sm btn-danger" action="delete-baseimage" baseimage-id="<?php echo $i['id'];?>" type="button">Delete</a></td>
                  </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="tab-pane" id="gearman">
          <div class="btn-group pull-right">
            <button class="btn btn-success" action="add-gearman">Add Gearman Server</a>
          </div>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Hostname</th>
                <th>Port</th>
                <th>Enabled</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
<?php foreach($this->data['gearman_job_servers'] as $i): ?>                
                  <tr>
                    <td><?php echo $i['hostname'];?></td>
                    <td><?php echo $i['port'];?></td>
                    <td><?php echo $i['enabled'];?></td>
                    <td width="90"><button class="btn btn-sm btn-info" action="edit-gearman" gearman-id="<?php echo $i['id'];?>" type="button">Edit</a></td>
                    <td width="90"><button class="btn btn-sm btn-danger" action="delete-gearman" gearman-id="<?php echo $i['id'];?>" type="button">Delete</a></td>
                  </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div><!-- /container -->

    <!-- confirmation delete modal -->
    <div class="modal fade" id="confirmation-delete-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Confirmation</h4>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete?</p>
          </div>
          <div class="modal-footer">
            <div id="confirmation-delete-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a id="continue-delete-button" href="#" class="btn btn-danger">Delete</a>
          </div>
        </div>
      </div>
    </div>

    <!-- game edit modal -->
    <div class="modal fade" id="edit-game-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Game Config</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
              <div class="form-group">
                <label for="edit-game-name-text" class="col-sm-3 control-label">Name</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-game-name-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-game-folder-text" class="col-sm-3 control-label">Github Folder</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-game-folder-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-game-glibc-text" class="col-sm-3 control-label">GLIBC Min Version</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-game-glibc-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-game-query-engine-select" class="col-sm-3 control-label">Query Engine</label>
                <div class="col-sm-8">
                  <select class="form-control" id="edit-game-query-engine-select">
<?php foreach($this->data['query_engines'] as $q) : ?>
                    <option value="<?php echo $q['id'];?>"><?php echo $q['name'];?></option>
<?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-8">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="edit-game-hidden-checkbox">Hidden</label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="edit-game-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" id="edit-game-modal-save" class="btn btn-primary">Save</a>
          </div>
        </div>
      </div>
    </div>

    <!-- game add modal -->
    <div class="modal fade" id="add-game-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Game Config</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
              <div class="form-group">
                <label for="add-game-name-text" class="col-sm-3 control-label">Name</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-game-name-text">
                </div>
              </div>
              <div class="form-group">
                <label for="add-game-folder-text" class="col-sm-3 control-label">Github Folder</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-game-folder-text">
                </div>
              </div>
              <div class="form-group">
                <label for="add-game-glibc-text" class="col-sm-3 control-label">GLIBC Min Version</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-game-glibc-text">
                </div>
              </div>
               <div class="form-group">
                <label for="add-game-query-engine-select" class="col-sm-3 control-label">Query Engine</label>
                <div class="col-sm-8">
                  <select class="form-control" id="add-game-query-engine-select">
<?php foreach($this->data['query_engines'] as $q) : ?>
                    <option value="<?php echo $q['id'];?>"><?php echo $q['name'];?></option>
<?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-8">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="add-game-hidden-checkbox">Hidden</label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="add-game-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" id="add-game-modal-save" class="btn btn-primary">Add</a>
          </div>
        </div>
      </div>
    </div>

    <!-- service edit modal -->
    <div class="modal fade" id="edit-service-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Service Config</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
              <div class="form-group">
                <label for="edit-service-game-select" class="col-sm-3 control-label">Game</label>
                <div class="col-sm-8">
                  <select class="form-control" id="edit-service-game-select">
<?php foreach($this->data['games'] as $g) : ?>
                    <option value="<?php echo $g['id'];?>"><?php echo $g['full_name'];?></option>
<?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="edit-service-name-text" class="col-sm-3 control-label">GitHub Script Name</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-service-name-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-service-port-text" class="col-sm-3 control-label">Port</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-service-port-text">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-8">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="edit-service-default-checkbox">Default</label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="edit-service-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" id="edit-service-modal-save" class="btn btn-primary">Save</a>
          </div>
        </div>
      </div>
    </div>

    <!-- service add modal -->
    <div class="modal fade" id="add-service-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Service Config</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
              <div class="form-group">
                <label for="add-service-game-select" class="col-sm-3 control-label">Game</label>
                <div class="col-sm-8">
                  <select class="form-control" id="add-service-game-select">
<?php foreach($this->data['games'] as $g) : ?>
                    <option value="<?php echo $g['id'];?>"><?php echo $g['full_name'];?></option>
<?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="add-service-name-text" class="col-sm-3 control-label">GitHub Script Name</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-service-name-text">
                </div>
              </div>
              <div class="form-group">
                <label for="add-service-port-text" class="col-sm-3 control-label">Port</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-service-port-text">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-8">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="add-service-default-checkbox">Default</label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="add-service-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" id="add-service-modal-save" class="btn btn-primary">Add</a>
          </div>
        </div>
      </div>
    </div>

    <!-- vbox edit modal -->
    <div class="modal fade" id="edit-vbox-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">VirtualBox Config</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
             <div class="form-group">
                <label for="edit-vbox-url-text" class="col-sm-3 control-label">URL</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-vbox-url-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-vbox-username-text" class="col-sm-3 control-label">Username</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-vbox-username-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-vbox-password-password" class="col-sm-3 control-label">Password</label>
                <div class="col-sm-8">
                  <input type="password" class="form-control" id="edit-vbox-password-password">
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="edit-vbox-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" id="edit-vbox-modal-save" class="btn btn-primary">Save</a>
          </div>
        </div>
      </div>
    </div>

    <!-- vbox add modal -->
    <div class="modal fade" id="add-vbox-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">VirtualBox Config</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
              <div class="form-group">
                <label for="add-vbox-url-text" class="col-sm-3 control-label">URL</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-vbox-url-text">
                </div>
              </div>
              <div class="form-group">
                <label for="add-vbox-username-text" class="col-sm-3 control-label">Username</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-vbox-username-text">
                </div>
              </div>
              <div class="form-group">
                <label for="add-vbox-password-password" class="col-sm-3 control-label">Password</label>
                <div class="col-sm-8">
                  <input type="password" class="form-control" id="add-vbox-password-password">
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="add-vbox-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" id="add-vbox-modal-save" class="btn btn-primary">Add</a>
          </div>
        </div>
      </div>
    </div>
    
    <!-- github edit modal -->
    <div class="modal fade" id="edit-github-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Github Config</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
             <div class="form-group">
                <label for="edit-github-url-text" class="col-sm-3 control-label">URL</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-github-url-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-github-branch-text" class="col-sm-3 control-label">Branch</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-github-branch-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-github-username-text" class="col-sm-3 control-label">Username</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-github-username-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-github-sshkey-textarea" class="col-sm-3 control-label">SSH Key</label>
                <div class="col-sm-8">
                  <textarea class="form-control" rows="5" id="edit-github-sshkey-textarea"></textarea>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="edit-github-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" id="edit-github-modal-save" class="btn btn-primary">Save</a>
          </div>
        </div>
      </div>
    </div>

    <!-- github add modal -->
    <div class="modal fade" id="add-github-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Github Config</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
             <div class="form-group">
                <label for="add-github-url-text" class="col-sm-3 control-label">URL</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-github-url-text">
                </div>
              </div>
              <div class="form-group">
                <label for="add-github-branch-text" class="col-sm-3 control-label">Branch</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-github-branch-text">
                </div>
              </div>
              <div class="form-group">
                <label for="add-github-username-text" class="col-sm-3 control-label">Username</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-github-username-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-github-sshkey-textarea" class="col-sm-3 control-label">SSH Key</label>
                <div class="col-sm-8">
                  <textarea class="form-control" rows="5" id="add-github-sshkey-textarea"></textarea>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="add-github-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" id="add-github-modal-save" class="btn btn-primary">Add</a>
          </div>
        </div>
      </div>
    </div>

    <!-- baseimage edit modal -->
    <div class="modal fade" id="edit-baseimage-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Base Image Config</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
              <div class="form-group">
                <label for="edit-baseimage-vbox-select" class="col-sm-3 control-label">Virtual Box Instance</label>
                <div class="col-sm-8">
                  <select class="form-control" id="edit-baseimage-vbox-select">
<?php foreach($this->data['vbox_soap_endpoints'] as $v) : ?>
                    <option value="<?php echo $v['id'];?>"><?php echo $v['url'];?></option>
<?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="edit-baseimage-name-text" class="col-sm-3 control-label">Virtual Box VM</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-baseimage-name-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-baseimage-arch-select" class="col-sm-3 control-label">Architecture</label>
                <div class="col-sm-8">
                    <select class="form-control" id="edit-baseimage-arch-select">
                      <option>32 bit</option>
                      <option>64 bit</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="edit-baseimage-glibc-text" class="col-sm-3 control-label">GLIBC Version</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-baseimage-glibc-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-baseimage-username-text" class="col-sm-3 control-label">SSH Username</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-baseimage-username-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-baseimage-password-text" class="col-sm-3 control-label">SSH Password</label>
                <div class="col-sm-8">
                  <input type="password" class="form-control" id="edit-baseimage-password-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-baseimage-sshkey-textarea" class="col-sm-3 control-label">SSH Key</label>
                <div class="col-sm-8">
                  <textarea class="form-control" rows="5" id="edit-baseimage-sshkey-textarea"></textarea>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="edit-baseimage-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" id="edit-baseimage-modal-save" class="btn btn-primary">Save</a>
          </div>
        </div>
      </div>
    </div>

    <!-- baseimage add modal -->
    <div class="modal fade" id="add-baseimage-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Base Image Config</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
              <div class="form-group">
                <label for="add-baseimage-vbox-select" class="col-sm-3 control-label">Virtual Box Instance</label>
                <div class="col-sm-8">
                  <select class="form-control" id="add-baseimage-vbox-select">
<?php foreach($this->data['vbox_soap_endpoints'] as $v) : ?>
                    <option value="<?php echo $v['id'];?>"><?php echo $v['url'];?></option>
<?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="add-baseimage-name-text" class="col-sm-3 control-label">Virtual Box VM</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-baseimage-name-text">
                </div>
              </div>
              <div class="form-group">
                <label for="add-baseimage-arch-select" class="col-sm-3 control-label">Architecture</label>
                <div class="col-sm-8">
                    <select class="form-control" id="add-baseimage-arch-select">
                      <option>32 bit</option>
                      <option>64 bit</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="add-baseimage-glibc-text" class="col-sm-3 control-label">GLIBC Version</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-baseimage-glibc-text">
                </div>
              </div>
              <div class="form-group">
                <label for="add-baseimage-username-text" class="col-sm-3 control-label">SSH Username</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-baseimage-username-text">
                </div>
              </div>
              <div class="form-group">
                <label for="add-baseimage-password-text" class="col-sm-3 control-label">SSH Password</label>
                <div class="col-sm-8">
                  <input type="password" class="form-control" id="add-baseimage-password-text">
                </div>
              </div>
              <div class="form-group">
                <label for="add-baseimage-sshkey-textarea" class="col-sm-3 control-label">SSH Key</label>
                <div class="col-sm-8">
                  <textarea class="form-control" rows="5" id="add-baseimage-sshkey-textarea"></textarea>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="add-baseimage-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" id="add-baseimage-modal-save" class="btn btn-primary">Add</a>
          </div>
        </div>
      </div>
    </div>

    <!-- gearman edit modal -->
    <div class="modal fade" id="edit-gearman-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Gearman Config</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
              <div class="form-group">
                <label for="edit-gearman-hostname-text" class="col-sm-3 control-label">Hostname</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-gearman-hostname-text">
                </div>
              </div>
              <div class="form-group">
                <label for="edit-gearman-port-text" class="col-sm-3 control-label">Port</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="edit-gearman-port-text">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-8">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="edit-gearman-enaabled-checkbox">Enabled</label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="edit-gearman-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" id="edit-gearman-modal-save" class="btn btn-primary">Save</a>
          </div>
        </div>
      </div>
    </div>

    <!-- gearman add modal -->
    <div class="modal fade" id="add-gearman-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Gearman Config</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
              <div class="form-group">
                <label for="add-gearman-hostname-text" class="col-sm-3 control-label">Hostname</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-gearman-hostname-text" placeholder="localhost">
                </div>
              </div>
              <div class="form-group">
                <label for="add-gearman-port-text" class="col-sm-3 control-label">Port</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-gearman-port-text" placeholder="4730">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-8">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="add-gearman-enaabled-checkbox">Enabled</label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="add-gearman-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" id="add-gearman-modal-save" class="btn btn-primary">Add</a>
          </div>
        </div>
      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="global/js/admin-game.js"></script>
    <script src="global/js/admin-service.js"></script>
    <script src="global/js/admin-vbox.js"></script>
    <script src="global/js/admin-github.js"></script>
    <script src="global/js/admin-baseimages.js"></script>
    <script src="global/js/admin-gearman.js"></script>
  </body>
</html>