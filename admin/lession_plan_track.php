<?php include("../../db.php");  
$username=$_POST['userid'];
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$branch=$_POST['branch'];
$type='admin';
$date=date('Y-m-d');
$time=date('h:i:s');
$branchid=$_POST['branchid'];
$type=$_POST['typefirst'];
$session=$_POST['session'];
$userid=$_POST['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
 

$arr['status']=true;
$arr['message']='success';
$arr['publisher']=array();
$arr['slotteacher']=array();
$arr['slotlink']=array();
$arr['disslot']=array();
$arr['apslot']=array();
$arr['goslot']=array();
$sql=mysqli_query($host,"Select * from stream where (status='active' or status IS NULL) and cid='$user'"); while($data=mysqli_fetch_array($sql)){ $stream=$data['id'];
$row['stream']=$data['name'];
$row['standard']=array();
$x=0; $k=0; $sq2=mysqli_query($host,"select standard.id,standard.name as standard from standard INNER JOIN subject_link_with_class ON subject_link_with_class.stream=standard.name where subject_link_with_class.user='$cid' and standard.type='$stream' and  standard.cid='$cid' and  (standard.status='Active' or standard.status IS NULL)  group by subject_link_with_class.stream  order by standard.num asc"); 
               while($dq2=mysqli_fetch_array($sq2)){ 
                     $st=$dq2['standard'];
               $row2['name']=$dq2['standard'];
               $row2['subject']=array();
                  $s2=mysqli_query($host,"select * from subject_link_with_class where user='$cid' and stream='$st' and book IS NULL "); 
                   while($d1=mysqli_fetch_array($s2)){ 
               $row3['name']=$d1['name'];
               $row2['subject'][]=$row3;
                   }
                   $row['standard'][]=$row2;
               }
$arr['publisher'][]=$row;
}

$row=array();
$row2=array();
$row3=array(); 
$sql=mysqli_query($host,"Select * from stream where (status='active' or status IS NULL) and cid='$user'"); while($data=mysqli_fetch_array($sql)){ $stream=$data['id'];
$row['stream']=$data['name'];
$row['standard']=array();
$x=0; $k=0; $sq2=mysqli_query($host,"select standard.id,standard.name as standard from standard INNER JOIN subject_link_with_class ON subject_link_with_class.stream=standard.name where subject_link_with_class.user='$cid' and  standard.cid='$cid' and standard.type='$stream' and  (standard.status='Active' or standard.status IS NULL)  group by subject_link_with_class.stream  order by standard.num asc");  
               while($dq2=mysqli_fetch_array($sq2)){   
                   $st=$dq2['standard'];
               $row2['name']=$dq2['standard'];
               $row2['subject']=array();
                 $s2=mysqli_query($host,"select * from subject_link_with_class where user='$cid' and stream='$st' and book IS NOT NULL ");
                   while($d1=mysqli_fetch_array($s2)){
                       $sub=$d1['name'];
                                   
                                $s3=mysqli_query($host,"select lession_slot.id from lession_slot INNER JOIN lession_standard ON lession_standard.sid=lession_slot.id where lession_slot.subject='$sub' and lession_standard.name='$st' and lession_slot.fromdate<='$date' and lession_slot.todate>='$date' and lession_slot.cid='$user'");
                                if(mysqli_num_rows($s3)==0){ 
               $row3['name']=$d1['name'];
               $row2['subject'][]=$row3;
                                }
                   }
                   $row['standard'][]=$row2;
               }
$arr['slotteacher'][]=$row;
}

$row=array();
$row2=array();
$row3=array(); 
 
$x=0; $k=0; $s3=mysqli_query($host,"select lession_slot.id,lession_standard.name as standard from lession_slot INNER JOIN lession_standard ON lession_standard.sid=lession_slot.id where lession_slot.fromdate<='$date' and lession_slot.todate>='$date' and lession_slot.cid='$user' and lession_slot.status='pending' group by lession_standard.name");
      while($d3=mysqli_fetch_array($s3)){
                   $st=$d3['standard'];
               $row2['name']=$d3['standard'];
               $row2['subject']=array();
                 $s2=mysqli_query($host,"select lession_slot.id,lession_standard.name as standard,lession_slot.* from lession_slot INNER JOIN lession_standard ON lession_standard.sid=lession_slot.id where lession_slot.fromdate<='$date' and lession_slot.todate>='$date' and lession_slot.cid='$user' and lession_slot.status='pending' and lession_standard.name='$st' group by lession_slot.subject");
                   while($d1=mysqli_fetch_array($s2)){
               $row3['name']=$d1['subject'];
               $row2['subject'][]=$row3;
                                
                   }
                  $arr['slotlink'][]=$row2;
               }

$row=array();
$row2=array();
$row3=array(); 
 
$x=0; $k=0;$s3=mysqli_query($host,"select lession_slot.id,lession_standard.name as standard from lession_slot INNER JOIN lession_standard ON lession_standard.sid=lession_slot.id where lession_slot.fromdate<='$date' and lession_slot.todate>='$date' and lession_slot.cid='$user' and lession_slot.status='Under Review' group by lession_standard.name");
      while($d3=mysqli_fetch_array($s3)){
                   $st=$d3['standard'];
               $row2['name']=$d3['standard'];
               $row2['subject']=array();
                $s2=mysqli_query($host,"select lession_slot.id,lession_standard.name as standard,lession_slot.* from lession_slot INNER JOIN lession_standard ON lession_standard.sid=lession_slot.id where lession_slot.fromdate<='$date' and lession_slot.todate>='$date' and lession_slot.cid='$user' and lession_slot.status='Under Review' and lession_standard.name='$st' group by lession_slot.subject");
                   while($d1=mysqli_fetch_array($s2)){
                         $sid=$d1['staff'];
                             $s90=mysqli_query($host,"select name from staff where branch='$user' and id='$sid'");
                             $d09=mysqli_fetch_array($s90);
               $row3['name']=$d1['subject'];
               $row3['staff']=$d09['name'];
                  $row3['date']=date('d M Y',strtotime($d1['date']));
               $row2['subject'][]=$row3;
                                
                   }
                  $arr['disslot'][]=$row2;
               }
 

$row=array();
$row2=array();
$row3=array(); 
 
$x=0; $k=0;  $s3=mysqli_query($host,"select lession_slot.id,lession_standard.name as standard from lession_slot INNER JOIN lession_standard ON lession_standard.sid=lession_slot.id where lession_slot.fromdate<='$date' and lession_slot.todate>='$date' and lession_slot.cid='$user' and lession_slot.status='approval' group by lession_standard.name");
      while($d3=mysqli_fetch_array($s3)){
                   $st=$d3['standard'];
               $row2['name']=$d3['standard'];
               $row2['subject']=array();
                 $s2=mysqli_query($host,"select lession_slot.id,lession_standard.name as standard,lession_slot.* from lession_slot INNER JOIN lession_standard ON lession_standard.sid=lession_slot.id where lession_slot.fromdate<='$date' and lession_slot.todate>='$date' and lession_slot.cid='$user' and lession_slot.status='approval' and lession_standard.name='$st' group by lession_slot.subject");
                                        
                   while($d1=mysqli_fetch_array($s2)){
                         $sid=$d1['staff'];
                             $s90=mysqli_query($host,"select name from staff where branch='$user' and id='$sid'");
                             $d09=mysqli_fetch_array($s90);
               $row3['name']=$d1['subject'];
               $row3['staff']=$d09['name'];
                  $row3['date']=date('d M Y',strtotime($d1['date']));
               $row2['subject'][]=$row3;
                                
                   }
                  $arr['apslot'][]=$row2;
               }
               
               
               

$row=array();
$row2=array();
$row3=array(); 
 
$x=0; $k=0; $s3=mysqli_query($host,"select lession_slot.id,lession_standard.name as standard from lession_slot INNER JOIN lession_standard ON lession_standard.sid=lession_slot.id where lession_slot.fromdate<='$date' and lession_slot.todate>='$date' and lession_slot.cid='$user' and lession_slot.status='Approved' group by lession_standard.name");
      while($d3=mysqli_fetch_array($s3)){ 
                   $st=$d3['standard'];
               $row2['name']=$d3['standard'];
               $row2['subject']=array();
                $s2=mysqli_query($host,"select lession_slot.id,lession_standard.name as standard,lession_slot.* from lession_slot INNER JOIN lession_standard ON lession_standard.sid=lession_slot.id where lession_slot.fromdate<='$date' and lession_slot.todate>='$date' and lession_slot.cid='$user' and lession_slot.status='Approved' and lession_standard.name='$st' group by lession_slot.subject");
                   while($d1=mysqli_fetch_array($s2)){
                         $sid=$d1['staff'];
                             $s90=mysqli_query($host,"select name from staff where branch='$user' and id='$sid'");
                             $d09=mysqli_fetch_array($s90);
               $row3['name']=$d1['subject'];
               $row3['staff']=$d09['name'];
                  $row3['date']=date('d M Y',strtotime($d1['date']));
               $row2['subject'][]=$row3;
                                
                   }
                  $arr['goslot'][]=$row2;
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