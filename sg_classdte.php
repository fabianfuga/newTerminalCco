<?php 
class Dte {

	public $apikey="apikey: 928e15a2d14d4a6292345f04960f4bd3"; //apikey de la empresa
	public $idempotency; 
	public $url="https://dev-api.haulmer.com/v2/dte/document"; // url desarrollo
	public $RUTEmisor="76795561-8";
	public $RznSocEmisor="HAULMER SPA";
	public $GiroEmisor='VENTA AL POR MENOR EN EMPRESAS DE VENTA A DISTANCIA VÍA INTERNET; COMERCIO ELEC';
	public $Acteco="479100";
	public $CdgSIISucur="81303347";
	public $DirOrigen="ARTURO PRAT 527   CURICO";
	public $CmnaOrigen="Curicó";


/*
	public $apikey="apikey: 244c8b8371b546ebbaf3fdad93ca8cd0"; //apikey de la empresa
	public $idempotency; 
	public $url="https://api.haulmer.com/v2/dte/document";
	public $RUTEmisor="96789100-2";
	public $RznSocEmisor="NACIONAL RODOVIARIO SPA";
	public $GiroEmisor='EXPLOTACION DE TERMINALES TERRESTRES DE PASAJEROS';
	public $Acteco="522110";
	public $CdgSIISucur="64609283";
	public $DirOrigen="A.PRAT 780 LOCAL 28-ROL 210/511, Curicó";
	public $CmnaOrigen="Curicó";
*/

	public $FchEmis;
	public $TermPagoGlosa;
	
	//receptor
	public $RUTRecep;
	public $RznSocRecep;
	public $GiroRecep;
	public $Contacto;
	public $DirRecep;
	public $CmnaRecep;
	//totales boleta 
	public $MntExe;
	public $MntTotal;
	public $TotalPeriodo;
	public $MontoNF;
	public $VlrPagar;

	//totales factura 
	public $factutaMntNeto;
	public $factutaIVA;
	public $factutaMntTotal;

	//detalle	
	public $data;
	public $IdDoc;
	public $Receptor;
	public $Transporte;
	public $Totales;
	public $detalle;
	public $Referencia;
	public $folio;	

	function bafecta(){
	
		$response=array("TIMBRE","FOLIO");
		$encabezado['IdDoc']=array(
			"TipoDTE"=>39,
			"Folio"=>"0",
			"FchEmis"=>$this->FchEmis,
			"IndServicio"=>"3");	
		$encabezado['Emisor']=array(
			"RUTEmisor"=>$this->RUTEmisor,		
			"RznSocEmisor"=>$this->RznSocEmisor,
			"GiroEmisor"=>utf8_encode($this->GiroEmisor),
			"CdgSIISucur"=>$this->CdgSIISucur,
			"DirOrigen"=>$this->DirOrigen,			
			"CmnaOrigen"=>utf8_encode($this->CmnaOrigen));

		$encabezado['Receptor']=$this->Receptor;
		$encabezado['Totales']=$this->Totales;
		$detalle=$this->Detalle;
		$dte=array("Encabezado"=>$encabezado,"Detalle"=>$detalle);
		$xml=array("response"=>$response,"dte"=>$dte);
		$this->data=json_encode($xml, JSON_UNESCAPED_UNICODE);		
	}

	function bexenta(){
	
		$response=array("TIMBRE","FOLIO");
		$encabezado['IdDoc']=array(
			"TipoDTE"=>41,
			"Folio"=>"0",
			"FchEmis"=>$this->FchEmis,
			"IndServicio"=>"3");			
		$encabezado['Emisor']=array(
			"RUTEmisor"=>$this->RUTEmisor,		
			"RznSocEmisor"=>$this->RznSocEmisor,
			"GiroEmisor"=>utf8_encode($this->GiroEmisor),
			"CdgSIISucur"=>$this->CdgSIISucur,
			"DirOrigen"=>$this->DirOrigen,			
			"CmnaOrigen"=>utf8_encode($this->CmnaOrigen));
		$encabezado['Receptor']=$this->Receptor;
		$encabezado['Totales']=$this->Totales;
		$detalle=$this->Detalle;
		$dte=array("Encabezado"=>$encabezado,"Detalle"=>$detalle);
		$xml=array("response"=>$response,"dte"=>$dte);
		$this->data=json_encode($xml, JSON_UNESCAPED_UNICODE);		
   }




   function curl(){
		$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => $this->url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS =>$this->data,
			CURLOPT_HTTPHEADER => array($this->apikey,$this->idempotency),
		));
		return $curl;	
	}
	
	function enviadte($codigo){	
		$curl=$this->curl();
		$response = curl_exec($curl);
		$mydata = json_decode($response,true);
		$err = curl_error($curl);
		curl_close($curl);		
		if(array_key_exists('error', $mydata)) {
			return $mydata;
		}		
		else{								
			$array=json_decode($response, true);
			$Base64Img = base64_decode($array['TIMBRE']);
			$nombretimbre='timbre_'.$codigo.'.png';			
			file_put_contents("img_timbres/".$nombretimbre, $Base64Img); 			
			$image = new Imagick("img_timbres/".$nombretimbre);
			$image->adaptiveResizeImage(570,273);
			//$image->cropThumbnailImage(570,273);
			$image->writeImage("img_timbres/".$nombretimbre);
			return array("folio"=>$array['FOLIO'],"timbre"=>$nombretimbre);
		}
	}	
}
?>