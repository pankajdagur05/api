<?php  include("session.php"); ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> 
<meta name="viewport" content="width=device-width, initial-scale=0.6" />

<?php 
$date=date('Y-m-d');
if($_GET['fromdate']){
$fromdate=$_GET['fromdate'];
}else{
$fromdate=$date;    
}
$q="select staff_attendance.*,staff.name as sname,staff.staff_id as stid from staff_attendance INNER JOIN staff ON staff.id=staff_attendance.sid where staff_attendance.subdomain='$branchid' and staff_attendance.status='p' and staff_attendance.idate='$fromdate' and (staff.consi IS NULL or staff.consi='No') order by staff.name asc";
$s=mysqli_query($host,$q);

?>
<div class="row">
<div class="col-lg-8" id="printarea" style="overflow: visible; position: relative;">
        <h2 class="text-danger" style="font-size:22px;margin-top: 19px;"><b><?php if($fromdate==$date){ echo "Today Date"; }else{  echo date('d M Y',strtotime($fromdate)); } ?></b>  </h1>
        
        <h4 class="text-success"><b>Persent</b> <span class="badge"><?php echo mysqli_num_rows($s); ?></span></h4>

		 
<?php
 while($staff=mysqli_fetch_array($s)){?>

 <div class="panel panel-default">
     
     <div class="panel-heading">
         <div class="row">
             <div class="col-lg-12" style="font-size:16px;">
        <b><?php echo $staff['sname']; $grandmint=0; ?> </b> : <?php echo $fromdate; if($todate){ echo "-".$todate; } ?>   - <?php echo 'Staff ID : '.$staff['stid']; ?> <label class="label <?php if($staff['status']=='A'){ echo 'label-danger'; $status='Absent'; }else{ if($staff['status']=='L'){ echo 'label-warning'; $status='Leave'; }else{ echo 'label-success'; $status='Punch'; } } ?>"><?php echo $status; ?> <span class="badge" id="status" style="font-size:10px;" ><?php  $turn=count(explode(",",$staff['time'])); echo ceil($turn/2);  ?></span></label>
        </div>
         
        
        </div>
     </div>
      <div class="panel-body">
          
          
          <table classs="table table-hover" style="width:100%;">
              <tr>
                  <td>Date</td>
                  <td>In Time</td>
                  <td>Out Time</td>
                  <td>Duration</td>
              </tr>
              <?php $arr=explode(",",$staff['time']); $arr2=explode(",",$staff['uploadby']); $arr3=explode(",",$staff['punch']); for($a=0; $a<$turn; $a++){  
                  if($arr[$a+1]){
              $start=strtotime($staff['idate'].$arr[$a]);
              $end=strtotime($staff['idate'].$arr[$a+1]);
$dateDiff = intval(($end-$start)/60);
$hours = intval($dateDiff/60);
$minutes = $dateDiff%60;   

 $grandmint+=$hours*60 + $minutes;
 
                  }else{
                     $hours=0; 
                     $minutes=0;
                  }
              ?>
              <tr>
                  <td><?php echo date('d-m-Y',strtotime($staff['idate']));  ?></td>
                   <td><?php echo '<b>'.$arr[$a].'</b>'; if($arr2[$a]){ echo '<br/>('.$arr2[$a].')'; } if($arr3[$a]){ echo '('.$arr3[$a].')'; }else{   if($arr[$a]){ echo '(qr)'; }    } ?></td>
                    <td><?php echo '<b>'.$arr[$a+1].'</b>'; if($arr2[$a+1]){ echo '<br/>('.$arr2[$a+1].')'; }  if($arr3[$a+1]){ echo '('.$arr3[$a+1].')'; }else{  if($arr[$a+1]){ echo '(qr)'; }   } ?></td>
                     <td><?php echo '<b>'.$hours." HR : ".$minutes." Min </b>"; ?></td>
              </tr>
              <?php $a++; }  ?>
              
              
             
          </table>
           <?php if($a>2){  $hr=intval($grandmint / 60);
              $min=$grandmint % 60;   ?>
          <span style="text-align: center;
    background: #eee;
    padding: 5px;
    float: right;
    margin-right: 138px;
    font-size: 12px;" colspan="4"><?php echo 'Total Duration  : <b>'.$hr." HR : ".$min." Min </b>";   ?></span>
    <?php } ?>
      </div>
     
 </div>
 
 
<?php } ?>
				
</div>

<div class="col-lg-4">
    
<?php $q="select name,post from staff where id IN (select sid from staff_attendance where subdomain='$branchid' and staff_attendance.idate='$fromdate' and status='a') and branch='$branchid' and status='Active' and (staff.consi IS NULL or staff.consi='No') order by name asc";
$s=mysqli_query($host,$q); ?>
      <h4 class="text-danger"><b>Absent</b> <span class="badge"><?php echo mysqli_num_rows($s); ?></span></h4>
      <table class="table table-striped">
          <tr>
              <th>Name</th>
              <th>Post</th>
          </tr>
      
<?php      
 while($staff=mysqli_fetch_array($s)){?>
<tr>

<td><?php echo $staff[0]; ?></td>    
<td><?php echo $staff[1]; ?></td>      
</tr>
 
 
<?php } ?>
		
    </table>
    
    
    
    
    
<?php   $q="select name,post from staff where id NOT IN (select sid from staff_attendance where subdomain='$branchid' and staff_attendance.idate='$fromdate') and branch='$branchid' and status='Active' and (consi IS NULL or consi='No') order by name asc";
$s=mysqli_query($host,$q); ?>
      <h4 class="text-danger"><b>Not Punched</b> <span class="badge"><?php echo mysqli_num_rows($s); ?></span></h4>
      <table class="table table-striped">
          <tr>
              <th>Name</th>
              <th>Post</th>
          </tr>
      
<?php      
 while($staff=mysqli_fetch_array($s)){?>
<tr>

<td><?php echo $staff[0]; ?></td>    
<td><?php echo $staff[1]; ?></td>      
</tr>
 
 
<?php } ?>
		
    </table>
    
    
    <?php $q="select name,post from staff where branch='$branchid' and status='Active' and consi='yes' order by name asc";
$s=mysqli_query($host,$q); ?>
      <h4 class="text-danger"><b>Not Consider Attendance</b> <span class="badge"><?php echo mysqli_num_rows($s); ?></span></h4>
      <table class="table table-striped">
          <tr>
              <th>Name</th>
              <th>Post</th>
          </tr>
      
<?php      
 while($staff=mysqli_fetch_array($s)){?>
<tr>

<td><?php echo $staff[0]; ?></td>    
<td><?php echo $staff[1]; ?></td>      
</tr>
 
 
<?php } ?>
		
    </table>
    
    
    
    <?php $q="select name,post from staff INNER JOIN staff_attendance ON staff_attendance.sid=staff.id where staff.branch='$branchid' and staff.status='Inactive' and staff_attendance.date='$date' order by staff.name asc";
$s=mysqli_query($host,$q); ?>
      <h4 class="text-danger"><b>Inactive Staff Attendance</b> <span class="badge"><?php echo mysqli_num_rows($s); ?></span></h4>
      <table class="table table-striped">
          <tr>
              <th>Name</th>
              <th>Post</th>
          </tr>
      
<?php      
 while($staff=mysqli_fetch_array($s)){?>
<tr>

<td><?php echo $staff[0]; ?></td>    
<td><?php echo $staff[1]; ?></td>      
</tr>
 
 
<?php } ?>
		
    </table>
    
    
</div>
</div>
