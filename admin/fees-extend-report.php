<?php
include("../../db.php");  
$username=$_POST['userid'];
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$branch=$_POST['branch'];
$standard=$_POST['standard'];
$branchid=$_POST['branchid'];
$type=$_POST['typefirst'];
$session=$_POST['session'];
$userid=$_POST['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
$staff=$_POST['staff'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
 
$arr['status']=true;
$arr['message']="success";
$arr['data']=array();
if($staff){
 
 $s1=mysqli_query($host,"select staff.name as name,staffaccess.class,staffaccess.staff from staffaccess INNER JOIN staff ON staff.id=staffaccess.staff where (staffaccess.feature='All' or staffaccess.feature='Fees Manage' or staffaccess.feature='Fees')  and staffaccess.cid='$branch' and staffaccess.staff='$staff'");
while($d1=mysqli_fetch_array($s1)){ 
$sid=$d1['staff'];
$standard=$d1['class'];  
$row['standard']=$standard;
$s=mysqli_query($host,"select id from students where standard='$standard' and session='$session' and (status='active' or status IS NULL) and cid='$user'");
$row['staff']=$d1['name']; 
$row['total']=mysqli_num_rows($s);
$s3=mysqli_query($host,"select sum(debit) as db,sum(credit) as cc from feesdue INNER JOIN students ON students.id=feesdue.sid where standard='$standard' and feesdue.rdate<='$date' and feesdue.cid='$user' and feesdue.cancel='No' and feesdue.session='$session' group by feesdue.sid HAVING sum(feesdue.debit)>sum(feesdue.credit)");
$row['overdue']=mysqli_num_rows($s3);  
$s4=mysqli_query($host,"select fees_status.id from fees_status INNER JOIN students ON students.id=fees_status.sid where students.standard='$standard' fees_status.cid='$user' and fees_status.date='$date' and fees_status.staff='$username'");
$row['intaction']=mysqli_num_rows($s4);  
$arr['data'][]=$row;
}
 
 
    
}else{


$s=mysqli_query($host,"select staff.id,staff.name from staffaccess INNER JOIN staff ON staff.id=staffaccess.staff where  feature='fees' and cid='$user' group by staff"); while($d=mysqli_fetch_array($s)){ 
        $id=$d[0];
        $s0=mysqli_query($host,"select * from staffaccess where  feature='fees' and cid='$user' and staff='$id'");
        $q1="(";    
        while($d0=mysqli_fetch_array($s0)){
              $class=$d0['class'];
              $q1.="students.standard='$class' or ";
        }
        $q1.=" students.standard='koko')";
        $s0=mysqli_query($host,"select students.id from students where students.cid='$user' and  $q1 and students.session='$session' and (students.status='Active' or students.status IS NULL )");
        $d0=mysqli_num_rows($s0);
        $s1=mysqli_query($host,"select students.id from students INNER JOIN feesdue ON feesdue.sid=students.id where students.cid='$user' and feesdue.rdate<='$date' and feesdue.cancel='No' and  $q1 and students.session='$session' and (students.status='Active' or students.status IS NULL ) group by feesdue.sid HAVING sum(feesdue.debit)>sum(feesdue.credit)");
        $d2=mysqli_num_rows($s1); $pop=0;
        while($d23=mysqli_fetch_array($s1))
        {
          $rto=$d23[0]; 
           $sss0=mysqli_query($host,"select idate from fees_status where sid='$rto' and idate>'$date'");
              if(mysqli_num_rows($sss0)>0){
                ++$pop;   
              }
        }
        $d2=$d2-$pop;
        $s2=mysqli_query($host,"select students.id from students INNER JOIN feesdue INNER JOIN fees_status ON fees_status.sid=students.id and feesdue.sid=students.id where students.cid='$user' and   $q1 and feesdue.rdate<='$session' and feesdue.cancel='No' and students.session='$session' and (students.status='Active' or students.status IS NULL ) and fees_status.idate>'$date' group by feesdue.sid HAVING sum(feesdue.debit)>sum(feesdue.credit)");
        $s3=mysqli_query($host,"select count(*),sum(if(respo='yes' and date='$date',1,0)),sum(if(date='$date',1,0)) as today from fees_status where cid='$user' and date='$date' and staff='$id'");
        $d3=mysqli_fetch_array($s3); if($d3[2]==""){ $d3[2]=0; } if($d3[1]==""){ $d3[1]=0; } if($d3[0]==""){ $d3[0]=0; }
        


$row['staff']=$d['name']; 
$row['staffid']=$sid; 
$row['text1']=$d0;
$row['text2']=$d2;  
$row['text3']=$d2-$d3[2]; 
$row['text4']=$d3[2];  
$row['text5']=$d3[1];   
$row['text6']=$d3[2]-$d3[1];   
$arr['data'][]=$row;
}
   
    
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