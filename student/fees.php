<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$sid=$_POST['sid'];
$username=$_POST['username'];
$token=$_POST['token'];
$type='staff';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $q="select * from apk_token where token='$token' and user='$username'";
    $s=mysqli_query($host,$q);
    $r=mysqli_num_rows($s);
    if($r==1){
    $arr['status']=true;
    $arr['message']='success';
    $arr['data']=array();
    $s=mysqli_query($host,"select feesdue.*,erp_category.name as cname,erp_group.name as gname from feesdue INNER JOIN erp_category INNER JOIN erp_group ON erp_group.id=erp_category.type and erp_category.id=feesdue.cateid where feesdue.sid='$sid' and feesdue.cancel='No' order by feesdue.rdate desc");
    while($d=mysqli_fetch_array($s)){
        $due+=$d['debit'];
        $rece+=$d['credit'];
     $row["type"]=$d['voucher'];
     $row["debit"]=$d['debit'];
     $row["credit"]=$d['credit'];
     $row["invoice"]=$d['invoice'];
     $row["rdate"]=date('d M',strtotime($d['rdate']));
     $row["mode"]=$d['mode'];
     $row["remark"]=$d['remark'];
     $row["cname"]=$d['cname'];
     $row["gname"]=$d['gname'];
     $arr['data'][]=$row;
    }
    
    $arr['tdue']=$due; 
    $arr['trece']=$rece;
    $s1=mysqli_query($host,"select sum(debit),sum(credit) from feesdue where sid='$sid' and cancel='No' and rdate<='$date'");
    $d1=mysqli_fetch_array($s1);
    $arr['overdue']=$d1[0]-$d1[1];
    
    
    }else{
    $arr['status']=false;
    $arr['message']='Token Not Match';
}

echo $json = json_encode($arr);

}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}










?>