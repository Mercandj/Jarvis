<?php
namespace app\controller;
use \lib\Entities\Device;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class DeviceController extends \lib\Controller {

	/**
	* @uri    /device/add
	* @method POST
	* @return JSON with info about server Info
	*/
	public function addOrUpdate() {
		$json['succeed'] = true;

		$inputJSON 							= file_get_contents('php://input');
		$input 								= json_decode( $inputJSON, TRUE );
		$content 							= array_key_exists('content', $input) ? 							$input['content'] : '';
		$description 						= array_key_exists('description', $input) ?							$input['description'] : '';
		$operating_system 					= array_key_exists('operating_system', $input) ?					$input['operating_system'] : '';
		$android_app_gcm_id 				= array_key_exists('android_app_gcm_id', $input) ?					$input['android_app_gcm_id'] : '';
		$android_app_version_code 			= array_key_exists('android_app_version_code', $input) ?			$input['android_app_version_code'] : '';
		$android_app_version_name 			= array_key_exists('android_app_version_name', $input) ?			$input['android_app_version_name'] : '';
		$android_app_package 				= array_key_exists('android_app_package', $input) ?					$input['android_app_package'] : '';
		$android_device_model 				= array_key_exists('android_device_model', $input) ?				$input['android_device_model'] : '';
		$android_device_manufacturer 		= array_key_exists('android_device_manufacturer', $input) ?			$input['android_device_manufacturer'] : '';
		$android_device_version_os 			= array_key_exists('android_device_version_os', $input) ?			$input['android_device_version_os'] : '';
		$android_device_display 			= array_key_exists('android_device_display', $input) ?				$input['android_device_display'] : '';
		$android_device_bootloader 			= array_key_exists('android_device_bootloader', $input) ?			$input['android_device_bootloader'] : '';
		$android_device_language 			= array_key_exists('android_device_language', $input) ?				$input['android_device_language'] : '';
		$android_device_display_language	= array_key_exists('android_device_display_language', $input) ?		$input['android_device_display_language'] : '';
		$android_device_country 			= array_key_exists('android_device_country', $input) ?				$input['android_device_country'] : '';
		$android_device_timezone 			= array_key_exists('android_device_timezone', $input) ?				$input['android_device_timezone'] : '';
		$android_device_radio_version 		= array_key_exists('android_device_radio_version', $input) ?		$input['android_device_radio_version'] : '';
		$android_device_version_sdk 		= array_key_exists('android_device_version_sdk', $input) ?			$input['android_device_version_sdk'] : '';
		$android_device_version_incremental = array_key_exists('android_device_version_incremental', $input) ?	$input['android_device_version_incremental'] : '';
		$android_device_year				= array_key_exists('android_device_year', $input) ?					$input['android_device_year'] : '';
		$android_device_rooted 				= array_key_exists('android_device_rooted', $input) ? 				$input['android_device_rooted'] : '';

		$current_date = date('Y-m-d H:i:s');		

		$deviceManager = $this->getManagerof('Device');
		$json['debug'] = 'Gcm not updated.';
		
		if($deviceManager->getByIdGcm($android_app_gcm_id) == NULL) {

			$device = new Device(array(
				'id'				=> 0,
				'content' 			=> $content,
				'date_creation' 	=> $current_date,
				'date_update' 		=> $current_date,

				'operating_system' 					=> $operating_system,
				'android_app_gcm_id' 				=> $android_app_gcm_id,
				'android_app_version_code' 			=> $android_app_version_code,
				'android_app_version_name' 			=> $android_app_version_name,
				'android_app_package' 				=> $android_app_package,
				'android_device_model' 				=> $android_device_model,
				'android_device_language' 			=> $android_device_language,
				'android_device_display_language' 	=> $android_device_display_language,
				'android_device_country' 			=> $android_device_country,
				'android_device_version_sdk' 		=> $android_device_version_sdk,
				'android_device_timezone'			=> $android_device_timezone,
				'android_device_year' 				=> $android_device_year,
				'android_device_rooted' 			=> $android_device_rooted
			));

			$deviceManager->add($device);
			$json['debug'] = 'Gcm created.';

		} else {

			$device = new Device(array(
				'id'				=> 0,
				'content' 			=> $content,
				'date_update' 		=> $current_date,

				'operating_system' 					=> $operating_system,
				'android_app_gcm_id' 				=> $android_app_gcm_id,
				'android_app_version_code' 			=> $android_app_version_code,
				'android_app_version_name' 			=> $android_app_version_name,
				'android_app_package' 				=> $android_app_package,
				'android_device_model' 				=> $android_device_model,
				'android_device_language' 			=> $android_device_language,
				'android_device_display_language' 	=> $android_device_display_language,
				'android_device_country' 			=> $android_device_country,
				'android_device_version_sdk' 		=> $android_device_version_sdk,
				'android_device_timezone'			=> $android_device_timezone,
				'android_device_year' 				=> $android_device_year,
				'android_device_rooted' 			=> $android_device_rooted
			));

			$deviceManager->update($device);
			$json['debug'] = 'Gcm updated.';
		}

		HTTPResponse::send(json_encode($json));
	}

	public function sendPushByGcm() {
		$json['succeed'] = true;

		$inputJSON 		= file_get_contents('php://input');
		$input 			= json_decode( $inputJSON, TRUE );
		$gcmId 			= array_key_exists('gcmId', $input) ? 			$input['gcmId'] : '';
		$googleApiKey	= array_key_exists('googleApiKey', $input) ? 	$input['googleApiKey'] : '';
		$message	= array_key_exists('message', $input) ? 	$input['message'] : '';

		if(empty($googleApiKey)) {
			$googleApiKey = $this->_app->_config->get('google_api_key');
		}

		$url = 'https://gcm-http.googleapis.com/gcm/send';
		$fields = array(
			'to' => $gcmId,
			'data' => array(
				'n_m' => $message	
			)
		);
		$headers = array(
			'Authorization: key=' . $googleApiKey,
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);

		$json['succeed'] = true;
		$json['debug'] =
			'googleApiKey == ' . $googleApiKey . '  '.
			'gcmId == ' . $gcmId . '  '.
			'message == ' . $message;
		HTTPResponse::send(json_encode($json));
	}

	public function sendPushToDev() {
		$json['succeed'] = true;

		$inputJSON 		= file_get_contents('php://input');
		$input 			= json_decode( $inputJSON, TRUE );
		$type			= array_key_exists('type', $input) ? 			$input['type'] : '';
		$title			= array_key_exists('title', $input) ? 			$input['title'] : '';
		$message		= array_key_exists('message', $input) ? 		$input['message'] : '';
		$action_data	= array_key_exists('action_data', $input) ? 	$input['action_data'] : '';
		$devices 		= $this->getManagerof('Device')->getAllDevVersion();

		foreach ($devices as $device) {

			$url = 'https://gcm-http.googleapis.com/gcm/send';
			$fields = array(
				'to' 	=> $device->getAndroid_app_gcm_id(),
				'data' 	=> array(
					't' 			=> $type,
					'n_t' 			=> $title,
					'n_m' 			=> $message,
					'd' 			=> $action_data
				)
			);
			$headers = array(
				'Authorization: key=' . $this->_app->_config->get('google_api_key'),
				'Content-Type: application/json'
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			if ($result === FALSE) {
				die('Curl failed: ' . curl_error($ch));
			}
			curl_close($ch);
		}

		$json['debug'] = 'count = ' . count($devices);

		HTTPResponse::send(json_encode($json));
	}

	public function sendPushToAll() {
		$json['succeed'] = true;

		$inputJSON 		= file_get_contents('php://input');
		$input 			= json_decode( $inputJSON, TRUE );
		$type			= array_key_exists('type', $input) ? 			$input['type'] : '';
		$title			= array_key_exists('title', $input) ? 			$input['title'] : '';
		$message		= array_key_exists('message', $input) ? 		$input['message'] : '';
		$action_data	= array_key_exists('action_data', $input) ? 	$input['action_data'] : '';
		$devices 		= $this->getManagerof('Device')->getAll();

		foreach ($devices as $device) {

			$url = 'https://gcm-http.googleapis.com/gcm/send';
			$fields = array(
				'to' 	=> $device->getAndroid_app_gcm_id(),
				'data' 	=> array(
					't' 			=> $type,
					'n_t' 			=> $title,
					'n_m' 			=> $message,
					'd' 			=> $action_data
				)
			);
			$headers = array(
				'Authorization: key=' . $this->_app->_config->get('google_api_key'),
				'Content-Type: application/json'
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			if ($result === FALSE) {
				die('Curl failed: ' . curl_error($ch));
			}
			curl_close($ch);
		}

		$json['debug'] = 'count = ' . count($devices);

		HTTPResponse::send(json_encode($json));
	}
}