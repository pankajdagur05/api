<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$sid=$_POST['sid'];
$username=$_POST['username'];
$token=$_POST['token'];
$type=$_POST['type'];
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
    
    $s0=mysqli_query($host,"select cid,standard from students where id='$sid'");
    $d0=mysqli_fetch_array($s0);
    $user=$d0[0];
   $standard=$d0['standard'];
    
$s=mysqli_query($host,"select * from subject_link_with_class where stream='$standard' and user='$user' order by num asc");  
while($d=mysqli_fetch_array($s)){ 
$subject=$d['name'];
$row['subject']=$d['name'];
$book=$d['book'];
$row['book']=array();
if($book){
$s1=mysqli_query($host,"select book.id as bid,book.book as bookname from publisher INNER JOIN book ON book.type=publisher.id where publisher.cid='$user' and publisher.name='$book' and book.standard='$standard' and book.subject='$subject' and book.status='Active' and publisher.status='Active'"); 
while($d1=mysqli_fetch_array($s1)){
$bid=$d1['bid'];    
$row2['name']=$d1['bookname'];
$row2['chapter']=array();

$s2=mysqli_query($host,"select * from chapter where cid='$user'  and under='$bid' order by num asc");
while($d2=mysqli_fetch_array($s2)){
$row3['name']=$d2['title'];
$row3['marks']=$d2['marks'];
$row3['topic']=array();
$tid=$d2['id'];
$s3=mysqli_query($host,"select * from chapter where cid='$user'  and under='$tid' order by num asc");
while($d3=mysqli_fetch_array($s3)){
$stid=$d3['id'];
$row4['name']=$d3['title']; 
$row4['marks']=$d3['marks']; 
$row4['subtopic']=array();

$s4=mysqli_query($host,"select * from chapter where cid='$user'  and under='$stid' order by num asc");
while($d4=mysqli_fetch_array($s4)){

$row5['name']=$d4['title']; 
$row5['marks']=$d4['marks']; 

$row4['subtopic'][]=$row5;
    
}


$row3['topic'][]=$row4;
    
}


$row2['chapter'][]=$row3;
    
}
 $row['book'][]=$row2;   
}
}
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