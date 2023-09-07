<?php 
	namespace App\Controllers;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	//use App\Controllers\Admin\SomeClass as ...
	use App\Controllers\Datos as MisDatos;

	class ConciliacionController extends BaseController{

		public function Index(){
			$return = new MisDatos();
			$return->setTitle('Titulo...');
			$title = $return->getTitle();
			print_r($title);
		}

		protected $helpers = ['form'];

		public function Conciliacion(){
			$data = array("foo"=>"bar");
			return view('conciliacion',array("data"=>$data));
		}


		public function xlsx($type = '.xlsx', $name = 'Banco'){
			echo 'File is a type ' .$type .' and his name is '.$name;
		}

		public function SendData(/*$args*/){//Argumentos

			if($this->request->is('post')){              	      
	    		//$validation =  \Config\Services::validation();
	    		//$validation->setRule('file1' => 'uploaded[file1]|ext_in[file1,xlsx]');
				//!$validation->withRequest($this->request)->run()

		        $rules = [
		        	'file1' => 'uploaded[file1]|ext_in[file1,xlsx]',
		        	'file2' => 'uploaded[file2]|ext_in[file2,xlsx]'
		     	];

		        if($this->validate($rules)){
		            
		      		$ut = $_FILES['file1']['tmp_name'];
					$ba = $_FILES['file2']['tmp_name'];

					$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
					$reader->setReadDataOnly(true);
		            ###
					$data1 = $this->GetDatosUt(array("file"=>$ut,"reader"=>$reader));
					$data2 = $this->GetDatosBa(array("file"=>$ba,"reader"=>$reader));
					//Recorrer el total de procesos a mostrar(4)
					for($i=1;$i<=4;$i++){
			            $this->FetchData(
											array(
													"data1"	=>	$data1,
													"data2"	=>	$data2,
													"opciones" => $i)
												);
					}
				}else{
					$data=[];
					$data['validation'] = $this->validator;
		            return view('/conciliacion',$data);
				}
			}else{
				return view('/conciliacion');
			}

		}

		public function GetDatosUt($array){
			$spreadsheet = $array['reader']->load($array['file']);
            $sheet = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);
            $count = count($sheet);
            $column_1 = array();
            $column_2 = array();
            $column_3 = array();
            $column_4 = array();
            $column_5 = array();
            for($x=1; $x<=$count; $x++){
            	//Columnas archivo universidad.
            	$column_1[$x] = $sheet[$x]['D'];            	
            	$column_2[$x] = $sheet[$x]['B'];            	
            	$column_3[$x] = $sheet[$x]['C'];            	
            	$column_4[$x] = $sheet[$x]['A'];            	
            	$column_5[$x] = $sheet[$x]['E'];            	
            }
            return array("column_1"=>$column_1,
            				"column_2"=>$column_2,
            				"column_3"=>$column_3,
            				"column_4"=>$column_4,
							"column_5"=>$column_5);
		}

		public function GetDatosBa($array){
			$spreadsheet = $array['reader']->load($array['file']);
            $sheet = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);			
			$count = count($sheet);
            $column_1 = array();
            $column_2 = array();
            $column_3 = array();
            $column_4 = array();
            $column_5 = array();
            for($x=1; $x<=$count; $x++){
            	//Columnas archivo banco.
            	$column_1[$x] = $sheet[$x]['G'];            	
            	$column_2[$x] = $sheet[$x]['C'];            	
            	$column_3[$x] = $sheet[$x]['D'];            	
            	$column_4[$x] = $sheet[$x]['A']; 
            	$column_5[$x] = $sheet[$x]['H']; 

            }
            return array("column_1"=>$column_1,
            				"column_2"=>$column_2,
            				"column_3"=>$column_3,
            				"column_4"=>$column_4,
							"column_5"=>$column_5);
		}

		public function FetchData($array){
			$x=1;			
			
			$cargo_ba1 = $array['data2']['column_1'];//Cargos del banco
			$cargo_ba2 = $array['data2']['column_2'];//Referencia
			$cargo_ba3 = $array['data2']['column_3'];//Descripción
			$cargo_ba4 = $array['data2']['column_4'];//Fecha
			$cargo_ba5 = $array['data2']['column_5'];//Abonos

			$cargo_ut1 = $array['data1']['column_1'];//Cargos de la universidad
			$cargo_ut2 = $array['data1']['column_2'];//Referencia
			$cargo_ut3 = $array['data1']['column_3'];//Descripción
			$cargo_ut4 = $array['data1']['column_4'];//Fecha
			$cargo_ut5 = $array['data1']['column_5'];//Abonos

			switch ($array['opciones']) {
				case 1://Cargos del banco no correspondidos por la entidad	
					$array1 = $cargo_ut1;

					$array2 = $cargo_ba1;
					$array3 = $cargo_ba2;
					$array4 = $cargo_ba3;
					$array5 = $cargo_ba4;			
					break;
				case 2://Cargos de la entidad no correspondidos por el banco
					$array1 = $cargo_ba1;

					$array2 = $cargo_ut1;
					$array3 = $cargo_ut2;
					$array4 = $cargo_ut3;
					$array5 = $cargo_ut4;						
					break;
				
				case 3://Abonos del banco no correspondidos por la entidad
					$array1 = $cargo_ut5;

					$array2 = $cargo_ba5;
					$array3 = $cargo_ba2;
					$array4 = $cargo_ba3;
					$array5 = $cargo_ba4;	
					break;

				case 4://Abonos de la entidad no correspondidos por el banco
					$array1 = $cargo_ba5;

					$array2 = $cargo_ut5;
					$array3 = $cargo_ut2;
					$array4 = $cargo_ut3;
					$array5 = $cargo_ut4;					
					break;
				
				default:
					# code...
					break;
			}

			foreach ($array1 as $key1 => $value1) {
				foreach($array2 as $key2 => $value2){
					if($array1[$key1]==$value2){
						unset($array2[$key2]);
						unset($array3[$key2]);
						unset($array4[$key2]);
						unset($array5[$key2]);
					}
				}		
			}
		
			$datos = array("data1"=>$array2,
				"data2"=>$array3,
				"data3"=>$array4,
				"data4"=>$array5);	
			print_r($datos);
		}


		//return redirect()->to(base_url('../'))->with('msg', 'You are not allowed to access that category.');
		//return view('drawlayout',array("datos"=>$datos));	
	}
?>