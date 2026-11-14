<?php include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$sid=$_POST['sid'];
$username=$_POST['username'];
$token=$_POST['token'];
$type='staff';
$date=date('Y-m-d');
$time=date('h:i:s');
$weeks=array('Sunday'=>7,'Monday'=>1,'Tuesday'=>2,'Wednesday'=>3,'Thursday'=>4,'Friday'=>5,'Saturday'=>6); 
$range=array(0,31,29,31,30,31,30,31,31,30,31,30,31);
$month=array("","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
if($username){
    $q="select * from apk_token where token='$token' and user='$username'";
    $s=mysqli_query($host,$q);
    $r=mysqli_num_rows($s);
    if($r==1){
    $arr['status']=true;
    $arr['message']='success';
    $arr['data']=array();
$s1=mysqli_query($host,"select * from students where id='$sid'"); 
$d1=mysqli_fetch_array($s1);   
$user=$d1['cid'];
$s=mysqli_query($host,"select count(*),sum(if(status = 'P',1,0)) as p,sum(if(status = 'A',1,0)) as ab,sum(if(status = 'L',1,0)) as lv from attendance where sid='$sid' and subdomain='$user'");
$d=mysqli_fetch_array($s);
$per=round(($d[1]*100)/$d[0]);
if($sid2){
$s2=mysqli_query($host,"select count(*),sum(if(status = 'P',1,0)) as p,sum(if(status = 'A',1,0)) as ab,sum(if(status = 'L',1,0)) as lv from attendance where sid='$sid2' and subdomain='$user'");
$d2=mysqli_fetch_array($s2);
}
$row4['total_parsent']=$d['p'];
$row4['last_total_parsent']=$d1['p']>0?round(($d['p']*100)/$d1['p']):0;
$row4['total_absent']=$d['ab'];
$row4['last_total_absent']=$d1['ab']>0?round(($d['ab']*100)/$d1['ab']):0;
$row4['total_leave']=$d['lv'];
$row4['last_total_leave']=$d1['lv']>0?round(($d['lv']*100)/$d1['lv']):0;
$row4['monthwise']=array();


for($a=1; $a<=12; $a++){
    
$s=mysqli_query($host,"select count(*),sum(if(status = 'P',1,0)) as p,sum(if(status = 'A',1,0)) as ab,sum(if(status = 'L',1,0)) as lv,year(idate) as year from attendance where sid='$sid' and subdomain='$user' and month(idate)='$a'");
$d=mysqli_fetch_array($s);
if($d[0]>0){
$m=$month[$a];    
$row5['month']=$a;
$row5['monthname']=$month[$a];
$row5['year']=$d['year'];
$row5['p']=$d['p'];
$row5['ab']=$d['ab'];
$row5['lv']=$d['lv'];
$row5['datewise']=array();
$row5['calender']=array(); 

$year=$d['year'];

$q2="select status,idate from attendance where sid='$sid' and subdomain='$user' and month(date)='$a' order by date asc";
$s2=mysqli_query($host,$q2); 
while($d2=mysqli_fetch_array($s2)){
$row6['date']=date('d',strtotime($d2['idate'])); 
$row6['status']=$d2['status']; 
$row5['datewise'][]=$row6;
}

$s9=mysqli_query($host,"select * from calender where cid='$user' and (month(fdate)='$a' and year(fdate)='$year') or  (month(tdate)='$a' and year(tdate)='$year') order by day(fdate) asc");
while($d9=mysqli_fetch_array($s9)){
$row7['subject']=$d9['title'];   
$row7['type']=$d9['mode'];   
$row7['fdate']=date("d M Y",strtotime($d9['fdate']));
$row7['tdate']=$d9['tdate']==NULL?"":date("d M Y",strtotime($d9['tdate']));
$row5['calender'][]=$row7;
}
$row4['monthwise'][]=$row5;
} 
    
    
} 



$arr['data'][]=$row4;
    
 
    
    
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