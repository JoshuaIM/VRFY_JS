function get_grph_unit(var_select, vrfy_id) {

	var unit = "";
	
	var v_vrfy = var_select + "_" + vrfy_id;

	switch (v_vrfy) {

		case "T3H_BIAS" :
		case "T1H_BIAS" :
		case "TMN_BIAS" :
		case "TMX_BIAS" : unit = "BIAS#\xB0C"; break;
		case "T3H_CNT1" : 
		case "T3H_CNT2" : 
		case "T3H_CNT3" : 
		case "T1H_CNT1" : 
		case "T1H_CNT2" : 
		case "T1H_CNT3" : 
		case "TMN_CNT1" : 
		case "TMN_CNT2" : 
		case "TMN_CNT3" : 
		case "TMX_CNT1" : 
		case "TMX_CNT2" : 
		case "TMX_CNT3" : 
		case "REH_CNT1" : 
		case "REH_CNT2" : 
		case "REH_CNT3" : unit = "Frequency#%"; break;
		case "T3H_RMSE" :
		case "T1H_RMSE" :
		case "TMN_RMSE" :
		case "TMX_RMSE" : unit = "RMSE#\xB0C"; break;
		case "T3H_MAEE" :
		case "T1H_MAEE" :
		case "TMN_MAEE" :
		case "TMX_MAEE" : unit = "MAE#\xB0C"; break;
		case "REH_BIAS" : unit = "BIAS#%"; break;
		case "REH_RMSE" : unit = "RMSE#%"; break;
		case "REH_MAEE" : unit = "MAE#%"; break;
		case "POP_BRS0" :
		case "POP_BRS1" :
		case "POP_BRSS" : unit = "Brier Score#"; break;
		case "PTY_ACCC" : unit = "ACC#%"; break;
		case "PTY_BIAS" :
		case "RN3_BIS1" :
		case "RN3_BIS2" :
		case "RN3_BIS3" :
		case "RN3_BIS4" :
		case "RN1_BIS1" :
		case "RN1_BIS2" :
		case "RN1_BIS3" :
		case "RN1_BIS4" :
		case "RN6_BIS1" :
		case "RN6_BIS2" :
		case "RN6_BIS3" :
		case "RN6_BIS4" :
		case "R12_BIS1" :
		case "R12_BIS2" :
		case "R12_BIS3" :
		case "R12_BIS4" :
		case "SN3_BIS1" :
		case "SN3_BIS2" :
		case "SN3_BIS3" :
		case "SN3_BIS4" :
		case "SN1_BIS1" :
		case "SN1_BIS2" :
		case "SN1_BIS3" :
		case "SN1_BIS4" :
		case "SN6_BIS1" :
		case "SN6_BIS2" :
		case "SN6_BIS3" :
		case "SN6_BIS4" :
		case "S12_BIS1" :
		case "S12_BIS2" :
		case "S12_BIS3" :
		case "S12_BIS4" : unit = "BIAS#"; break;
		case "PTY_CSII" :
		case "RN3_CSI1" :
		case "RN3_CSI2" :
		case "RN3_CSI3" :
		case "RN3_CSI4" :
		case "RN1_CSI1" :
		case "RN1_CSI2" :
		case "RN1_CSI3" :
		case "RN1_CSI4" :
		case "RN6_CSI1" :
		case "RN6_CSI2" :
		case "RN6_CSI3" :
		case "RN6_CSI4" :
		case "R12_CSI1" :
		case "R12_CSI2" :
		case "R12_CSI3" :
		case "R12_CSI4" :
		case "SN3_CSI1" :
		case "SN3_CSI2" :
		case "SN3_CSI3" :
		case "SN3_CSI4" :
		case "SN1_CSI1" :
		case "SN1_CSI2" :
		case "SN1_CSI3" :
		case "SN1_CSI4" :
		case "SN6_CSI1" :
		case "SN6_CSI2" :
		case "SN6_CSI3" :
		case "SN6_CSI4" :
		case "S12_CSI1" :
		case "S12_CSI2" :
		case "S12_CSI3" :
		case "S12_CSI4" : unit = "CSI#%"; break;
		case "PTY_FARR" : unit = "FAR#%"; break;
		case "PTY_HSSS" :
		case "SKY_HSSS" :
		case "VEC_HSSS" : unit = "HSS#"; break;
		case "PTY_PODD" : unit = "POD#%"; break;
		case "RN3_ETS1" :
		case "RN3_ETS2" :
		case "RN3_ETS3" :
		case "RN3_ETS4" :
		case "RN1_ETS1" :
		case "RN1_ETS2" :
		case "RN1_ETS3" :
		case "RN1_ETS4" :
		case "RN6_ETS1" :
		case "RN6_ETS2" :
		case "RN6_ETS3" :
		case "RN6_ETS4" :
		case "R12_ETS1" :
		case "R12_ETS2" :
		case "R12_ETS3" :
		case "R12_ETS4" :
		case "SN3_ETS1" :
		case "SN3_ETS2" :
		case "SN3_ETS3" :
		case "SN3_ETS4" :
		case "SN1_ETS1" :
		case "SN1_ETS2" :
		case "SN1_ETS3" :
		case "SN1_ETS4" :
		case "SN6_ETS1" :
		case "SN6_ETS2" :
		case "SN6_ETS3" :
		case "SN6_ETS4" :
		case "S12_ETS1" :
		case "S12_ETS2" :
		case "S12_ETS3" :
		case "S12_ETS4" : unit = "ETS#%"; break;
		case "SKY_PCC0" :
		case "SKY_PCC1" :
		case "VEC_PCC0" :
		case "VEC_PCC1" : unit = "PC#%"; break;
		case "WSD_BIAS" : unit = "BIAS#m/s"; break;
		case "WSD_MAEE" : unit = "MAE#m/s"; break;
		case "WSD_RMSE" : unit = "RMSE#m/s"; break;
	}
	
	return unit;
}
