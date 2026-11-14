<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$standard=$obj['standard'];
$cid=$branchid; 
$user=$branchid;
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
if($r==1){

$arr['status']=true;
$arr['message']='success'; 
if($standard){
$arr['subject']=array();
$s2=mysqli_query($host,"select * from subject_link_with_class where user='$user' and stream='$standard' order by num asc"); $stnd=mysqli_num_rows($s2); while($d2=mysqli_fetch_array($s2)){ 
$sub=$d2['name'];  
$pub=$d2['book'];
$q3="select book.id from book INNER JOIN publisher ON publisher.id=book.type where publisher.name='$pub' and publisher.cid='$user' and  book.cid='$user' and book.standard='$standard' and book.subject='$sub'";
$s01=mysqli_query($host,$q3);
$q="(";
$x=0;
while($d01=mysqli_fetch_array($s01)){
$idd=$d01[0];
$q.="under='$idd' or ";
++$x;
}
$q.=" under='0')";
$q2="(";
$y=0;
$s02=mysqli_query($host,"select * from chapter where cid='$user' and $q");
while($d02=mysqli_fetch_array($s02)){
$idd2=$d02['id'];    
$q2.="under='$idd2' or ";
$y++;
}
$q2.=" under='0'";
$q2.=")";

$s03=mysqli_query($host,"select * from chapter where cid='$user' and $q2");

$row9['name']=$d2['name']; 
$row9['publisher']=$d2['book']; 
$row9['book']=$x; 
$row9['chapter']=$y;
$row9['topic']=mysqli_num_rows($s03);
$row9['done']=mysqli_num_rows($s03)-1;
$arr['subject'][]=$row9;

}    
    
}else{
$arr['stream']=array();
$s=mysqli_query($host,"select * from stream where cid='$user' order by num asc"); while($d=mysqli_fetch_array($s)){ 
$gid=$d['id'];
$row8['name']=$d['name'];
$row8['standard']=array();
$s2=mysqli_query($host,"select standard.name,subject_link_with_class.book,count(subject_link_with_class.id) from standard INNER JOIN subject_link_with_class ON subject_link_with_class.stream=standard.name where standard.cid='$user' and subject_link_with_class.user='$user' and standard.type='$gid' group by subject_link_with_class.stream order by standard.num asc"); $stnd=mysqli_num_rows($s2); while($d2=mysqli_fetch_array($s2)){ 
    
$row9['standard']=$d2[0];
$row9['publisher']=$d2[1];
$row9['subject']=$d2[2];
$row9['done']='2';
$row9['max']='10';
$row8['standard'][]=$row9;

}

$arr['stream'][]=$row8;
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