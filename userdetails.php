<?php
include 'config.php';

if(isset($_POST['submit']))
{
    $from = $_GET['id'];
    $to = $_POST['to'];
    $amount = $_POST['amount'];

    $sql = "SELECT * from users where id=$from";
    $query = mysqli_query($conn,$sql);
    $sql1 = mysqli_fetch_array($query); // returns array or output of user from which the amount is to be transferred.

    $sql = "SELECT * from users where id=$to";
    $query = mysqli_query($conn,$sql);
    $sql2 = mysqli_fetch_array($query);



    // constraint to check input of negative value by user
    if (($amount)<0)
   {
        echo '<script type="text/javascript">';
        echo ' alert("Negative values cannot be entered")';  // showing an alert box.
        echo '</script>';
    }


  
    // constraint to check insufficient balance.
    else if($amount > $sql1['balance']) 
    {
        
        echo '<script type="text/javascript">';
        echo ' alert("Insufficient Balance")';  // showing an alert box.
        echo '</script>';
    }
    


    // constraint to check zero values
    else if($amount == 0){

         echo "<script type='text/javascript'>";
         echo "alert('Enter a valid amount to be transfered')";
        //  echo 'Enter a valid amount to be transfered';
         echo "</script>";
     }


    else {
        
                // deducting amount from sender's account
                $newbalance = $sql1['balance'] - $amount;
                $sql = "UPDATE users set balance=$newbalance where id=$from";
                mysqli_query($conn,$sql);
             

                // adding amount to reciever's account
                $newbalance = $sql2['balance'] + $amount;
                $sql = "UPDATE users set balance=$newbalance where id=$to";
                mysqli_query($conn,$sql);
                
                $sender = $sql1['name'];
                $receiver = $sql2['name'];
                $sql = "INSERT INTO transaction(`sender`, `receiver`, `balance`) VALUES ('$sender','$receiver','$amount')";
                $query=mysqli_query($conn,$sql);

                if($query){
                     echo "<script> alert('Transaction Successful');
                                     window.location='history.php';
                           </script>";
                    
                }

                $newbalance= 0;
                $amount =0;
        }
    
}
?>

<!DOCTYPE html>
<html lang="en" style="background: linear-gradient(to left, #ffffcc 11%, #ccffcc 100%)">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NB-TRANSFER MONEY</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/table.css">

    <style type="text/css">
    	
		button{
			border:none;
			background: #d9d9d9;
		}
	    button:hover{
			background-color:#777E8B;
			transform: scale(1.1);
			color:white;
		}

    </style>
</head>

<body style="background: linear-gradient(to left, #ffffcc 22%, #ccffcc 100%)">
 
<?php
  include 'navbar.php';
?>

	<div class="container" style = "border-style: solid;
        border-color: #000000; border-width: medium;">
        <h2 class="text-center pt-4">Transaction</h2>
            <?php
                include 'config.php';
                $sid=$_GET['id'];
                $sql = "SELECT * FROM  users where id=$sid";
                $result=mysqli_query($conn,$sql);
                if(!$result)
                {
                    echo "Error : ".$sql."<br>".mysqli_error($conn);
                }
                $rows=mysqli_fetch_assoc($result);
            ?>
            <form method="post" name="tcredit" class="tabletext" ><br>
        <div>
            <table class="table table-striped table-condensed table-bordered">
                <tr>
                    <th class="text-center" style = "border-style: solid;
        border-color: #000000; border-width: medium; color: rgb(143, 62, 62); vertical-align: middle;">ID</th>
                    <th class="text-center" style = "border-style: solid;
        border-color: #000000; border-width: medium; color: rgb(143, 62, 62); vertical-align: middle;"> NAME</th>
                    <th class="text-center" style = "border-style: solid;
        border-color: #000000; border-width: medium; color: rgb(143, 62, 62); vertical-align: middle;">EMAIL</th>
                    <th class="text-center" style = "border-style: solid;
        border-color: #000000; border-width: medium; color: rgb(143, 62, 62); vertical-align: middle;">BALANCE</th>
                </tr>
                <tr>
                    <td class="py-2" style = "border-style: solid;
        border-color: #000000; border-width: medium; color: rgb(143, 62, 62); vertical-align: middle; font-weight: bold;"><?php echo $rows['id'] ?></td>
                    <td class="py-2" style = "border-style: solid;
        border-color: #000000; border-width: medium; color: rgb(143, 62, 62); vertical-align: middle; font-weight: bold;"><?php echo $rows['name'] ?></td>
                    <td class="py-2" style = "border-style: solid;
        border-color: #000000; border-width: medium; color: rgb(143, 62, 62); vertical-align: middle; font-weight: bold;"><?php echo $rows['email'] ?></td>
                    <td class="py-2" style = "border-style: solid;
        border-color: #000000; border-width: medium; color: rgb(143, 62, 62); vertical-align: middle; font-weight: bold;"><?php echo $rows['balance'] ?></td>
                </tr>
            </table>
        </div>
        <br>
        <label><b>TRANSFER TO:</b></label>
        <select name="to" class="form-control" required style= "background: #4b5152; color: #ffffff">
            <option value="" disabled selected>RECEIVER</option>
            <?php
                include 'config.php';
                $sid=$_GET['id'];
                $sql = "SELECT * FROM users where id!=$sid";
                $result=mysqli_query($conn,$sql);
                if(!$result)
                {
                    echo "Error ".$sql."<br>".mysqli_error($conn);
                }
                while($rows = mysqli_fetch_assoc($result)) {
            ?>
                <option class="table" value="<?php echo $rows['id'];?>" >
                
                    <?php echo $rows['name'] ;?> (BALANCE: 
                    <?php echo $rows['balance'] ;?> ) 
               
                </option>
            <?php 
                } 
            ?>
            <div>
        </select>
        <br>
        <br>
            <label ><b >AMOUNT:</b></label>
            <input type="number" class="form-control" name="amount" required style= "background: #4b5152; color: #ffffff">   
            <br><br>
                <div class="text-center" >
            <button class="btn mt-3" name="submit" type="submit" id="myBtn">Transfer</button>
            <br></br>
            </div>
        </form>
    </div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>