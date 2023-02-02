function get_variable_unit(v) {
	
	var unit = "";
	
	switch (v) {

		case "T3H" : 
		case "T1H" : 
		case "TMX" : 
		//case "TMN" : unit = "\xB0C"; break; 
		case "TMN" : unit = "Temperature#\xB0C"; break; 
		case "REH" : 
		case "POP" : unit = "Percentage#%"; break; 
		case "VEC" : unit = "Degree#\xB0"; break; 
		case "WSD" : unit = "Meters per second#m/s"; break; 
		case "SKY" : unit = "Value#"; break; 
		case "PTY" : 
		case "RN3" : 
		case "RN1" : 
		case "RN6" : unit = "Millimeter#mm"; break; 
		case "R12" : unit = "Millimeter#mm"; break; 
		case "SN3" : 
		case "SN1" : 
		case "SN6" : unit = "Centimeter#cm"; break; 
		case "S12" : unit = "Centimeter#cm"; break; 
	
	}
	
	
	return unit;
}
