<?php include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true); 
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
if($r==1){
    
    
$arr['status']=true;
$arr['message']="success";
$arr['standard']=array();

$sq2=mysqli_query($host,"select standard.id,standard.name as standard from standard INNER JOIN subject_link_with_class ON subject_link_with_class.stream=standard.name where subject_link_with_class.user='$cid' and  standard.cid='$cid' and  (standard.status='Active' or standard.status IS NULL)  group by subject_link_with_class.stream  order by standard.num asc"); 
               while($dq2=mysqli_fetch_array($sq2)){
        $st=$dq2['standard']; 
        $row['standard']=$dq2['standard']; 
        $row['data']=array();
        $s2=mysqli_query($host,"select * from subject_link_with_class where user='$cid' and stream='$st'");
         while($d2=mysqli_fetch_array($s2)){ 
             $book=$d2['book'];
             $sub=$d2['name'];
             $row2['subject']=$d2['name'];
             $row2['book']=$d2['book'];
             $ss=mysqli_query($host,"select id from publisher where name='$book' and cid='$user'");
                           $dd=mysqli_fetch_array($ss);
                           $pid=$dd['id'];
              $q="SELECT count(book.id),book.type,sum(if(chapter.idate<='$date',1,0)) as cc FROM `book` INNER JOIN chapter ON chapter.bid=book.id where book.standard='$st' and book.type='$pid'  and chapter.type='chapter' and book.subject='$sub' and book.cid='$user'";
                   $sq=mysqli_query($host,$q); 
                   $des=mysqli_fetch_array($sq);
                   $to=$des[0];
                   $s22=mysqli_query($host,"select chapter.id,lession_slot_plan.idate,lession_slot.cordi as cordi,lession_slot.staff as staff from lession_slot_plan INNER JOIN chapter INNER JOIN book INNER JOIN lession_standard INNER JOIN lession_slot ON book.id=chapter.bid and chapter.id=lession_slot_plan.topic and lession_standard.sid=lession_slot_plan.slotid and lession_slot.id=lession_slot_plan.slotid where lession_slot_plan.cid='$user' and lession_standard.name='$st' and  lession_slot.subject='$sub' and lession_slot_plan.idate IS NOT NULL group by chapter.under order by lession_slot_plan.idate desc");
                   $to2=mysqli_num_rows($s22);
                   $dd2=mysqli_fetch_array($s22);
                   $staff=$dd2['staff'];
                   $s01=mysqli_query($host,"select name from staff where id='$staff' and branch='$user'");
                   $d01=mysqli_fetch_array($s01);
                   $sname=$d01[0];
                   $cord=$dd2['cordi'];
                     $s01=mysqli_query($host,"select name from staff where id='$cord' and branch='$user'");
                     if(mysqli_num_rows($s01)==1){
                   $d01=mysqli_fetch_array($s01);
                   $cordi=$d01['name'];
                     }else{
                         $cordi='Admin';
                     }
                   $last=date('d M Y',strtotime($dd2['idate']));
                   
                   $row2['totalchapter']=$to;
                   $row2['completechapter']=$to2;
                   $row2['academyplanner']=$des['cc'];
                   $row2['cordinator']=$cordi;
                   $row2['staff']=$sname;
                   $row2['lastupdate']=$last;
                   $row['data'][]=$row2;
                   
        
             
             
         }
                   
        $arr['standard'][]=$row;           
                   
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