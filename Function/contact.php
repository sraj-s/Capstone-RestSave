<?php
$con = mysqli_connect('localhost', 'root', '', 'loginPro');

if(!$con) {
    echo 'Connection Error';
}

//mysql_select_db("test");

$companyname =$_POST['companyname'];
$organizationtype= $_POST['organizationtype'];
$name=$_POST['name'];
$phonenumber=$_POST['phonenumber'];
$emailaddress=$_POST['emailaddress'];



$sql ="INSERT INTO datas (Company_Name, Organization_Type, Users_Fullname, Phone_Number, Email_Address) VALUES ('$companyname' ,'$organizationtype', '$name', '$phonenumber', '$emailaddress')";
//$sql=mysql_query($select);
$rs = mysqli_query($con, $sql);
if($rs)
{
	echo "Contact Records Inserted";
}
//print '<script type= "text/javascript">';
//print 'alert("The data is inserted...")';
//print '</script>';

//mysqli_close();
?>