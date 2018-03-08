<?php
/**
 * Created by PhpStorm.
 * User: JAshMe
 * Date: 3/7/2018
 * Time: 4:17 AM
 */

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    require_once "includes/database.php";
    $db = new PDOdb("culrav2k18");
    $KEY = "6Qfm5exu";
    $SALT = "JcShuxWUzy";
    $hash ='';
    if(isset($_POST['hash']))
    {

        //checking if hash matches
        $data = array();
        $data['key'] = $KEY;
        $data['txnid']= htmlentities(trim(stripslashes( $_POST['txnid'])));
        $data['firstname'] = htmlentities(trim(stripslashes($_POST['firstname'])));
        $data['email'] = htmlentities(trim(stripslashes($_POST['email'])));
        $data['amount'] = htmlentities(trim(stripslashes($_POST['amount'])));
        $data['phone'] = htmlentities(trim(stripslashes($_POST['phone'])));
        $data['productinfo'] = htmlentities(trim(stripslashes($_POST['productinfo'])));
        $data['hash'] = htmlentities(trim(stripslashes($_POST['hash'])));
        $data['payuMoneyId'] = htmlentities(trim(stripslashes($_POST['payuMoneyId'])));
        $data['status'] = htmlentities(trim(stripslashes($_POST['status'])));
        $data['mode'] =htmlentities(trim(stripslashes( $_POST['mode'])));
        $data['bankcode'] =htmlentities(trim(stripslashes( $_POST['mode'])));

        $hashSequence= "status||||||udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key";
        $hashVarsSeq = explode('|', $hashSequence);
        $hash_string = '';
        $hash_string .= $SALT;
        foreach($hashVarsSeq as $hash_var)
        {
            $hash_string .= '|';
            $hash_string .= isset($data[$hash_var]) ? $data[$hash_var] : '';
        }
       // echo $hash_string;


        $hash = strtolower(hash('sha512', $hash_string));
//        echo "<br>Computed Hash=".$hash."<br>";
//        echo "Received Hash=".$data['hash']."<br>";

        if($hash==$data['hash'])
        {

                //store verdict in database
                $prepared  = "update transac_details set trans_no=?,status=?,amount=?,mode=?,bank_code=? where Ref_no=? and email=?";
                $params = array($data['payuMoneyId'],$data['status'],$data['amount'],$data['mode'],$data['bankcode'],$data['txnid'],$data['email']);
                $db->ins_del_query($prepared,$params);



                echo "<h2>SUCCESS</h2><br>";
        }
        else
           die("Something went wrong. Please try again.<br><a href='apply.php'>Back to Home Page</a> ");
    }
}
else
    die("<h2>Unauthorised Access!!!</h2>");

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
    <head>
        <title>Transaction Verdict</title>
    </head>
    <body>
        <table>
            <tr>
                <td>Status:</td>
                <td><?= $data['status']?></td>
            </tr>            <tr>
                <td>First Name:</td>
                <td><?= $data['firstname']?></td>
            </tr>
            <tr>
                <td><strong>Reference ID:</strong></td>
                <td><?= $data['txnid']?></td>
            </tr>
            <tr>
                <td><strong>Transaction ID:</strong></td>
                <td><?= $data['payuMoneyId']?></td>
            </tr>
            <tr>
                <td>Purpose:</td>
                <td><?= $data['productinfo']?></td>
            </tr>
            <tr>
                <td>Amount:</td>
                <td><?= $data['amount']?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?= $data['email']?></td>
            </tr>
            <tr>
                <td>Mobile Number:</td>
                <td><?= $data['phone']?></td>
            </tr>
        </table>
        <br>
        <br>
        <a href="../index.html">Back to homepage</a>
    </body>

</html>