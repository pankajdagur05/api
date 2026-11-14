<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$search=$obj['search'];
$fromdate=$obj['fromdate'];
$todate=$obj['todate'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$x=$obj['fromdate'];
$y=$obj['todate'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){ 
         $arr['status']=true;
    $arr['message']='success';
    $arr['data']=array();
        $s=mysqli_query($host,"select count(attendance.id) as mm,students.name,students.fname,students.standard,students.smobile from attendance INNER JOIN students ON students.id=attendance.sid where attendance.idate BETWEEN '$x' and '$y' and attendance.subdomain='$user' and attendance.status='a' group by attendance.sid order by mm desc");     
						while($d=mysqli_fetch_array($s)){
						    
             $row['name']=$d['name'];
             $row['fname']=$d['fname'];
             $row['standard']=$d['standard'];
              $row['mobile']=$d['smobile'];
               $row['count']=$d[0];
             $arr['data'][]=$row;
             
         }
        
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