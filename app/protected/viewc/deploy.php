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
            <li class="active"><a href="deploy">Deploy</a></li>
            <li><a href="admin">Admin</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
      <div class="jumbotron">
        <h1>Deploy Game Servers</h1>
      </div>
      <div id="vm-alert" class="alert alert-danger"></div>
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?php $cnt=0; foreach($this->data['games'] as $g => $vboxes): ?>
        <div class="panel panel-primary">
          <div class="panel-heading clearfix" role="tab" id="heading<?php echo $cnt ?>">
            <div class="btn-group pull-right">
              <button class="btn btn-success" action="add-vm" game-id="<?php echo explode("|", $g)[1];?>">Add Server</a>
            </div>
            <h4 class="panel-title" style="padding-top: 7.5px;">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $cnt ?>" aria-expanded="true" aria-controls="collapse<?php echo $cnt ?>">
                <?php echo explode("|", $g)[0] . " - " . count($vboxes);?>
              </a>
            </h4>
 
          </div>
          <div id="collapse<?php echo $cnt ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $cnt ?>">
            <div class="panel-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>OS</th>
                    <th>Virtualbox Name</th>
                    <th>IP</th>
                    <th>Deploy Status</th>
                    <th>VM Status</th>
                    <th>Assigned Services</th>
                    <th></th>
                  </tr>
                </thead>
                 <tbody>
<?php foreach($vboxes as $v): ?>                
                  <tr>
                    <td><?php echo isset($v['query']) ? $v['query']['OSTypeId'] :  "N/A";?></td>
                    <td><?php echo $v['data']['hostname'];?></td>
                    <td><?php echo $v['data']['ip'];?></td>
                    <td><?php echo $v['data']['deploy_status'];?></td>
                    <td><?php echo isset($v['query']) ? $v['query']['state'] : "Unknown";?></td>
                    <td><?php echo $v['cnt']; ?></td>
                    <td width="300">
                      <div class="btn-group" role="group" aria-label="...">
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            VM Options
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu" id="vm-options">
                            <li op="start" vm-id="<?php echo $v['data']['id'] ?>"><a href="#">Start</a></li>
                            <li op="stop" vm-id="<?php echo $v['data']['id'] ?>"><a href="#">Stop</a></li>
                            <li vm-id="<?php echo $v['data']['id'] ?>"><a href="ssh://<?php echo $v['data']['ssh_username'] . "@" .  $v['data']['ip']; ?>">SSH Access</a></li>
                            <li action="resize" vm-id="<?php echo $v['data']['id'] ?>"><a href="#">Resize</a></li>
                            <li action="delete" vm-id="<?php echo $v['data']['id'] ?>"><a href="#">Delete</a></li>
                          </ul>
                        </div>
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Service Options
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu" id="service-options">
                            <li op="startall" vm-id="<?php echo $v['data']['id'] ?>"><a href="#">Start All</a></li>
                            <li op="stopall" vm-id="<?php echo $v['data']['id'] ?>"><a href="#">Stop All</a></li>
                            <li vm-id="<?php echo $v['data']['id'] ?>"><a href="#">Edit</a></li>
                            <li op="update" vm-id="<?php echo $v['data']['id'] ?>"><a href="#">Update</a></li>
                          </ul>
                        </div>
                      </div>
                    </td>
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

    <!-- vm add modal -->
    <div class="modal fade" id="add-vm-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Deploy VM</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
              <div class="form-group">
                <label for="add-vm-game-select" class="col-sm-3 control-label">Game</label>
                <div class="col-sm-8">
                  <select class="form-control" id="add-vm-game-select" disabled>
<?php foreach($this->data['games'] as $g => $vboxes) : ?>
                    <option value="<?php echo explode("|", $g)[1];?>"><?php echo explode("|", $g)[0];?></option>
<?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="add-vm-vbox-select" class="col-sm-3 control-label">Virtual Box</label>
                <div class="col-sm-8">
                  <select class="form-control" id="add-vm-vbox-select">
<?php foreach($this->data['vbox_soap_endpoints'] as $v) : ?>
                    <option value="<?php echo $v['id'];?>"><?php echo $v['url'];?></option>
<?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="add-vm-image-select" class="col-sm-3 control-label">Base Image</label>
                <div class="col-sm-8">
                  <select class="form-control" id="add-vm-image-select">
<?php foreach($this->data['base_images'] as $i) : ?>
                    <option value="<?php echo $i['id'];?>"><?php echo $i['name'];?></option>
<?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="add-vm-service-select" class="col-sm-3 control-label">Services</label>
                <div class="col-sm-8">
                  <select multiple class="form-control" id="add-vm-service-select">
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="add-vm-github-select" class="col-sm-3 control-label">GitHub Repo</label>
                <div class="col-sm-8">
                  <select class="form-control" id="add-vm-github-select">
<?php foreach($this->data['gits'] as $g) : ?>
                    <option value="<?php echo $g['id'];?>"><?php echo $g['url'];?></option>
<?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="add-vm-cpu-text" class="col-sm-3 control-label">CPU</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-vm-cpu-text" placeholder="1">
                </div>
              </div>
              <div class="form-group">
                <label for="add-vm-mem-text" class="col-sm-3 control-label">Memory (MB)</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="add-vm-mem-text" placeholder="1024">
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="add-vm-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" id="add-vm-modal-save" class="btn btn-primary">Deploy + Install</a>
          </div>
        </div>
      </div>
    </div>

    <!-- resize modal -->
    <div class="modal fade" id="resize-modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Resize Config</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
             <div class="form-group">
                <label for="resize-cpu-text" class="col-sm-3 control-label">CPU</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="resize-cpu-text">
                </div>
              </div>
              <div class="form-group">
                <label for="resize-mem-text" class="col-sm-3 control-label">Memory</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="resize-mem-text">
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="resize-alert" class="alert alert-danger"></div>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" id="resize-modal-save" class="btn btn-primary">Save</a>
          </div>
        </div>
      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="global/js/deploy.js"></script>
  </body>
</html>