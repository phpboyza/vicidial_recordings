<?php
    ini_set('display_errors','On');
    require_once("includes/config.inc.php");
?>

<html>
    <head>
        <title><?php echo $title; ?></title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    </head>
<body>
<?php

require_once("includes/db.inc.php");
require_once("includes/class.recordings.php");

$rec = new recordings($conn);

$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';
$status = $_GET['status'] ?? '';
$phone_number = $_GET['phone_number'] ?? '';
$phone_code = $_GET['phone_code'] ?? '';

$page = $_GET['page'] ? (int) $_GET['page'] : 1;

$recordings = $rec->get_recordings($campaign_ids,$from_date,$to_date,$status,$phone_code,$phone_number,$page);

$nextpage = false;
if(count($recordings) > 98){
    $page++;
    $nextpage = true;
}

echo '
    <h1>'.$title.'</h1>
    <div class="container" style="margin: 2px">  
        <div style="position: relative">
            <form role="form">
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label for="code">From Date</label>
                        <input type="text" class="form-control datetimepicker" name="from_date" value="'.$from_date.'"/>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="code">To Date</label>
                        <input type="text" class="form-control datetimepicker" name="to_date" value="'.$to_date.'"/>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-3">
                        <label for="code">Call Status</label>
                        <select name="status" class="form-control">
                            <option value=""></option>
                            ';
                                $stauses = $rec->get_statuses();
                                foreach($stauses as $s){
                                    echo '
                                        <option value="'.$s->status.'" '.($s->status == $status ? 'selected' : '').'>'.$s->status_name.'</option>
                                    ';
                                }
                            echo'
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <label for="code">Phone Code</label>
                        <input type="text" class="form-control" name="phone_code" value="'.$phone_code.'"/>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="code">Phone Number</label>
                        <input type="text" class="form-control" name="phone_number" value="'.$phone_number.'"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-1">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="col-lg-11 text-right">
                        '.($nextpage ? '<button type="submit" class="btn btn-success" name="page" value="'.$page.'">Next Page -></button>' : '').'
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr>
    <table class="table">
        <tr>
            <th></th>
            <th>Lead ID</th>
            <th>Start</th>
            <th>End</th>
            <th>Duration</th>
            <th>Agent</th>
            <th>Number</th>
            <th>Call Status</th>
            <th>Lead Status</th>
        </tr>
';

foreach($recordings as $r){
    echo "
        <tr>
            <td><a href='{$r->location}'><i class='fa-solid fa-download'></i></a></td>
            <td>{$r->lead_id}</td>
            <td>{$r->start_time}</td>
            <td>{$r->end_time}</td>
            <td>{$r->call_length}</td>
            <td>{$r->user}</td>
            <td>{$r->phone_number}</td>
            <td>{$r->call_status}</td>
            <td>{$r->lead_status}</td>
        </tr>
    ";
}

echo "</table>";
?>

<script>
    $(document).ready(function(){
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });
    });
</script>
</body>
</html>