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
        <li><a data-target="#ssh-keys" data-toggle="tab">SSH Keys</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="games">
          <div class="btn-group pull-right">
            <a href="#" class="btn btn-success">Add Game</a>
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
                    <td ><?php echo $g['hidden'];?></td>
                    <td width="90"><a class="btn btn-sm btn-info" type="button">Edit</a></td>
                    <td width="90"><a class="btn btn-sm btn-danger" type="button">Delete</a></td>
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
                  <a href="#" class="btn btn-success">Add Service</a>
                </div>
                <h4 class="panel-title" style="padding-top: 7.5px;">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $cnt ?>" aria-expanded="true" aria-controls="collapse<?php echo $cnt ?>">
                    <?php echo $g;?>
                  </a>
                </h4>
              </div>
              <div id="collapse<?php echo $cnt ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $cnt ?>">
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
                        <td width="90"><a class="btn btn-sm btn-info" type="button">Edit</a></td>
                        <td width="90"><a class="btn btn-sm btn-danger" type="button">Delete</a></td>
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
            <a href="#" class="btn btn-success">Add Virtualbox Soap Server</a>
          </div>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>url</th>
                <th>username</th>
                <th>pw</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
<?php foreach($this->data['vbox_soap_endpoints'] as $e): ?>                
                  <tr>
                    <td><?php echo $e['url'];?></td>
                    <td><?php echo $e['username'];?></td>
                    <td><?php echo $e['password'];?></td>
                    <td width="90"><a class="btn btn-sm btn-info" type="button">Edit</a></td>
                    <td width="90"><a class="btn btn-sm btn-danger" type="button">Delete</a></td>
                  </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="tab-pane" id="github">
          <div class="btn-group pull-right">
            <a href="#" class="btn btn-success">Add Github Repo</a>
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
                    <td width="90"><a class="btn btn-sm btn-info" type="button">Edit</a></td>
                    <td width="90"><a class="btn btn-sm btn-danger" type="button">Delete</a></td>
                  </tr>
<?php endforeach; ?>
            </tbody>
          </table>

        </div>
        <div class="tab-pane" id="ssh-keys">sshkey</div>
      </div>

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  </body>
</html>