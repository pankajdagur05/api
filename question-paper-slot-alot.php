<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$username=$obj['userid'];
$type='staff';
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

  $s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $user=$branch;


$board=$obj['board'];
$medium=$obj['medium'];
$standard=$obj['standard'];
$subject=$obj['subject'];
$book=$obj['book'];
$sid=$obj['sid'];
$tid=$obj['update'];
if($board){
if($tid){
$q="update paperslot set board='$board',medium='$medium',standard='$standard',book='$book',subject='$subject',sid='$sid',date='$date' where id='$tid' and cid='$user'";    
mysqli_query($host,$q);   
$arr['status']=true;
$arr['message']='successfully Updated';
}else{    
$q="insert into paperslot set board='$board',medium='$medium',book='$book',standard='$standard',subject='$subject',sid='$sid',date='$date',cid='$user'";    
mysqli_query($host,$q);
$arr['status']=true;
$arr['message']='successfully Inserted';
 
} 
}else{

if($delete){
mysqli_query($host,"delete from paperslot where id='$delete' and cid='$user'"); 
$arr['status']=true;
$arr['message']='successfully deleted';
}else{
$arr['status']=true;
$arr['message']='success';  
}
 
 
 
$arr['data']=array();
$arr['filter']=array();
$arr['staff']=array();
$s=mysqli_query($host,"select *,paperslot.id as tid from paperslot INNER JOIN staff ON staff.id=paperslot.sid where paperslot.cid='$user' order by paperslot.id desc");
while($d=mysqli_fetch_array($s)){
   
$tid=$d['tid'];
$row['board']=$d['board']; 
$row['medium']=$d['medium']; 
$row['standard']=$d['standard']; 
$row['subject']=$d['subject']; 
$row['book']=$d['book']; 
$row['sid']=$d['sid']; 
$row['sname']=$d['name']; 
$row['date']=$d['date']; 
$row['id']=$tid; 
$arr['data'][]=$row;
    
}



$s4=mysqli_query($host,"select * from subject_link_with_class where user='$user' group by stream");
while($d4=mysqli_fetch_array($s4)){
    $str=$d4['stream'];
$row3['standard']=$d4['stream']; 
$row3['subject']=array();
$s6=mysqli_query($host,"select * from subject_link_with_class where user='$user' and stream='$str' group by name");
while($d6=mysqli_fetch_array($s6)){
  $row5['name']=$d6['name'];
  $row3['subject'][]=$row5;
}




$arr['filter'][]=$row3;
}

$s5=mysqli_query($host,"select * from staff where branch='$user'");
while($d5=mysqli_fetch_array($s5)){
$row4['name']=$d5['name']; 
$row4['id']=$d5['id']; 
$arr['staff'][]=$row4;
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