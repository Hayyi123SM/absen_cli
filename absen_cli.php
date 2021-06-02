<?php 
	function getToken($url){
		$ch = curl_init();
		$option = [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_ENCODING => "",
			CURLOPT_COOKIEJAR => 'cookie-name.txt',
			CURLOPT_COOKIESESSION => true,
			// CURLOPT_HTTPHEADER => array(
			// 	"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apngsigned-exchange;v=b3;q=0.9",
			// 	"Accept-Encoding: gzip, deflate, br",
			// 	"Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7",
			// 	"Cache-Control: max-age=0",
			// 	"Connection: keep-alive",
			// 	"Host: elearning.akakom.ac.id",
			// 	"Referer: https://elearning.akakom.ac.id/",
			// 	"User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.85 Safari/537.36",
			// ),
		];
		curl_setopt_array($ch, $option);
		$result = curl_exec($ch);
		$form = explode("input", $result);
		$token = explode('"', $form[2]);
		curl_close($ch);
		return $token[5];
	}

	function login($url, $user, $pw, $opsi){
		$ch = curl_init();
		$token = getToken($url);
		$option = [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_POST => true,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_ENCODING => "",
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => "anchor=&logintoken=".$token."&username=".$user."&password=".$pw."&rememberusername=1",
			CURLOPT_COOKIEJAR => 'cookie-name.txt',
			CURLOPT_COOKIEFILE => 'cookie-name.txt',
			// CURLOPT_HTTPHEADER => array(
			// 	"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng",
			// 	"Accept-Encoding: gzip, deflate, br",
			// 	"Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7",
			// 	"Connection: keep-alive",
			// 	"Origin:  https://elearning.akakom.ac.id",
			// 	"Content-Type: application/x-www-form-urlencoded",
			// 	"Referer: https://elearning.akakom.ac.id/login/index.php",
			// 	"User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.85 Safari/537.36",
			// ),
		];
		curl_setopt_array($ch, $option);
		$result = curl_exec($ch);
		curl_close($ch);
		$error = strpos($result, '<div class="loginerrors mt-3">');
		if(!$error){
			$span = explode('<span class="usertext mr-1">', $result);
			$tutupSpan = explode('</span>', $span[1]);
			$nama = [];
			preg_match("/[^\d]+/", $tutupSpan[0],$nama);
			echo "Hai, ". $nama[0] . "\n";
			for ($i=0; $i < 5; $i++) { 
				praAbsen($opsi[$i]);
			}
		}else{
			echo "---------------------------------- \n";
			echo "| Username atau Password salah!! |\n";
			echo "---------------------------------- \n";
			start();
		}
	}

	function praAbsen($url){
		$ch = curl_init();
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_ENCODING => "",
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_COOKIEFILE => 'cookie-name.txt',
			CURLOPT_CUSTOMREQUEST => 'GET',
			// CURLOPT_HTTPHEADER => array(
			// 	"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng",
			// 	"signed-exchange;v=b3;q=0.9",
			// 	"Accept-Encoding: gzip, deflate, br",
			// 	"Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7",
			// 	"Cache-Control: max-age=0",
			// 	"Connection: keep-alive",
			// 	"Host: elearning.akakom.ac.id",
			// 	"Referer: https://elearning.akakom.ac.id/login/index.php",
			// 	"User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.85 Safari/537.36",
			// ),
		];
		curl_setopt_array($ch, $options);
		$result = curl_exec($ch);
		curl_close($ch);
		$cell2 = explode('<td class="statuscol cell c2 lastcol" style="text-align:center;width:*;" colspan="3">', $result);
		$matkul = explode("id=", $url);
			switch ($matkul[1]) {
				case 13085:
					$nMatkul = "PWC";
					break;
				case 13086:
					$nMatkul = "PrakPWC";
					break;
				case 18860:
					$nMatkul = "Kec.Butan";
					break;
				case 14391:
					$nMatkul = "ALVECMA";
					break;
				case 13083:
					$nMatkul = "Jaringan";
					break;
				default:
					$nMatkul = "Ada yang salah";
					break;
			}
		if (count($cell2) != 1) {
			$link = explode('<a href="', $cell2[1]);
			$endpoint = explode('">', $link[1]);
			$file = fopen('endpoint.txt', 'a+');
			fwrite($file, $endpoint[0]."\n");
			fclose($file);
			// return var_dump($endpoint);
			return absen($endpoint[0],$nMatkul, $url);
		}else{
			echo "=================================== \n";
			echo " | Absen ".$nMatkul." belum ada!! |\n";
			echo "=================================== \n";
		}
	}

	function absen($url, $nMatkul, $opsi){
			$ch = curl_init();
			$options = [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_COOKIEFILE => 'cookie-name.txt',
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_ENCODING => "",
				// CURLOPT_HTTPHEADER => array(
				// 	"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng",
				// 	"signed-exchange;v=b3;q=0.9",
				// 	"Accept-Encoding: gzip, deflate, br",
				// 	"Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7",
				// 	"Cache-Control: max-age=0",
				// 	"Connection: keep-alive",
				// 	"Host: elearning.akakom.ac.id",
				// 	"Referer: https://elearning.akakom.ac.id/login/index.php",
				// 	"User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.85 Safari/537.36",
				// ),
			];
			curl_setopt_array($ch, $options);
			$result = curl_exec($ch);
			curl_close($ch);
			if ($opsi == "https://elearning.akakom.ac.id/mod/attendance/view.php?id=13086") {
				$label = explode('<label class="form-check-inline form-check-label  fitem  ">', $result);
				$radio = explode(' ', $label[1]);
				$Rvalue = explode('=', $radio[9]);
				$key = explode('?', $url);
				return grab_prakPWC($key[0],$key[1],$Rvalue[1]);
				
			}else{
				echo "+++++++++++++++++++++++++++++++++++++ \n";
				echo "+\[ Absen ".$nMatkul." berhasil!! ]/+ \n";
				echo "+++++++++++++++++++++++++++++++++++++ \n";
			}
	}

	function grab_prakPWC($url, $key, $status){
		$sesskey = explode("=", $key);
		$ch = curl_init();
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_COOKIEFILE => 'cookie-name.txt',
			CURLOPT_FOLLOWLOCATION => TRUE,
			CURLOPT_ENCODING => "",
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $key."&sesskey".$sesskey[2]."&_qf__mod_attendance_student_attendance_form=1&mform_isexpanded_id_session=1&status=".$status."&submitbutton=Save+changes
",
			CURLOPT_COOKIEFILE => 'cookie-name.txt',
			CURLOPT_COOKIEJAR => 'cookie-name.txt',
			// CURLOPT_HTTPHEADER => array(
			// 	"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng",
			// 	"signed-exchange;v=b3;q=0.9",
			// 	"Accept-Encoding: gzip, deflate, br",
			// 	"Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7",
			// 	"Cache-Control: max-age=0",
			// 	"Connection: keep-alive",
			// 	"Host: elearning.akakom.ac.id",
			// 	"Referer: https://elearning.akakom.ac.id/login/index.php",
			// 	"User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.85 Safari/537.36",
			// ),
		];
		curl_setopt_array($ch, $options);
		$result = curl_exec($ch);
		curl_close($ch);
		echo "+++++++++++++++++++++++++++++++++++++ \n";
		echo "+\[ Absen Praktik PWC berhasil!! ]/+ \n";
		echo "+++++++++++++++++++++++++++++++++++++ \n";
	}

	function start($ulang = true){
	while ($ulang) {
			echo "Slahkan masukan username : ";
			$us = trim(fgets(STDIN));
			echo "Silahkan masukan password : ";
			$pw = trim(fgets(STDIN));
			$matkul = [
				"https://elearning.akakom.ac.id/mod/attendance/view.php?id=13085",
				"https://elearning.akakom.ac.id/mod/attendance/view.php?id=13086",
				"https://elearning.akakom.ac.id/mod/attendance/view.php?id=18860",
				"https://elearning.akakom.ac.id/mod/attendance/view.php?id=14391",
				"https://elearning.akakom.ac.id/mod/attendance/view.php?id=13083",
			];
			// $ulg = true;
			// while ($ulg) {
			// 	echo "Silahkan masukan pilihan matkul(masukan dengan angka): ";
			// 	$opsi = trim(fgets(STDIN));
			// 	switch ($opsi) {
			// 		case 1:
			// 			$matkul = "https://elearning.akakom.ac.id/mod/attendance/view.php?id=13085";
			// 			$ulg = false;
			// 			break;
			// 		case 2:
			// 			$matkul = "https://elearning.akakom.ac.id/mod/attendance/view.php?id=13086";
			// 			$ulg = false;
			// 			break;
			// 		case 3:
			// 			$matkul = "https://elearning.akakom.ac.id/mod/attendance/view.php?id=18860";
			// 			$ulg = false;
			// 			break;
			// 		case 4:
			// 			$matkul = "https://elearning.akakom.ac.id/mod/attendance/view.php?id=14391";
			// 			$ulg = false;
			// 			break;
			// 		case 5:
			// 			$matkul = "https://elearning.akakom.ac.id/mod/attendance/view.php?id=13083";
			// 			$ulg = false;
			// 			break;
			// 		default:
			// 			echo "+---------------------------+ \n";
			// 			echo "+ Pilihan matkul tidak ada! + \n";
			// 			echo "+---------------------------+ \n";
			// 			break;
			// 	}
			// }
			if ($us == "" && $pw == "") {
				echo "----------------------------------------------- \n";
				echo "| Username atau Password tidak boleh kosong!! | \n";
				echo "----------------------------------------------- \n";
			}else{
				$url = "https://elearning.akakom.ac.id/login/index.php";
				login($url,$us,$pw,$matkul);
				$ulang = false;
			}
		}
	}
	
	echo "Hallo Selamat Datang Di Bot Absen Heandsome \n";
	echo "--------------------------------------------\n";
	start();

	








 ?>