<form name="formPixel" id="formPixel" method="post">
	<input type="text" name="command" id="command" value="<?=(isset($_POST['command']) ? $_POST['command'] : '') ;?>" style="text-transform:uppercase;" maxlength=""/>
  <input type="submit" name="execute" value="execute" />
</form>

<?php
	session_start();
	//INPUT: I, C, L, V, H, F, S and X

	$input = '';
	if(isset($_POST['command'])) {
		
		//$input = strtolower(key($_POST['command']));
		$input = strtolower($_POST['command']);
		
	}
	
	$arr_input = explode(" ", $input);

	// Check if table is SET
	if(isset($_SESSION['tableset'])) { // Enable execution of all INPUTS

		$result = '';
	
		switch($arr_input[0]) {
			case 'i':
				$start_x = isset($arr_input[1]) ? $arr_input[1] : NULL;
				$start_y = isset($arr_input[2]) ? $arr_input[2] : NULL;
				$result = setTable($start_x, $start_y, 'O');
				
				break;
			case 'c':
			
				$start_x = isset($_SESSION['tableset_x']) ? $_SESSION['tableset_x'] : NULL;
				$start_y = isset($_SESSION['tableset_y']) ? $_SESSION['tableset_y'] : NULL;
				$result = clearTable($start_x, $start_y, 'O');
				
				break;
			case 'l':
				
				$axis_x = $arr_input[1];
				$axis_y = $arr_input[2];
				$fill_color = $arr_input[3];
				$result = fillPixel($axis_x, $axis_y, strtoupper($fill_color));;

				break;
			case 'v':

				$axis_x = $arr_input[1];
				$axis_y1 = $arr_input[2];
				$axis_y2 = $arr_input[3];
				$fill_color = $arr_input[4];
				$result = fillPixelVertical($axis_x, $axis_y1, $axis_y2, strtoupper($fill_color));

				break;
			case 'h':

				$axis_x1 = $arr_input[1];
				$axis_x2 = $arr_input[2];
				$axis_y = $arr_input[3];
				$fill_color = $arr_input[4];
				$result = fillPixelHorizontal($axis_x1, $axis_x2, $axis_y, strtoupper($fill_color));

				break;
			case 'f':

				$end_x = $arr_input[1];
				$end_y = $arr_input[2];
				$fill_color = $arr_input[3];
				$result = fillPixelRegion($end_x, $end_y, strtoupper($fill_color));

				break;																				
			case 's':
				
				echo $_SESSION['tableset'];
				
				break;
			case 'x':
				session_destroy();
				echo "Session has been terminated!";
				break;				
		}
		
		// Check for ERROR
		$strErrorHandler =  substr($result,0,3);
		if($strErrorHandler=='err') {
			
			echo errorHandler($result);
			
		}
		
	} else if($arr_input[0]=='i'){ // Enable execution of "I" only
	
		//$input = strtolower(key($_GET));
		//$arr_input = explode("*", $input);
		$start_x = isset($arr_input[1]) ? $arr_input[1] : NULL;
		$start_y = isset($arr_input[2]) ? $arr_input[2] : NULL;
		
		$tableset = setTable($start_x, $start_y, 'O');

		$strErrorHandler =  substr($tableset,0,3);
		if($strErrorHandler=='err') {
			
			errorHandler($tableset);
			
		}

	} else {
		
		echo  errorHandler('err4');
		 
	}

	function fillPixelRegion($end_x, $end_y, $fill_color) {
		
		$start_x = 1;
		$start_y = 1;
		
		// Check if input axis set
		if(isset($end_x) && isset($end_y)) {
			
			$arrTable = array();
			
			// Check if tablearray session exists
			if(isset($_SESSION['tablearray'])) {
				
				if(isset($_SESSION['tablearray'][$end_y][$end_x])) {
					
					for($y=$start_x; $y<=$end_y; $y++) {
						
						for($x=$start_y; $x<=$end_x; $x++) {
						
							// Set X and Y1 to Y2 axis to tablearray and store to session
							$_SESSION['tablearray'][$y][$x] = $fill_color;
							
						}
						
					}
					return createTable($_SESSION['tablearray']);
					
				} else {
					
					return "err5|{$end_x}|{$end_y}";
					
				}
				
			} else {
				
				return 'err4';
				
			}
			
		} else {
			
			return 'err1';
			
		}
		
		return false;
		
	}

	function fillPixelHorizontal($axis_x1, $axis_x2, $axis_y, $fill_color) {
		
		// Check if input axis set
		if(isset($axis_x1) && isset($axis_x2) && isset($axis_y)) {
			
			$arrTable = array();
			
			// Check if tablearray session exists
			if(isset($_SESSION['tablearray'])) {
				
				if(isset($_SESSION['tablearray'][$axis_y][$axis_x1]) && isset($_SESSION['tablearray'][$axis_y][$axis_x2])) {
					
					if((int)$axis_x1 < (int)$axis_x2) {
					
						for($x=$axis_x1; $x<=$axis_x2; $x++) {
							
							// Set X and Y1 to Y2 axis to tablearray and store to session
							$_SESSION['tablearray'][$axis_y][$x] = $fill_color;
							
						}
						return createTable($_SESSION['tablearray']);
						
					} else {
						
						return "err9";
						
					}
					
				} else {
					
					return "err7|{$axis_x1}|{$axis_x2}|{$axis_y}";
					
				}
				
			} else {
				
				return 'err4';
				
			}
			
		} else {
			
			return 'err1';
			
		}
		
		return false;
		
	}
	
	function fillPixelVertical($axis_x, $axis_y1, $axis_y2, $fill_color) {
		
		// Check if input axis set
		if(isset($axis_x) && isset($axis_y1) && isset($axis_y2)) {
			
			$arrTable = array();
			
			// Check if tablearray session exists
			if(isset($_SESSION['tablearray'])) {
				
				if(isset($_SESSION['tablearray'][$axis_y1][$axis_x]) && isset($_SESSION['tablearray'][$axis_y2][$axis_x])) {
					
					if((int)$axis_y1 < (int)$axis_y2) {
						
						for($y=$axis_y1; $y<=$axis_y2; $y++) {
							
							// Set X and Y1 to Y2 axis to tablearray and store to session
							$_SESSION['tablearray'][$y][$axis_x] = $fill_color;
							
						}
						return createTable($_SESSION['tablearray']);
						
					} else {
						
						return "err8";
						
					}
				} else {
					
					return "err6|{$axis_x}|{$axis_y1}|{$axis_y2}";
					
				}
				
			} else {
				
				return 'err4';
				
			}
			
		} else {
			
			return 'err1';
			
		}
		
		return false;
		
	}

	function fillPixel($axis_x, $axis_y, $fill_color) {
		
		// Check if input axis set
		if(isset($axis_x) && isset($axis_y)) {
			
			$arrTable = array();
			
			// Check if tablearray session exists
			if(isset($_SESSION['tablearray'])) {
				
				if(isset($_SESSION['tablearray'][$axis_y][$axis_x])) {
					
					// Set X and Y axis to tablearray and store to session
					$_SESSION['tablearray'][$axis_y][$axis_x] = $fill_color;
					
					return createTable($_SESSION['tablearray']);
					
				} else {
					
					return "err5|{$axis_x}|{$axis_y}";
					
				}
				
			} else {
				
				return 'err4';
				
			}
			
		} else {
			
			return 'err1';
			
		}
		
		return false;
		
	}

	function clearTable($start_x, $start_y, $fill_color) {
		
		//Check if X and Y were set
		if(isset($start_y) && isset($start_x)) {
			
			$arrTable = array_fill(1, $start_y, array_fill(1, $start_x, $fill_color));

			// Store table array to session
			$_SESSION['tablearray'] = $arrTable;

			$createTable = createTable($arrTable);

			return $createTable;
			
		}
		
		return false;
		
	}

	function setTable($start_x, $start_y, $fill_color) {
		
		// Check if all input was set
		if(isset($start_y) && isset($start_x)) {
			
			//Check if X and Y are integers
			if(is_integer((int)$start_x) && is_integer((int)$start_y)) {
				
				// Check value and limit condition
				if($start_y <= 250 && $start_x >= 1) {
					
					// Store X and Y to session
					$_SESSION['tableset_y'] = $start_y;
					$_SESSION['tableset_x'] = $start_x;
					
					$arrTable = array_fill(1, $start_y, array_fill(1, $start_x, $fill_color));
					
					// Store table array to session
					$_SESSION['tablearray'] = $arrTable;
					
					return createTable($arrTable);
					
				} else {
					
					return 'err3';
					
				}
				
			} else {
				
				return 'err2';
				
			}
			
		} else {
			
			return 'err1';
			
		}
	
		return false;
		
	}
	
	function createTable($arrTable) {
			
		$strTable = '';
				
		if(!empty($arrTable)) {
			
			foreach($arrTable as $arr_table_y) {
				
				foreach($arr_table_y as $arr_table_x) {
					
					$strTable .= $arr_table_x;
					
				}
				
				$strTable .= '<br>';
				
			}
			
			// Store table string to session
			$_SESSION['tableset'] = $strTable;
			
		}
		
		return $strTable;
			
	}
	
	function errorHandler($err) {
		
		$arr_error = explode("|", $err);
		$err = $arr_error[0];
		$err_msg = '';
		
		switch($err) {
			case 'err1':
				$err_msg = "Please enter coordinates!";
				break;
			case 'err2':
				$err_msg = "Coordinates should be integers!";
				break;
			case 'err3':
				$err_msg = "Please enter a valid coordinates: <br>X is less than or equal to 250 AND Y is greater than or equal to 1";
				break;
			case 'err4':
				$err_msg = "Please initiate table pixels!<br>Command: I M N";
				break;
			case 'err5':	
				$err_msg = "Coordinates {$arr_error[1]},{$arr_error[2]} does not exist!";
				break;
			case 'err6':
				$start_x = isset($_SESSION['tableset_x']) ? $_SESSION['tableset_x'] : NULL;
				$start_y = isset($_SESSION['tableset_y']) ? $_SESSION['tableset_y'] : NULL;
				$err_msg = "Coordinates {$arr_error[1]},{$arr_error[2]} or {$arr_error[1]},{$arr_error[3]} does not exist!<br>Existing coordinates: {$start_x},{$start_y}";
				break;
			case 'err7':
				$start_x = isset($_SESSION['tableset_x']) ? $_SESSION['tableset_x'] : NULL;
				$start_y = isset($_SESSION['tableset_y']) ? $_SESSION['tableset_y'] : NULL;
				$err_msg = "Coordinates {$arr_error[1]},{$arr_error[3]} or {$arr_error[2]},{$arr_error[3]} does not exist!<br>Existing coordinates: {$start_x},{$start_y}";
				break;
			case 'err8':	
				$err_msg = "Coordinate Y1 should be less than to Y2";
				break;
			case 'err9':	
				$err_msg = "Coordinate X1 should be less than to X2";
				break;								
								
		}
		
		return $err_msg;
		
	}
?>