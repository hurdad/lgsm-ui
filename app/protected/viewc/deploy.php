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

      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?php $cnt=0; foreach($this->data['games'] as $g => $vboxes): ?>
        <div class="panel panel-primary">
          <div class="panel-heading clearfix" role="tab" id="heading<?php echo $cnt ?>">
            <div class="btn-group pull-right">
              <a href="#" class="btn btn-success">Add Server</a>
            </div>
            <h4 class="panel-title" style="padding-top: 7.5px;">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $cnt ?>" aria-expanded="true" aria-controls="collapse<?php echo $cnt ?>">
                <?php echo $g; ?>
              </a>
            </h4>
 
          </div>
          <div id="collapse<?php echo $cnt ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $cnt ?>">
            <div class="panel-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>OS</th>
                    <th>Hostname</th>
                    <th>IP</th>
                    <th>Status</th>
                    <th>Services</th>
                    <th></th>
                  </tr>
                </thead>
                 <tbody>
<?php foreach($vboxes as $v): ?>                
                  <tr>
                    <td><?php echo isset($v['query']) ? $v['query']['OSTypeId'] :  "N/A";?></td>
                    <td><?php echo $v['data']['hostname'];?></td>
                    <td><?php echo $v['data']['ip'];?></td>
                    <td><?php echo isset($v['query']) ? $v['query']['state'] : "Unknown";?></td>
                    <td><?php echo $v['cnt']; ?></td>
                    <td width="300">
                      <div class="btn-group" role="group" aria-label="...">
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            VM Options
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="#">Start</a></li>
                            <li><a href="#">Stop</a></li>
                            <li><a href="#">Connect</a></li>
                            <li><a href="#">Resize</a></li>
                            <li><a href="#">Delete</a></li>
                          </ul>
                        </div>
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Service Options
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="#">Start All</a></li>
                            <li><a href="#">Stop All</a></li>
                            <li><a href="#">Edit</a></li>
                            <li><a href="#">Update</a></li>
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
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  </body>
</html>