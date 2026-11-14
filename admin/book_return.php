<?php error_reporting(4);
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$search=$obj['search'];
$stype=$obj['type'];
 $branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$data=$obj['data'];
$sid=$obj['sid'];
$ty=$obj['type'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
$s=mysqli_query($host,"select * from apk_token where token='$token'");
$r=mysqli_num_rows($s);
if($r==1){

$branch=$branchid;
      $user=$branch;
      $userby=$type;

$s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];




  $arr['data']=array();
  if($sid){
      
      if(count($data)>0){
      
      $sql=mysqli_query($host,"select * from library_setting where cid='$user'");
      $like=mysqli_fetch_array($sql);
     
      for($a=0; $a<count($data); $a++){
           $amount=0;
          $bid=$data[$a]['bid'];
          $status=$data[$a]['status'];
          if(strtolower($status)=='ok'){
              if($like['fdays']){
              $s2=mysqli_query($host,"select * from book_issue where sid='$sid' and cid='$user' and bid='$bid' order by id desc limit 1");
              $d2=mysqli_fetch_array($s2);
              $date1=$d2['date'];
              $date2=date('Y-m-d');
              $diff = abs(strtotime($date2) - strtotime($date1));
              $years = floor($diff / (365*60*60*24));
$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

if($days>$like['fdays']){
   $amount=($days-$like['fdays'])*$like['dpenalty']; 
}

}
              
              
          }else{
              $s4=mysqli_query($host,"select price from book_data where id='$bid' and cid='$user'");
              $d4=mysqli_fetch_array($s4);
              if(strtolower($status)=='low damage'){
                  $per=$like['ldamage'];
              }else{
                 if(strtolower($status)=='medium damage'){
                  $per=$like['mdamage'];
              }else{
                  if(strtolower($status)=='medium damage'){
                  $per=$like['hdamage'];
              }else{
                  $per=100;
              }  
              } 
              }
              $amount=round(($d4['price']*$per)/100);
          }
          
          mysqli_query($host,"update book_issue set amount='$amount',remark='$status',retuened='$date',status='returned' where bid='$bid' and sid='$sid' and status='Issued'");
          
          
          
      }
      
      $arr['status']=true;
    $arr['message']='success'."update book_issue set amount='$amount',remark='$status',retuened='$date',status='returned' where bid='$bid' and sid='$sid' and status='Issued'";
    
      }else{
          
          $arr['status']=true;
    $arr['message']='success';
          
          $s=mysqli_query($host,"select book_issue.*,book_data.bname as bname,book_data.subcategory as subcategory,book_data.category as category from book_issue INNER JOIN book_data ON book_data.id=book_issue.bid  where book_issue.cid='$user' and book_issue.status='issued' and book_issue.sid='$sid' and book_issue.type='$ty'");
          while($d=mysqli_fetch_array($s)){
               $row['id']=$d['bid'];
              $row['subcategory']=$d['subcategory'];
               $row['category']=$d['category'];
                $row['bname']=$d['bname'];
                 $row['issuedate']=date('d M Y',strtotime($d['date']));
                  $row['status']=$d['status'];
                   $row['returndate']=date('d M Y',strtotime($d['returndate']));
                    $arr['data'][]=$row;
          }
          
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