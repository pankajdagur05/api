<?php include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$_POST['userid'];
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$type='other';
$standard=$_POST['standard'];
$subject=$_POST['subject'];
$date=date('Y-m-d');
$times=date('h:i:s');
if($username){
    
     
    
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){ 
        
     $s3=mysqli_query($host,"select branch from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $cid=$d3['branch'];
      
      $arr['status']=true;
      $arr['message']="successfully";
      $arr['data']=array();
      
      $s1=mysqli_query($host,"select * from book where standard='$standard' and subject='$subject' and cid='$cid' and status='Active' order by num asc");
      while($d1=mysqli_fetch_array($s1)){
          $bid=$d1['id'];
          $row['book_name']=$d1['book'];
          $row['chapter']=array();
          
          $s2=mysqli_query($host,"select * from chapter where cid='$cid' and type='chapter' and under='$bid' order by num asc");
          while($d2=mysqli_fetch_array($s2)){
              $chapid=$d2['id'];
              $row1['title']=$d2['title'];
              $row1['id']=$d2['id'];
              $row1['topic']=array();
              
              $s3=mysqli_query($host,"select * from chapter where type='topic' and cid='$cid' and under='$chapid'");
              while($d3=mysqli_fetch_array($s3)){
                  $row2['title']=$d3['title'];
                  $row2['id']=$d3['id'];
                    $row1['topic'][]=$row2;
              }
              
            
            $row['chapter'][]=$row1;  
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