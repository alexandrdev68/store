<?php
class Graph {
	
	static public $font = 'times_new_roman.ttf';
	static public $curr_dpi = 600;
	static public $img_path = 'static/images/';
	static public $img_name;


	/**
	 * Функция изменяет размер изображения по пути $path из исходных размеров в изображение
	 * с размерами $newwidth и $newheight сохраняя пропорции и возвращает массив данных модифицированного 
	 * изображения либо false если произошла ошибка.
	 * Пример: Graph::imgResize(80, 50, $_SERVER['DOCUMENT_ROOT'].'/upload/bikes/00001.jpg');
	 * @var static function
	 */
	static public function imgResize($newwidth, $newheight, $path, $newfile = true){
		list($src_width, $src_height) = getimagesize($path);

		//если размер не меняется выходим из метода
		if($src_width == $newwidth && $src_height == $newheight) return true;
		
		//определяем по какому измерению меняется размер больше
		$resize_horizontal = false;
		if($newwidth - $src_width < $newheight - $src_height) $resize_horizontal = true;
		
		if($resize_horizontal){
			$diff_value = $src_width - $newwidth;
			$arParam = array(
				'imgHeight'=>ceil($src_height * ($src_width - $diff_value) / $src_width),
				'imgWidth'=>$src_width - $diff_value,
			);
			if($arParam['imgHeight'] > $newheight) $newheight = $arParam['imgHeight']; 
			$yCoord = 0 + (ceil(($newheight - $arParam['imgHeight']) / 2));
			$xCoord = 0;
		}else{
			$diff_value = $src_height - $newheight;
			$arParam = array(
				'imgHeight'=>$src_height - $diff_value,
				'imgWidth'=>ceil($src_width * ($src_height - $diff_value) / $src_height),
			);
			$yCoord = 0;
			$xCoord = 0 + (ceil(($newwidth - $arParam['imgWidth']) / 2));
		}

		$resized = imagecreatetruecolor($newwidth, $newheight);
		imagefill($resized, 0, 0, imagecolorallocate($resized, 255, 255, 255));
		$source = imagecreatefromjpeg($path);

		$arParam['xCoord'] = $xCoord;
		$arParam['yCoord'] = $yCoord;

		if(imagecopyresampled($resized, $source, $arParam['xCoord'], $arParam['yCoord'], 0, 0, $arParam['imgWidth'], $arParam['imgHeight'], $src_width, $src_height)){
			if($newfile === true) $new_path = substr($path, 0, strpos($path, 'jpg') - 1).'_resized_'.$newwidth.'.jpg';
			else $new_path = $path;
			if(imagejpeg($resized, $new_path)){
				return array('path'=>$new_path, 'width'=>$newwidth, 'height'=>$newheight);
			};
			//return $path.'<br>'.$new_path.'<br>'.mb_strlen($path, 'utf-8');
		}
		return false;

		
	}

	/*	создает картинку с прозрачным фоном и тестом $text, с размерами, подогнанными под размер $fontsize текста
	*	Пример: Graph::create_img_text(18, 'какой-то текст');
	*	Путь к картинке задается Graph::$img_path.Graph::$img_name, Разрешение в Graph::$curr_dpi, название шрифта в Graph::$font
	*	(шрифты должны располагаться в папке upload/fonts/)
	*/	

	static public function create_img_text($fontsize, $text, $param = array('resized'=>false, 
																				'res_width'=>0, 
																				'res_height'=>0, 
																				'resampled'=>false, 
																				'color'=>array('red'=>255, 'green'=>255, 'blue'=>255, 'transparent'=>127)))
	{
		$f_size_pt = round($fontsize * (self::$curr_dpi / 72));
		$arRes = imagettfbbox($f_size_pt, 0, $_SERVER['DOCUMENT_ROOT'].'/wallet/'.self::$font, $text);
		$width = $arRes[2] - $arRes[0] + 10;
		$height = $arRes[1] - $arRes[7];
		$newimg = imagecreatetruecolor($width, $height);
		$color = imagecolorallocatealpha($newimg, isset($param['color']['red']) ? $param['color']['red'] : 255, isset($param['color']['green']) ? $param['color']['green'] : 255, isset($param['color']['blue']) ? $param['color']['blue'] : 255, isset($param['color']['transparent']) ? $param['color']['transparent'] : 127);
		$txt_color = imagecolorallocate($newimg, 0, 0, 0);
		$opacity = imagecolortransparent($newimg, $color);
		imagefill($newimg, 0, 0, $opacity);
		
		if($height > $f_size_pt) $y = $height - ($height - $f_size_pt) * 1.3;
		else $y = $height - 1 * 1.3;
		
		//echo $width.' '.$height.' '.$f_size_pt; die();
		
		//создание изображения с текстом
		$arRes = imagettftext($newimg, $f_size_pt, 0, 0, $y, $txt_color, $_SERVER['DOCUMENT_ROOT'].'/upload/fonts/'.self::$font, $text);
		
		
		//если заданы жесткие размеры исходной картинки
		if($param['resized'] === true){
			$image_resize = imagecreatetruecolor($param['res_width'], $param['res_height']);
			$color = imagecolorallocatealpha($image_resize, isset($param['color']['red']) ? $param['color']['red'] : 255, isset($param['color']['green']) ? $param['color']['green'] : 255, isset($param['color']['blue']) ? $param['color']['blue'] : 255, isset($param['color']['transparent']) ? $param['color']['transparent'] : 127);
			$opacity = imagecolortransparent($image_resize, $color);
			imagefill($image_resize, 0, 0, $opacity);
			if(@$param['resampled'] === true){
				if (imagecopyresampled($image_resize, $newimg, 0, 0, 0, 0, $param['res_width'], $param['res_height'], $width, $height) === false) return false;
			}
			else{
				if(imagecopyresized($image_resize, $newimg, 0, 0, 0, 0, $param['res_width'], $param['res_height'], $width, $height) === false) return false;
			}
			
			$width = $param['res_width'];
			$height = $param['res_height'];
		}
		
		if(imagepng($image_resize, $_SERVER['DOCUMENT_ROOT'].self::$img_path.self::$img_name) !== false){
			return array('path'=>$_SERVER['DOCUMENT_ROOT'].self::$img_path.self::$img_name, 'width'=>$width, 'height'=>$height);
		};
		
		return false;
		
	}
	
	/*Записывает фотографию в указанную директорию, указанным именем и с ограничением по размеру, переданным
	 * в качестве параметра, возвращает массив с ошибкой, если неправильный формат, 
	 * false в случае неудачного перемещения файла или полное имя файла в случае удачи
	 * Пример: Graph::upload_photo('upload/photo/', 1024);
	 * 
	 */
	static public function upload_photo($uploadDir = '../upload/', $filename, $maxsize = 2146304){
		//print_r(@$_FILES); die();
		if(@$_FILES['foto']['size'] > $maxsize){
			return array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['upload_photo_to_big']);
		}elseif(!(strtolower(strrpos(@$_FILES['foto']['name'], 'jpeg')) !== false || strtolower(strrpos(@$_FILES['foto']['name'], 'jpg')) !== false)){
			return array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_photo_format'].@$_FILES['foto']['name']);
		}
		$uploadFile = $uploadDir.$filename;
		if(move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)){
			//если файл успешно записан
			return $uploadFile;
		}else return false;
	}
}
?>