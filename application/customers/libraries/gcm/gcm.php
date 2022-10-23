<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class GCM {

    // constructor
    function __construct() {
        
    }

    // sending push message to single user by gcm registration id
    public function send($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    // Sending message to a topic by topic id
    public function sendToTopic($to, $message) {
       /* $fields = array(
            'to' => '/topics/' . $to,
            'data' => $message,
        );*/
        $message_info = $message['data']['message']['message'];
        $group_name = $message['data']['message']['chat_room_id'];
        $user_name = $message['data']['message']['user_name'];
       // log_message('error','message_info==============28'.print_r($message_info,true));
        $title= $group_name." Sent by: ".$user_name;
        
        $fields=array('to'=>"/topics/all",
				               'notification'=>array('title'=>$title,'body'=>$message_info,'tag' =>$message));
        
        return $this->fcm_chat_message_notification($fields);
    }

     // Sending message to a topic by topic id for Only Chat app Users
    public function sendToTopic_chat_users($to, $message) {
       /* $fields = array(
            'to' => '/topics/' . $to,
            'data' => $message,
        );*/
        $message_info = $message['data']['message']['message'];
        $group_name = $message['data']['message']['chat_room_id'];
        $user_name = $message['data']['message']['user_name'];
       // log_message('error','message_info==============28'.print_r($message_info,true));
        $title= $group_name." Sent by: ".$user_name;
        
        $fields=array('to'=>"/topics/all",
				               'notification'=>array('title'=>$title,'body'=>$message_info,'tag' =>$message));
        
        return $this->fcm_chat_message_for_chat_app_users($fields);
    }

    // sending push message to multiple users by gcm registration ids
    public function sendMultiple($registration_ids, $message) {
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message,
        );

        return $this->sendPushNotification($fields);
    }

    // function makes curl request to gcm servers
    private function sendPushNotification($fields) {

        // Set POST variables
        $url = 'https://gcm-http.googleapis.com/gcm/send';
       // $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return $result;
    }


    public function fcm_chat_message_notification($fields)
	{
				/*$message= "Hi how are you\n";  
				$title= "TEST CHAT";
				$date='FCM';*/
				//log_message('error','fcm_chat_message_notification==============28'.print_r($fields,true));
				$path_to_fcm='https://fcm.googleapis.com/fcm/send';
				//$server_key="AIzaSyDvt3dpbX4f0cUZbpsuQgNziUV4hzMD8gU";
				$server_key="AIzaSyB424Ma6dDfdzf2ELLY9YqUG-ud09iuUXM";
				//log_message('error','message==========85'.print_r($message,true));
				//$query = $this->ci->panacea_common_model->get_check_email_id_for_fcm($email);


				/*$sql="select fcm_token from fcm_info where topic='$topic'";
				$result=mysqli_query($con,$sql);
				$row=mysqli_fetch_row($result);
				$key=$row[0];*/

				$headers=array('Authorization:key='.$server_key,
				               'Content-Type:application/json');
				               
				/* $fields=array('to'=>"/topics/all",
				               'notification'=>array('title'=>$title,'body'=>$message));*/
				               
				                 	//$ar=array();

				//$sql1="insert into notification_message(title,number,message)values('$title','$date','$message')";

				/*$query = $this->ci->panacea_common_model->insert_into_notification_message($title,$date,$message);

				                if ($query) {
				              // $last_id = mysqli_insert_id($con);
				                //$ar['Ann_id']=$last_id ;
				                $status =1;
				                $message="Added Succesfully";
				                $ar['message']=$message;
				                } else {
				                $message="Not Added";
				                $ar['message']=$message;
				                }*/
				               
				               
				               
				$payload=json_encode($fields);

				$curl_session=curl_init();
				curl_setopt($curl_session, CURLOPT_URL, $path_to_fcm);
				curl_setopt($curl_session, CURLOPT_POST, true);
				curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
				curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);

				$result=curl_exec($curl_session);

				curl_close($curl_session);
				//mysqli_close($con);
				/*$ar['status']=$status;
				   $ajson = array();
				   $ajson[] = $ar;
				   $finalresult=json_encode($ajson);
				   echo $finalresult;*/
				   //echo $sql1;


				/*$myfile = fopen("send_notification.txt", "a") or die("Unable to open file!");
				        $txt = "title:".$title."message: ".$message."\nOutput: ".$finalresult;
				        fwrite($myfile, $txt);*/

				       /* $myfile = fopen("send_notification.txt", "a") or die("Unable to open file!");
				        $txt = "title:".$title."message: ".$message;
				        fwrite($myfile, $txt);
				     //    fwrite($myfile,$sql);
				        fclose($myfile);*/
	}

	public function fcm_chat_message_for_chat_app_users($fields)
	{
		
		/*$message= "Hi how are you\n";  
		$title= "TEST CHAT";
		$date='FCM';*/
		//log_message('error','fcm_chat_message_notification==============28'.print_r($fields,true));
		$path_to_fcm='https://fcm.googleapis.com/fcm/send';
		//$server_key="AIzaSyDvt3dpbX4f0cUZbpsuQgNziUV4hzMD8gU";
		$server_key="AIzaSyDJiva1DJExUM0bxypYe83s1UEeucdY7bk";
		//log_message('error','message==========85'.print_r($message,true));
		//$query = $this->ci->panacea_common_model->get_check_email_id_for_fcm($email);


		/*$sql="select fcm_token from fcm_info where topic='$topic'";
		$result=mysqli_query($con,$sql);
		$row=mysqli_fetch_row($result);
		$key=$row[0];*/

		$headers=array('Authorization:key='.$server_key,
		               'Content-Type:application/json');
		               
		/* $fields=array('to'=>"/topics/all",
		               'notification'=>array('title'=>$title,'body'=>$message));*/
		               
		                 	//$ar=array();

		//$sql1="insert into notification_message(title,number,message)values('$title','$date','$message')";

		/*$query = $this->ci->panacea_common_model->insert_into_notification_message($title,$date,$message);

		                if ($query) {
		              // $last_id = mysqli_insert_id($con);
		                //$ar['Ann_id']=$last_id ;
		                $status =1;
		                $message="Added Succesfully";
		                $ar['message']=$message;
		                } else {
		                $message="Not Added";
		                $ar['message']=$message;
		                }*/
		               
		               
		               
		$payload=json_encode($fields);

		$curl_session=curl_init();
		curl_setopt($curl_session, CURLOPT_URL, $path_to_fcm);
		curl_setopt($curl_session, CURLOPT_POST, true);
		curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);

		$result=curl_exec($curl_session);

		curl_close($curl_session);
		//mysqli_close($con);
		/*$ar['status']=$status;
		   $ajson = array();
		   $ajson[] = $ar;
		   $finalresult=json_encode($ajson);
		   echo $finalresult;*/
		   //echo $sql1;


		/*$myfile = fopen("send_notification.txt", "a") or die("Unable to open file!");
		        $txt = "title:".$title."message: ".$message."\nOutput: ".$finalresult;
		        fwrite($myfile, $txt);*/

		       /* $myfile = fopen("send_notification.txt", "a") or die("Unable to open file!");
		        $txt = "title:".$title."message: ".$message;
		        fwrite($myfile, $txt);
		     //    fwrite($myfile,$sql);
		        fclose($myfile);*/
	}
	
}

?>