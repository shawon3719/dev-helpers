<?php

namespace App\Channels;

use App\Interfaces\SMSInterface;
use App\Models\Distributor;
use App\Models\Employee;
use App\Models\SmsHistory;
use App\Services\ErrorNotifierService;
use Illuminate\Notifications\Notification;

class TextMessageChannel
{
    protected $sms;
    protected $message;

    public function __construct(SMSInterface $sms)
    {
        $this->sms = $sms;
    }
    
    public function send($notifiable, Notification $notification)
    {
        if($notifiable instanceof Distributor)
        {
            $destination = $notifiable->primary_contact_no;
            if (empty($destination)) {
                $destination = $notifiable->alter_contact_no;
            }
        }elseif($notifiable instanceof Employee)
        {
            $destination = $notifiable->employee_mobile_no;
        }else
        {
            ErrorNotifierService::notifyError( '$notifiable must be instance of Distributor or Employee Model. Error form TextMessageChannel');
            return false; 
        }

        $this->message = $notification->toSMS()['message'];
        //log
        $historyId = SmsHistory::addHistory([
            'destination' => $destination,
            'body' => $this->message,
        ]);

        //IF phone number is empty
        if(empty($destination)){
            ErrorNotifierService::notifyError( 'SMS ID#'.$historyId.': Destination is empty!');
            return false;
        }

		$destination = $this->sanitize_phone($destination);

        //IF destination contain non numeric char
        if(!is_numeric($destination)){
            ErrorNotifierService::notifyError( 'SMS ID#'.$historyId.': Phone number should be contain all numeric value, given number ['.$destination.'] is not valid.');
            return false;
        }

        //$tempPhone = substr($destination, -11);
        if(strlen($destination) != 13){
            ErrorNotifierService::notifyError( 'SMS ID#'.$historyId.': Given phone number ['.$destination.'] is not a valid mobile phone number');
            return false;
        }

        $response = $this->sms->send($destination, $this->message);

        SmsHistory::updateHistory([
            'id' => $historyId,
            'gatewayResponse' => $response['gateway_response'],
            'status' => $response['status'] === 'success' ? 'DELIVERED' : 'FAILED',
            'smsId' => $response['data']['sms_uid'] ?? null,
            'destination' => $destination,
        ]);
    }

    
    /**
     * Description: This function will filter phone number and remove space, -, + sign. Also will add BD country code if its not there.
     * @param $phone
	*/	
	public function sanitize_phone($phone) {	
		//replace all unnecessary characters from phone numbers
		$phone = preg_replace('/[^0-9]/', '', $phone);
		
		$phone_length = strlen($phone);
		//country code check
		$findme   = '88';
		$code_pos = strpos($phone, $findme);
		//echo $code_pos;
		//if $phone_length = 13 and $code_pos 0 => good phone number with properly formated
		//if $phone_length = 11 and $code_pos false => good phone number without country code.
		
		if ($code_pos === false) {
			if( $phone_length == 11)
            {
				return "88".$phone;				
            }
		} else {
			if ($phone_length == 11 && $code_pos != 0)
            {
				return "88".$phone;
            }
		}
		return $phone; 		
	}
}
