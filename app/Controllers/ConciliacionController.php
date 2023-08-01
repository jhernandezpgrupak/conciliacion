<?php 
	namespace App\Controllers;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

	class ConciliacionController extends BaseController{

		public function Conciliacion(){
			$data = array("foo"=>"bar");
			return view('conciliacion',array("data"=>$data));
		}

		public function SendData(){			
			$ut = $_FILES['file1']['tmp_name'];
			$ba = $_FILES['file2']['tmp_name'];

			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
			$reader->setReadDataOnly(true);
            ###
			//Archivo de la universidad
			$data1 = $this->GetDatosUt(array("file"=>$ut,"reader"=>$reader));
			$data2 = $this->GetDatosBa(array("file"=>$ba,"reader"=>$reader));
			//Recorrer el total de procesos a mostrar(4)
			//for($i=1;$<=4;$i++){
	            //$opciones[] = $i;
	            $this->SelectData(
				            		array(
				            				"data1"=>$data1,
				            				"data2"=>$data2));
			//}
		}

		public function GetDatosUt($array){
			$spreadsheet = $array['reader']->load($array['file']);
            $sheet = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);
            $count = count($sheet);
            $column_1 = array();
            $column_2 = array();
            $column_3 = array();
            $column_4 = array();
            for($x=1; $x<=$count; $x++){
            	//Columnas del archivo de la universidad.
            	$column_1[$x] = $sheet[$x]['D'];            	
            	$column_2[$x] = $sheet[$x]['A'];            	
            	$column_3[$x] = $sheet[$x]['B'];            	
            	$column_4[$x] = $sheet[$x]['C'];            	
            }
            return array("column_1"=>$column_1,
            				"column_2"=>$column_2,
            				"column_3"=>$column_3,
            				"column_4"=>$column_4);
		}

		public function GetDatosBa($array){
			$spreadsheet = $array['reader']->load($array['file']);
            $sheet = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);
            $count = count($sheet);
            $column_1 = array();
            $column_2 = array();
            $column_3 = array();
            $column_4 = array();
            for($x=0; $x<=$count; $x++){
            	//Columnas del archivo del banco.
            	$column_1[$x] = $sheet[$x]['G'];            	
            	$column_2[$x] = $sheet[$x]['C'];            	
            	$column_3[$x] = $sheet[$x]['D'];            	
            	$column_4[$x] = $sheet[$x]['A'];            	
            }
            return array("column_1"=>$column_1,
            				"column_2"=>$column_2,
            				"column_3"=>$column_3,
            				"column_4"=>$column_4);
		}

		public function SelectData($array){
			//var_dump($array['data1']['column_1']);
			$this->FetchData($array);
		}

		public function FetchData($array){			
			//Recorriendo las columnas de ambos archivos
			
			for($b=1;$b<count($array['data2']['column_1']);$b++){
				$cargo_ba1 = intval($array['data2']['column_1'][$b]);
				$cargo_ba2 = $array['data2']['column_2'][$b];
				$cargo_ba3 = $array['data2']['column_3'][$b];
				$cargo_ba4 = $array['data2']['column_4'][$b];	
			}

			for($a=1;$a<count($array['data1']['column_1']);$a++){
				//Se guarda en una variable el valor de la celda a comparar
				$cargo_ut = intval($array['data1']['column_1'][$a]);
				

				for($c=1;$c<count($array['data2']['column_1']);$c++){
					if($cargo_ba1[$c]!=$cargo_ut){

					}else{
						unset($cargo_ba1[$c]);
					}
				}


			}
			print_r($cargo_ba1);
			echo "---------------------------";
			//print_r($arrayn);
		}		
	}
?>