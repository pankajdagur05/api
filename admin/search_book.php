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
$ssid=$obj['ssid'];
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



$arr['status']=true;
    $arr['message']='success';
  $arr['data']=array();
    $arr['data2']=array();
  
if($search or $ssid){
if($ssid)
{
$q="select book_data.*,book_issue.date,book_issue.returndate,book_issue.retuened from book_data inner join book_issue on book_data.id=book_issue.bid where book_issue.sid='$ssid' and book_data.cid='$branch' and book_data.scrape='No' and book_data.lost='No'";
}else
{
$q="select * from book_data where (bname like '%$search%' or bcode like '%$search%' or wname like '%$search%') and cid='$branch' and scrape='No' and lost='No'";
}

 $s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
     $bid=$d['id'];
     $s2=mysqli_query($host,"Select id from book_issue where cid='$user' and bid='$bid' and status='Issued'");
     $r2=mysqli_num_rows($s2);
     $row['language']=$d['language'];
     $row['category']=$d['category'];
     $row['subcategory']=$d['subcategory'];
     $row['bname']=$d['accession']."-".$d['bname'];
     $row['bcode']=$d['bcode'];
     $row['wname']=$d['wname'];
     $row['price']=$d['price'];
     $row['quantity']=$d['quantity'];
     $row['qty']=$d['quantity']-$r2;
     $row['age']=$d['age'];
     $row['shel']=$d['shel'];
      $row['remark']=$d['remark'];
      if($ssid){
       $row['issuedate']=date('d M Y',strtotime($d['date']));
       $row['returndate']=date('d M Y',strtotime($d['returndate']));
        $row['returndated']=date('d M Y',strtotime($d['retuened']));
      }
      $row['id']=$d['id'];
      $arr['data'][]=$row;
     
     
     
 }
}else{
    
    $q="select language from book_data where cid='$branch' and scrape='No' and lost='No' group by language";
 $s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
     
     $row['language']=$d['language'];
    // $arr['data2'][]=$row;
     
     
 }

$s1=mysqli_query($host,"select sum(quantity) from book_data where cid='$user' and lost='No' and scrape='no'"); 
$d1=mysqli_fetch_array($s1);
$arr['total']=$d1[0];
$s1=mysqli_query($host,"select id from book_issue where cid='$user' and status='Issued'"); 
$ss=mysqli_num_rows($s1);
$arr['issue']=$ss;

$s1=mysqli_query($host,"select id from book_issue where cid='$user' and retuened is NULL"); 
$ss=mysqli_num_rows($s1);

$arr['instock']=$d1[0]-$ss;
$s1=mysqli_query($host,"select id from book_issue where cid='$user' and length(retuened)=0 and returndate<'$date'"); 
$ss=mysqli_num_rows($s1);
$arr['overtime']=$ss;
}

}else{
    $arr['status']=false;
    $arr['message']='Token Not Match';
}
 $jsont = json_encode($arr);

file_put_contents("lib.txt",$jsont, FILE_APPEND );
echo $json = json_encode($arr);

}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}










?>