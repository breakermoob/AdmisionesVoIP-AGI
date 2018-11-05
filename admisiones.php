#!/usr/bin/php -q
<?php
set_time_limit(0);
$param_error_log = '/tmp/notas.log';
$param_debug_on = 1;

require('phpagi.php');
require("definiciones.inc");
$link = mysql_connect(MAQUINA, USUARIO,CLAVE);
mysql_select_db(DB, $link);

$agi = new AGI();
$agi->answer();

$agi->exec("AGI","googletts.agi,\"bienvenido al sistema de busqueda para credenciales de la Univesidad de Antioquia\",es");
$agi->exec("AGI","googletts.agi,\"ingrese su cedula para continuar\",es");
$cedula = $agi->get_data("beep",5000,1);
$valor = $cedula['result'];
$result = mysql_query("SELECT *  FROM usuario WHERE cedula = ".$valor, $link);
$row = mysql_fetch_array($result);
$opt = "0";

if($row['cedula']==$valor){
	$agi->exec("AGI","googletts.agi,\"bienvenido ". $row['nombre'] ." al sistema de audio respuesta de admisiones de la universidad de antioquia\",es");	
}
else{
	$agi->exec("AGI","googletts.agi,\"el numero de cedula ". $celuda["result"] ." es incorrecto o no esta registrado en nuestro sistema\",es");
	sleep(0,3);
	$agi->exec("AGI","googletts.agi,\"intentelo mas tarde\",es");	      	      
	$opt = null;
}

if($opt != null){
	do{
		$opt = null;
		$agi->exec("AGI","googletts.agi,\"marque uno para saber si fue admitido\",es");	
		sleep(1);
		$agi->exec("AGI","googletts.agi,\"marque dos para conocer su credencial\",es");
		sleep(1);
		$agi->exec("AGI","googletts.agi,\"marque tres para saber unicamente la fecha y lugar de su examen\",es");
		sleep(1);
		$agi->exec("AGI","googletts.agi,\"Marque cero para finalizar la llamada\",es");
		$numero = $agi->get_data("beep",5000,1);
		$opt = $numero["result"];
		switch($opt){
			case "1":
				$agi->exec("AGI","googletts.agi,\"Su estado en nuestro sistema es \",es");
				$result = mysql_query("SELECT * FROM credencial WHERE id = ".$cedula['result'], $link);
				while ($row = mysql_fetch_array($result)){
					if($row['estado']=="aprovado"){
						$agi->exec("AGI","googletts.agi,\"Felicidades usted fue ". $row['estado'] ."\",es"); 
					}else{
						if($row['estado']=="rechazado"){
							$agi->exec("AGI","googletts.agi,\"lo sentimos usted fue ". $row['estado'] ."\",es"); 
						}else{
							$agi->exec("AGI","googletts.agi,\"Su examen esta ". $row['estado'] ."\",es"); 
						}	
					}
					sleep(1);
				}
				break;

			case "2":
				$result = mysql_query("SELECT * FROM credencial WHERE id = ".$cedula['result'], $link);
				while ($row = mysql_fetch_array($result)){
					$agi->exec("AGI","googletts.agi,\"los datos de su examen son lugar sede de ". $row['sede'] ."\",es");
					$agi->exec("AGI","googletts.agi,\"fecha  ". $row['fecha'] ."\",es");
					$agi->exec("AGI","googletts.agi,\"hora  ". $row['hora'] ."\",es");
					$agi->exec("AGI","googletts.agi,\"bloque  ". $row['bloque'] ."\",es");
					$agi->exec("AGI","googletts.agi,\"aula  ". $row['aula'] ."\",es");
					sleep(1);
				}
				break;

			case "3":
				$result = mysql_query("SELECT * FROM credencial WHERE id = ".$cedula['result'], $link);
				while ($row = mysql_fetch_array($result)){
					$agi->exec("AGI","googletts.agi,\"Sue examen sera en la sede ". $row['sede'] ."\",es");
					$agi->exec("AGI","googletts.agi,\"el dia ". $row['fecha'] ."\",es");
					sleep(1);
				}
				break;
			
			case "4":
				$agi->exec("AGI","googletts.agi,\"estos son los cursos que usted actualmente esta cursando \",es");	
				$result = mysql_query("SELECT * FROM curso WHERE state = 'C'", $link);
				while ($row = mysql_fetch_array($result)){
					$agi->exec("AGI","googletts.agi,\"". $row['name'] ."\",es");
					$agi->exec("AGI","googletts.agi,\"dictado en ". $row['location'] ."\",es");                
					sleep(1);
				}
				break;
			
			case "0":
				$agi->exec("AGI","googletts.agi,\"gracias por utilizar el sistema de audio respuesta de los cursos de la universidad de antioquia\",es");
				$opt = null;

				break;
		}
	}while($opt != null);
}
$agi->exec("AGI","googletts.agi,\"fin de la llamada\",es");
?>
