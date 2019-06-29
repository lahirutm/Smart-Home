<?php
require('../class.db.php');
require('../session.php');

if(isset($iot_user_id) && $iot_user_id>0){                      
     $html = '<table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Device Id</th>
            <th scope="col">Name</th>
            <th scope="col">Nickname</th>
            <th scope="col">Type</th>
            <th scope="col">Status</th>
            <th scope="col">Connectivity</th>
            <th scope="col"><span class="float-right">Action</span></th>
          </tr>
        </thead>
        <tbody>';
             
              $sql = "SELECT devices.id, devices.name, devices.nickname, devices.is_on, devices.is_online,device_types.type FROM devices
              JOIN device_types ON device_types.id=devices.device_type_id WHERE devices.user_id='$iot_user_id' AND devices.status = 1";
              $results = $db->select($sql);
              if($results){
                      $i=1;
                      foreach($results as $result){
                      $status = $result['is_on'] ==1 ? 'ON' : 'OFF';
                      $online = $result['is_online'] == 1 ? 'Online': 'Offline';
                          $html .= '<tr>
                            <td scope="row">'.$i++.'</td>
			                <td>'.md5($result['id']).'</td>
                            <td>'.$result['name'].'</td>
                            <td>'.$result['nickname'].'</td>
                            <td>'.$result['type'].'</td>
                            <td>'.$status.'</td>
                            <td>'.$online.'</td>
                            <td align="right">
                              <button class="btn btn-danger float-right" onclick="delete_device('.$result['id'].');">Delete</button>
                              <button class="btn btn-info float-right" onclick="edit_device('.$result['id'].');">Edit</button>
                            </td>
                          </tr>';
                      }

                      if($i==1) {
                              $html .= '<tr><td colspan="8">No devices found in your account !</td></tr>';
                      }
              }
              else $html .= '<tr><td colspan="8">No device information found.</td></tr>';


  $html .= '</tbody>
</table>';

echo $html;
}
?>
