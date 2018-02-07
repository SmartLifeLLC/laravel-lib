<?php

namespace App\Console\Commands;

use App\Constants\SystemConstants;
use App\Constants\URLs;
use App\Lib\Logger;
use App\Models\AdminScheduledNotification;
use Illuminate\Console\Command;
use App\Constants\NotificationTopic;

//Todo TEST.
class SendNotificationToGlobalTopics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:global';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to global user.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

    	var_dump("START");
        //Get notification data
	    Logger::info("STARTED SEND NOTIFICATION ");
	    $data = (new AdminScheduledNotification())->getNextGlobalNotificationData();

	    if(empty($data)) {
	    	var_dump("data empty");
	    	return;
	    }

	    //Send notification to Global Users

	    $message = [];
		if(!empty($data['title']))
			$message['title'] =$data['title'];
		if(!empty($data['contents']))
			$message['text'] = $data['contents'];


		if(empty($message)){
			//Alarm to slack.
			var_dump("MESSAGE EMPTY");
			return ;
		}

	    //Send to Firebase
	    $headers = [
		    "Authorization: key=". SystemConstants::getFirebaseKey(),
		    "Content-Type: application/json"
	    ];

	    $data = array(
		    "to"           => NotificationTopic::GLOBAL
	        ,"priority"     => "high"
	        ,"notification" => $message
	    );

	    $handle = curl_init();
	    curl_setopt($handle, CURLOPT_URL, URLs::FIREBASE_FCM_ENDPOINT);
	    curl_setopt($handle, CURLOPT_POST, true);
	    curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data));
	    $result = curl_exec($handle);
	    var_dump($result);
	    curl_close($handle);

	    //todo send result to slack.
	    //Send result to slack
	    //$this->result = $result;
    }
}
