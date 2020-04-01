<?php
$status = '';


clearData();
function clearData() {
    $GLOBALS['name'] = '';
    $GLOBALS['email'] = '';
    $GLOBALS['address'] = '';
    $GLOBALS['action'] = '';
    $GLOBALS['submit'] = 'Add Data';
    $GLOBALS['cancelButton'] = '';
}
function cancelButton() {
    $GLOBALS['cancelButton'] = "<button onclick='cancelUpdate()' class='btn btn-warning btn-block mb-3 text-light'>Cancel</button>";
}


if(isset($_GET['a'])){

    //delete data
    if($_GET['a']=='delete'){
        $i = $_GET['i'];

        // read json file
        $data = file_get_contents('guest_data.json');

        // decode json to associative array
        $json_arr = json_decode($data, true);
        unset($json_arr[$i]);
        // rebase array
        $json_arr = array_values($json_arr);

        // encode array to json and save to file
        file_put_contents('guest_data.json', json_encode($json_arr));
        echo "<script>alert('delete is successfully');
                location.assign('index.php');
            </script>";
        clearData();
    }else{
        //edit data
        $i = $_GET['i'];

        // read json file
        $data = file_get_contents('guest_data.json');

        // decode json to associative array
        $json_arr = json_decode($data, true);
        // $json_arr = $json_arr[$i];

        $name = $json_arr[$i]['name'];
        $email = $json_arr[$i]['email'];
        $address = $json_arr[$i]['address'];
        $action = "edit";
        $submit = 'Update Data';
        cancelButton();
    }
}
// When submit button is click
if(isset($_POST['submit']))
{
    // For edit process
    if($_POST['action']=='edit')
    {
         $i = $_GET['i'];
        // read file
        $data = file_get_contents('guest_data.json');

        // decode json to array
        $json_arr = json_decode($data, true);

        $json_arr[$i]['name'] = $_POST['name'];
        $json_arr[$i]['email'] = $_POST['email'];
        $json_arr[$i]['address'] = $_POST['address'];

        // encode array to json and save to file
        file_put_contents('guest_data.json', json_encode($json_arr));
        clearData();

    }else{
        //For Append process
        if(file_exists('guest_data.json'))
        {
            $current_data = file_get_contents('guest_data.json');
            $array_data = json_decode($current_data, true);
            $extra = array(
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'address' => $_POST['address']
            );
            $array_data[] = $extra;
            $final_data = json_encode($array_data);
            //Append data to json
            if(file_put_contents('guest_data.json', $final_data))
            {
               echo "<script>alert('Data is successfully appended')</script>";
            }
            $action = '';
        }else{
            $error =  "File not exists";
        }
    }
}
if(file_exists('guest_data.json'))
{
    $data = file_get_contents('guest_data.json');
    $data = json_decode($data, true);
    $dataSize = sizeof($data);
}else{
    $status = "1";
}

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="fontawesome/css/all.css" rel="stylesheet"> <!--load all styles -->

    <title>Append Data to JSON File</title>
  </head>
  <body>
    <div class="container">

        <h3 class="text-center mt-3">Append Data to JSON File</h3>
        <hr/>
        <?php
            if(isset($error)){
                echo "<div style='color: red'>$error</div>";
            }
        ?>
        <form method="POST" action="">
            <div class="form-group row">
                <input type="hidden" name="action" value="<?=$action;?>">
                <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputName" placeholder="Name" name="name" required value="<?= $name;?>"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                <input type="email" class="form-control" id="inputEmail" placeholder="Email" name="email" required value="<?=$email;?>"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputAddress" class="col-sm-2 col-form-label">Address</label>
                <div class="col-sm-10">
                    <textarea name="address" class="form-control" id="inputAddress2" placeholder="Address" required><?=$address;?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <input type="submit" class="btn btn-primary btn-block" name="submit" value="<?=$submit;?>"/>
                    
                </div>
            </div>
        </form>
        <?php if(isset($cancelButton)){echo$cancelButton;}?>
        <table class="table">
            <thead class="thead-dark">
                <th>NO</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>ADDRESS</th>
                <th>ACTION</th>
            </thead>
            <tbody>
                <?php 
                    if($dataSize>0){
                        foreach($data as $key=>$value) : ?>
                            <tr>
                                <td><?= $key+1;?></td>
                                <td><?= $value['name'];?></td>
                                <td><?= $value['email'];?></td>
                                <td><?= $value['address'];?></td>
                                <td><a href="?a=delete&i=<?= $key;?>" onclick="return confirm('Are you sure to delete this data?')" class="fas fa-trash-alt btn btn-primary"></a>
                                <a href="?a=edit&i=<?= $key;?>" class="fas fa-edit btn btn-success"></a></td>
                            </tr>
                        <?php endforeach;
                    }else{ ?>
                        <tr>
                            <td colspan="4" class="text-center">Empty data</td>
                        </tr>
                    <?php } ?>
            </tbody>
        </table>
    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
    function cancelUpdate() {
        location.replace('index.php');
    }
    </script>
  </body>
</html>