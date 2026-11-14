<?php include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true); 
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
$arr['message']="success";
$arr['data']=array();
$arr['data2']=array();
$arr['data3']=array();
if($obj['standard']){
$stand=$obj['standard'];
$s=mysqli_query($host,"select standard.name as st,subject_link_with_class.name as sub,subject_link_with_class.book from standard INNER JOIN subject_link_with_class ON subject_link_with_class.stream=standard.name where subject_link_with_class.user='$user' and standard.cid='$user' and subject_link_with_class.stream='$stand'  order by standard.num asc");while($d=mysqli_fetch_array($s)){
            
            $standard=$d['st'];
            $sub=$d['sub'];
             $s2=mysqli_query($host,"select lession_slot.*,staff.name as sname from lession_slot INNER JOIN staff ON staff.id=lession_slot.staff where lession_slot.cid='$user' and lession_slot.standard='$standard' and lession_slot.subject='$sub'");
        $s3=mysqli_query($host,"select lession_slot.*,staff.name as sname from lession_slot INNER JOIN staff ON staff.id=lession_slot.staff where lession_slot.cid='$user' and lession_slot.standard='$standard' and lession_slot.subject='$sub' and lession_slot.fromdate<='$date' and lession_slot.todate>='$date'");
        $d3=mysqli_fetch_array($s3);
        $sid=$d3['id'];
        $fdate=$d3['todate'];
        $s3=mysqli_query($host,"select lession_slot.fromdate  from lession_slot where cid='$user' and standard='$standard' and subject='$sub' and fromdate>'$fdate'");
         $r4=mysqli_num_rows($s3);
         if($r3>0){ $dt='('.date('d M Y',strtotime($d3['fromdate'])).'-'.date('d M Y',strtotime($d3['todate'])).')';   }
           $row['subject']=$sub;
          $row['step1']=$d['book']==""?0:1;
          $row['name1']=$d['book'];
          $row['step2']=$d2>0?1:0;
          $row['name2']=$d2;
          $row['step3']=$r3>0?1:0;
          $row['name3']=$dt;
          $row['step4']=$r4>0?1:0;
          $row['name4']="7 Days Left";
          $arr['data2'][]=$row;
          
          
          
          
          
          
}

$s=mysqli_query($host,"select standard.name as st,subject_link_with_class.name as sub,subject_link_with_class.book from standard INNER JOIN subject_link_with_class ON subject_link_with_class.stream=standard.name where subject_link_with_class.user='$user' and standard.cid='$user' and subject_link_with_class.stream='$stand'  order by standard.num asc"); $rr=mysqli_num_rows($s); while($d=mysqli_fetch_array($s)){ ++$v;
				     $standard=$d['st'];
				     $sub=$d['sub'];
				     $row['standard']=$standard;
				     $row['subject']=$sub;
				     $row['data']=array();
				     $s3=mysqli_query($host,"select *  from lession_slot where cid='$user' and standard='$standard' and subject='$sub' order by fromdate asc");
				     $day=0;
				     $add="";
				     $r3=mysqli_num_rows($s3);
				     while($d3=mysqli_fetch_array($s3)){
				         $xx++;
	                    $date1=$d3['fromdate'];
	                    
                        $date2=$d3['todate'];
                       $diff = abs(strtotime($date2) - strtotime($date1));
                    $day+=floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                        $row2['day']=$day;
                        $row['data'][]=$row2;
                         
				     }
				     
				     
		$arr['data3'][]=$row;		     
				     
}


}else{

$s1=mysqli_query($host,"select standard.name from standard where cid='$user' order by num asc");while($d1=mysqli_fetch_array($s1)){  $st=$d1['name'];
  	    $s2=mysqli_query($host,"select * from subject_link_with_class where user='$user' and stream='$st'");
  	    $s3=mysqli_query($host,"select * from lession_slot where cid='$user' and standard='$st' and fromdate<='$date' and todate>='$date' group by subject");
  	    
 $row['standard']=$d1['name'];
 $row['subject']=mysqli_num_rows($s2);
 $row['publisher']=mysqli_num_rows($s3);
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