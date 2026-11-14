<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true); 
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];

$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
         $s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
            $arr['status']=true;
            $ftoday=date('Y-m-').'01';
    $arr['message']='success';
    $lastMonthTimestamp = strtotime('first day of last month');
    $arr['name1']= date('F Y',$lastMonthTimestamp);
    $arr['name2']=date('M Y');
     $lm=date('m', strtotime('last month'));
     $ly=date('Y', strtotime('last month'));
    $s1=mysqli_query($host,"select id from staff_attendance where subdomain='$branch' and sid='$username' and month(date)='$lm' and year(date)='$ly'");
    if(mysqli_num_rows($s1)>0){
        
        $month_ini = new DateTime("first day of last month");
$month_end = new DateTime("last day of last month");

  $startdate=$month_ini->format('Y-m-d'); // 2012-02-01
  $enddate=$month_end->format('Y-m-d');

    $arr['link1']="https://edpluss.com/school/admin-panel/cpanel/staff_excel_attendance.php?type=print&id=".$username."&sname=".$username."&fromdate=".$startdate."&todate=".$enddate."&token=".$token;
    
    }
   
    $arr['link2']="https://edpluss.com/school/admin-panel/cpanel/staff_excel_attendance.php?type=print&id=".$username."&sname=".$username."&fromdate=".$ftoday."&todate=".$date."&token=".$token;
    $arr['data']=array();
     $q=" select * from staff_attendance where subdomain='$branch' and sid='$username' and status='p' order by id desc limit 10";
$s=mysqli_query($host,$q);
             while($staff=mysqli_fetch_array($s)){
                 $row['date']=date('d M Y',strtotime($staff['idate']));
                  $row['data']=array();
                  $arr1=explode(",",$staff['time']); 
                  $arr2=explode(",",$staff['uploadby']);  
                  $arr3=explode(",",$staff['punch']); 
                   $lat=explode(",",$staff['lat']); 
                  $lon=explode(",",$staff['lon']); 
                  for($a=0; $a<count($arr1); $a++){ 
                      $row2['time']=$arr1[$a];
                      $row2['uploadby']=$arr2[$a];
                      $row2['punch']=$arr3[$a];
                      $row2['lat']=$lat[$a];
                      $row2['lon']=$lon[$a];
                      $row['data'][]=$row2;
                  }
                $arr['data'][]=$row;  
             } 
    
        
    }else{
    $arr['status']=false;
    $arr['message']='Token Not Match';
}

echo $json = json_encode($arr);


}else
{
    
    ?>
    <script>
        window.location="http://edpluss.com";        
    </script>
    <?php
}
?>