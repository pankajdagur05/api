<?php include("../../db.php");
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
$from=$obj['from'];
$to=$obj['to'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
if($r==1){ 
$arr['status']=true;
$arr['message']="success";
$sql="select count(*) as sum_status,sum(if(checkout_status IS NULL,1,0)) as inside,sum(if(checkout_status IS NOT NULL,1,0)) as outside from gatepass_data where cid='$user' and date='$date'";
$res=mysqli_query($host,$sql);
$row=mysqli_fetch_array($res);
$arr['total']=$row['sum_status'];
$arr['inside']=$row['inside'];
$arr['outside']=$row['outside'];
 $nextday = date('Y-m-d',strtotime('-7 day',strtotime($date)));

                    $sql="select count(*) as sum_status from gatepass_data where cid='$user' and  date between '$nextday' and '$date'";
                        $res=mysqli_query($host,$sql);
                        $row=mysqli_fetch_array($res);
   $arr['weekly']=$row[0];   
 $nextday = date('Y-m-d',strtotime('-30 day',strtotime($date)));

                    $sql="select count(*) as sum_status from gatepass_data where cid='$user' and  date between '$nextday' and '$date'";
                        $res=mysqli_query($host,$sql);
                        $row=mysqli_fetch_array($res);
   $arr['monthly']=$row[0];   
   
   
   $nextday = date('Y-m-d',strtotime('-365 day',strtotime($date)));

                    $sql="select count(*) as sum_status from gatepass_data where cid='$user' and  date between '$nextday' and '$date'";
                        $res=mysqli_query($host,$sql);
                        $row=mysqli_fetch_array($res);
   $arr['yearly']=$row[0];   
   $arr['data']=array();
   $arr['groupwise']=array();
   $q="select gatepass.com_subject,count(gatepass_data.id) FROM gatepass INNER JOIN gatepass_data ON gatepass.id = gatepass_data.gatepass_id where gatepass_data.cid='$user'  ";
     if($from){
       $q.=" and gatepass_data.date BETWEEN '$from' and '$to' ";
   }else{
       $q.="  and gatepass_data.date='$date' ";
   }
   $q.=" group by gatepass.com_subject order by gatepass_data.id desc ";
   $lik=mysqli_query($host,$q);
		while($dat=mysqli_fetch_array($lik))
		{
		    $row['name']=$dat[0];
		    $row['count']=$dat[1];
		    $arr['groupwise'][]=$row;
		    
		}
   $q="select gatepass.app_name,gatepass.com_subject,gatepass.com_desc,gatepass_data.date,gatepass_data.staffname,gatepass_data.checkin,gatepass_data.checkout FROM gatepass INNER JOIN gatepass_data ON gatepass.id = gatepass_data.gatepass_id where gatepass_data.cid='$user' ";
   
   if($from){
       $q.=" and gatepass_data.date BETWEEN '$from' and '$to' ";
   }else{
       $q.="  and gatepass_data.date='$date' ";
   }
   
   $q.=" order by gatepass_data.id desc";
   $s3=mysqli_query($host,$q); 
   
     while($d3=mysqli_fetch_array($s3)){ 


$start = $d3['checkin'];
if($d3['checkout']){
$end = $d3['checkout'];

     $dateDiff = intval(($end-$start)/60);

$hours = intval($dateDiff/60);
$minutes = $dateDiff%60;
$diff=$hours." Hrs. ".$minutes." Min";
}else{
    $diff="";
}

$row2['date']=date('d M Y',strtotime($d3['date']));
$row2['update']=$d3['staffname'];
$row2['name']=$d3['app_name'];
$row2['subject']=$d3['com_subject'];
$row2['checkin']=date('h:i:s A' ,$d3['checkin']);
$row2['checkout']=$d3['checkout']==NULL?"":date('h:i:s A',$d3['checkout']);
$row2['duration']=$diff;
$row2['desc']=$d3['com_desc'];
$arr['data'][]=$row2;



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