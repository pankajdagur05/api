<?php error_reporting(0);
include("../../db.php");

function send_notification($title,$desc,$token,$key,$m)
{

#prep the bundle
     $msg = array
          (
        'body'  => $desc,
        'title' => $title,
        'click_action' => $m,
                
          );
          
         
    $fields = array
            (
                'registration_ids'  => $token,
                'notification'  => $msg
            );
    
    
    $headers = array
            (
                'Authorization: key=' . $key,
                'Content-Type: application/json'
            );
            
          
             
#Send Reponse To FireBase Server    
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        
           
        
       
}
$json = file_get_contents('php://input');
$obj = json_decode($json,true); 
$username=$obj['userid']; 
$user=$obj['branch']; 
$token=$obj['token'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s a');
$tim=time();
$route=$obj['route'];
$sid=$obj['sid'];
$type=$obj['type'];
$mode=$obj['mode'];
$lat=$obj['lat'];
$lon=$obj['lon'];
$location=$lat.','.$lon;
$trip=$obj['trip']; 
$pickup=$obj['pickup'];

if($username){
     $s=mysqli_query($host,"select * from apk_token where token='$token' and user='$username'");
    $r=mysqli_num_rows($s);
    if($r==1){ 
         $ar1['status']=true;
		  $ar1['message']="Successfully Update";
		 $ar1['data']=array();
		   $s1=mysqli_query($host,"select * from session where cid='$user' order by id desc limit 1");
        $d1=mysqli_fetch_array($s1);
        $session=$d1['name'];
        
        
        if($sid)
        {
            $s0=mysqli_query($host,"select priority from students where route='$route' and cid='$user' and status='Active' and session='$session' order by priority desc limit 1");
            $d0=mysqli_fetch_array($s0);
            $num=$d0[0]==""?1:$d0[0]+1;
            $s9=mysqli_query($host,"select location,name from students where id='$sid'");
            $d9=mysqli_fetch_array($s9);
            $q="update students set priority='$num'";
            if($d9[0]==""){
                $q.=",lat='$lat',lon='$lon' ";
            }
            $q.=" where id='$sid'";
            
            mysqli_query($host,$q);
            mysqli_query($host,"insert into trip_students set sid='$sid',type='$type',date='$date',time='$tim',location='$location',pickup='$pickup',route='$route',cid='$user'");
       
       
            if($type=="pickup") 
            {
              if($pickup=="0") 
              {
                $desc=$d9[1]." is Picked";
              } else
              {
                  $desc=$d9[1]." has arrived at school";
              }
            }else
            {
              if($pickup=="0") 
              {
                $desc=$d9[1]." droped out of school";
              } else
              {
                  $desc=$d9[1]." has droped at drop-location";
              }  
            }
            if($desc){       
       
        
$title="Transport Notification";
$desc="";
$token=array();
$x=0;
$s90=mysqli_query($host,"select id,p_device,s_device from students where id='$sid' and (length(s_device)>0 or length(p_device)>0) and (status='active' or status IS NULL) and session='$session'");
$d90=mysqli_fetch_array($s90);
    $sid=$d90['id'];
if($d90['s_device']){
$token[$x]=$d90['s_device'];
$x++;
mysqli_query($host,"insert into notification set msg='$desc',mode='$title',date='$today',time='$time',sid='$sid',sub_domain='$subdomain',type='student'");
}
if($d90['p_device']){
$token[$x]=$d90['p_device'];
$x++;    
mysqli_query($host,"insert into notification set msg='$desc',mode='$title',date='$today',time='$time',sid='$sid',sub_domain='$subdomain',type='parent'");
}


$token=array_unique($token);

$s=mysqli_query($host,"select * from global where name='Student Key'");
$d=mysqli_fetch_array($s);
$key=$d['val'];

send_notification($title,$desc,$token,$key,$typed);
}
        }else{
        
        
        
       
$file2 = 'log.txt';
//$current = file_get_contents($file2);
$sqld="select students.id,students.name,students.standard,students.area as location,students.stopage from students where students.cid='$user' and students.status='Active' and students.session='$session' and students.route='$route' order by priority asc";
//file_put_contents($file2,$sqld);

       $s2=mysqli_query($host,"select students.id,students.name,students.standard,students.area as location,students.stopage from students where students.cid='$user' and students.status='Active' and students.session='$session' and students.route='$route' order by priority asc");
       
       
       while($d2=mysqli_fetch_array($s2)){
           $sid=$d2['id'];
            $s4=mysqli_query($host,"select * from trip_students where cid='$user' and type='$type' and sid='$sid' and date='$date' order by id desc limit 1");
            $d4=mysqli_fetch_array($s4);
            if(mysqli_num_rows($s4)==0){ 
           $s3=mysqli_query($host,"select * from docs where cid='$user' and name='photo' and sid='$sid' order by id desc limit 1");
           if(mysqli_num_rows($s3)==0){ $photo="https://edpluss.com/school/admin-panel/cpanel/access/img/student-list.png";  }else{ $d3=mysqli_fetch_array($s3); $photo="https://edpluss.com/school/admin-panel/cpanel/access/upload/".$d3['filename']; }
         $loc=explode(",",$d2['location']);
          $row['sid']=$d2['id']; 
          $row['name']=$d2['name']; 
          $row['fname']=$d2['fname']; 
          $row['standard']=$d2['standard']; 
          $row['stopage']=$d2['stopage']; 
           $row['lat']=$loc[0]; 
            $row['lon']=$loc[1]; 
           $row['photo']=$photo; 
          
            
            $row['pickup']=$pickup;
            $row['drop']=$drop;
            $ar1['data'][]=$row;
          
       }
       }
       
        }
        
         echo $json=json_encode($ar1); 
        
    }else{
         $ar1['status']=false;
		 $ar1['message']='Token Did Not Match';
		 echo $json=json_encode($ar1); 
    }
    
}else
{
    
    ?>
    <script>
        window.location="http://edpluss.com";        
    </script>
    <?php
}
?>