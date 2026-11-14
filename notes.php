<?php
include("../db.php");

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
$obj=json_decode($json,true);
$delete=$_POST['delete'];
$subdomain=$_POST['subdomain'];
$username=$_POST['userid'];
$token=$_POST['token'];
$typed=$_POST['typed'];
$data=explode(",",$_POST['types']);
$titles=explode(",",$_POST['titles']);
$ids=explode(",",$_POST['ids']);
$type='staff';
$date=time();
$today=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='".$token."'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
    

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $user=$branch;
      
          $s6=mysqli_query($host,"select * from session where cid='$user' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];
        
      $userby=$d3['name'];;
$title=$_POST['title'];
$desc=$_POST['desc'];
$subject=$_POST['subject'];
$standard=$_POST['standard'];

$sid=$username;
$tid=$_POST['update'];
$delete=$_POST['delete'];
if($title){

 if(!empty($_FILES['file'])){ 
     
    // File upload configuration 
    $targetDir = "../cpanel/upload/notes/"; 
    $allowTypes = array('jpg','JPG','JPEG','PNG','png','pdf','PDF','docx'); 
     $fileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $fileName = $token.time().".".$fileType;
    $targetFilePath = $targetDir.$fileName; 
     
    // Check whether file type is valid 
    $filesize = filesize($_FILES['file']['tmp_name']); // bytes
$filesize = round($filesize / 1024 , 1);
    if(in_array($fileType, $allowTypes)){ 
        // Upload file to the server 
        if(move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)){ 
            
          $uqst=$fileName;
        } 
    }
    
    $ff="aaaaaaaa";
}else{
  if($tid){
     $s1=mysqli_query($host,"select * from notes where id='$tid' and cid='$user'"); 
     $d1=mysqli_fetch_array($s1);
     $uqst=$d1['path'];
  }
  $ff="bbb";
}    
    
 
        
    
    
if($tid){
$q="update notes set title='$title',description='$desc',path='$uqst',standard='$standard',subject='$subject',cid='$user',date='$date',user='$userby',dele='0' where id='$tid' and cid='$user'";    
mysqli_query($host,$q);   
$arr['status']=true;
$arr['message']='successfully Updated';
}else{    
 $tit=$title;
   $q="insert into notes set title='$title',description='$desc',path='$uqst',standard='$standard',subject='$subject',cid='$user',user='$userby',staffid='$staff',date='$date',dele='0',type='$typed'";    
mysqli_query($host,$q);
if(count($data)>0){
$s0=mysqli_query($host,"select id from notes where cid='$user' and user='$userby' order by id desc limit 1");    
$d0=mysqli_fetch_array($s0);
$fid=$d0['id'];
for($a=0; $a<=count($data); $a++){
$title=$titles[$a];
$under=$ids[$a];
$type=$data[$a];

mysqli_query($host,"insert into sync set under='$under',title='$title',type='$type',feature='$typed',featureid='$fid',cid='$user'");
}   
}
$desc=$tit;
$title="Find New ".$typed." Uploaded";


$token=array();
$x=0;
$s90=mysqli_query($host,"select id,p_device,s_device from students where cid='$user' and standard='$standard' and (length(s_device)>0 or length(p_device)>0) and (status='active' or status IS NULL) and session='$session'");
while($d90=mysqli_fetch_array($s90)){
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
}

$token=array_unique($token);

$s=mysqli_query($host,"select * from global where name='Student Key'");
$d=mysqli_fetch_array($s);
$key=$d['val'];

send_notification($title,$desc,$token,$key,$typed);

$arr['status']=true;
$arr['message']="success";
 
} 
}else{

if($delete){
mysqli_query($host,"update notes set dele='1' where id='$delete' and cid='$user'"); 
$arr['status']=true;
$arr['message']='successfully deleted';
}else{
$arr['status']=true;
$arr['message']='success';  
}
 
 
 
$arr['data']=array();
$arr['topic']=array();


$s4=mysqli_query($host,"SELECT timetabledata.standard as standard FROM timetabledata INNER JOIN time_table ON time_table.id=timetabledata.timetable where time_table.status='Active' and timetabledata.cid='$user' and timetabledata.sid='$username' and time_table.access='Yes'");
while($d4=mysqli_fetch_array($s4)){
$standard=$d4['standard'];
$row1['name']=$d4['standard'];
$row1['subtopic']=array();

$s0=mysqli_query($host,"SELECT timetabledata.subject as subject FROM timetabledata INNER JOIN time_table ON time_table.id=timetabledata.timetable where time_table.status='Active' and timetabledata.cid='$user' and timetabledata.standard='$standard' and timetabledata.sid='$username'  and time_table.access='Yes'");
while($d0=mysqli_fetch_array($s0)){
 $row4['name']=$d0['subject'];
$row1['subtopic'][]=$row4;    
    
}

}



  $s4=mysqli_query($host,"select * from staffaccess where cid='$branch' and staff='$staff' and (feature='All' or feature='$typed') and type='class' group by class"); 
         while($d4=mysqli_fetch_array($s4)){
             $stream=$d4['class'];
             $row1['name']=$d4['class'];
                $row1['subtopic']=array();
                
                $s0=mysqli_query($host,"select * from subject_link_with_class where user='$branch' and stream='$stream'");
                while($d0=mysqli_fetch_array($s0)){
                    $row4['name']=$d0['name'];
                    $row1['subtopic'][]=$row4;
                }
                
              $arr['topic'][]=$row1;
              
              
         }

$s1=mysqli_query($host,"select * from notes where cid='$user' and dele='0' and type='$typed' order by id desc");
while($d=mysqli_fetch_array($s1)){
   
$tid=$d['id'];
$row['title']=$d['title']; 
$row['desc']=$d['description']; 
$row['standard']=$d['standard']; 
$row['subject']=$d['subject']; 
$row['path']=$d['path']==''?NULL:'https://edpluss.com/school/admin-panel/cpanel/upload/notes/'.$d['path'];
$row['date']=date('d M Y',$d['date']); 
$row['id']=$tid; 
$row['syllabus']=array(); 

 
     $s2=mysqli_query($host,"select * from sync where cid='$user' and under='$tid'");
     while($d2=mysqli_fetch_array($s2)){
         $row2['title']=$d2['title'];
         $row['syllabus'][]=$row2;
     }
    
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