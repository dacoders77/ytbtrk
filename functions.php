<?php 

function getVideoId($default_video_id = '') {

	$request_uri = $_SERVER['REQUEST_URI'];
	
	if (!empty($request_uri) && strlen($request_uri) > 1)
		$video_id = explode('/', explode('?', $request_uri, 2)[0], 3)[1];
	
	if ($video_id === '')
		return $default_video_id;
	
	if (preg_filter('/[a-z0-9\_\-]/i', '', $video_id) !== '')
		exit('Ошибка 404. Данной страницы не существует!');
	
	return $video_id;
	
}

function getPreviewData($video_id) {
    
	if (empty($video_id))
		exit('Ошибка 404. Данной страницы не существует!');
    
	// Проверяем наличие кеша ранее полученных данных
	$data_cash = getDataCash($video_id);
	if (!empty($data_cash) && is_array($data_cash))
		return $data_cash;
	
	// Получаем минимальные данные превью (на случай, если API Youtube не заработает)
	$data = getDefaultData($video_id);

	// Получаем данные превью через API Youtube
	$sours_url = $data['api_url'].'?'.http_build_query($data['api_params']);
	$video_data = getPageData($sours_url);

	// Если данные получены, дополняем ими ранее подготовленные данные
	if (!empty($video_data['items'][0]['snippet']) && is_array($video_data['items'][0]['snippet'])) {

		$data = mergeData($video_data['items'][0]['snippet'], $data, ['title','description','thumbnails']);

		if (!empty($data['thumbnails']) && is_array($data['thumbnails']) &&
			!empty($data['thumbnails']['maxres']) && is_array($data['thumbnails']['maxres'])) {
			$data['snippets']['image'] = $data['thumbnails']['maxres']['url'];
			$data['snippets']['width'] = $data['thumbnails']['maxres']['width'];
			$data['snippets']['height'] = $data['thumbnails']['maxres']['height'];
		}
		
		// Сохраняем данные в кеш
		saveDataCash($video_id, $data);

	}
	
	return $data;
	
}

function getDataCash($video_id) {
    
	$file_path = 'data/'.$video_id.'.json';
	
	if (!file_exists($file_path))
		return false;
	
	$file_data = file_get_contents($file_path);
	
	if (empty($file_data) || !is_string($file_data))
		return false;
		
	$file_data_arr = json_decode($file_data, 1);
	
	if (empty($file_data_arr) || !is_array($file_data_arr))
		return false;
	
	return $file_data_arr;
	
}

function saveDataCash($video_id, $data) {
	
	$file_path = 'data/'.$video_id.'.json';
	
	$file_data = json_encode($data);
	
	if (empty($file_data))
		return false;
	
	return file_put_contents($file_path, $file_data);
	
}

function getDefaultData($video_id) {
	
	return [
		'title'=>'Test title',
		'description'=>'Test description',
		'thumbnails'=>[],
		'snippets'=>[
			'url'=>'https://www.youtube.com/watch?v='.$video_id,
			'video'=>'https://www.youtube.com/embed/'.$video_id.'?autoplay=1',
			'image'=>'https://i.ytimg.com/vi/'.$video_id.'/default.jpg',
			'width'=>'120',
			'height'=>'90',
		],
		'api_url'=>'https://www.googleapis.com/youtube/v3/videos',
		'api_params'=>[
			'part'=>'snippet',
			'fields'=>'items(snippet(title,description,thumbnails))',
			'id'=>$video_id,
			'key'=>'AIzaSyBdvMJsZReCKbfORDLhCl1KJfqR4fCLFHw',
		],
	];
	
}

function getPageData($url, $method = 'curl') {
    
	switch ($method) {
		case 'curl':
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$page_data = curl_exec($ch);
			curl_close($ch);
			break;

		default:
			$page_data = file_get_contents($url);
			break;
	}
	
	if (!is_string($page_data) && !is_array($page_data))
		return false;
	
	if (substr($page_data, 0, 1) === '{') {
		$to_arr = json_decode($page_data, 1);
		if ($to_arr !== null)
			$page_data = $to_arr;
	}

	return $page_data;
	
}

function mergeData($data_from, $data_for, $merge_arr) {
    
	foreach ($merge_arr as $field_name) {
		
		if (empty($data_from[$field_name]))
			continue;
		
		$data_for[$field_name] = $data_from[$field_name];
		
	}
	
	return $data_for;
	
}