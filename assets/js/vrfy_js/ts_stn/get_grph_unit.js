function get_grph_unit(var_select, vrfy_id) {

	var unit = "";
	
	var v_vrfy = var_select + "_" + vrfy_id;
// console.log('v_vrfy', v_vrfy);

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
		
		// case "REH_BIAS" : unit = "BIAS#%"; break;
		case "REH_BIAS" : unit = "BIAS#"; break;
		
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
		case "RN6_BIS1" :
		case "RN6_BIS2" :
		case "RN6_BIS3" :
		case "RN6_BIS4" :
		case "R12_BIS1" :
		case "R12_BIS2" :
		case "R12_BIS3" :
		case "R12_BIS4" : unit = "BIAS#%"


		case "RN1_BIS1" :
		case "RN1_BIS2" :
		case "RN1_BIS3" :
		case "RN1_BIS4" :
		case "RN1_BIS5" :
		case "RN1_BIS6" :
		case "RN1_BIS7" :
		case "RN1_BIS8" :
		case "SN3_BIS1" :
		case "SN3_BIS2" :
		case "SN3_BIS3" :
		case "SN3_BIS4" :
		case "SN3_BIS5" :
		case "SN3_BIS6" :
		case "SN3_BIS7" :
		case "SN1_BIS1" :
		case "SN1_BIS2" :
		case "SN1_BIS3" :
		case "SN1_BIS4" :
		case "SN1_BIS5" :
		case "SN1_BIS6" :
		case "SN1_BIS7" : unit = "FBI#%"; break;

		// case "SN3_BIS1" :
		// case "SN3_BIS2" :
		// case "SN3_BIS3" :
		// case "SN3_BIS4" :
		case "SN6_BIS1" :
		case "SN6_BIS2" :
		case "SN6_BIS3" :
		case "SN6_BIS4" :
		case "S12_BIS1" :
		case "S12_BIS2" :
		case "S12_BIS3" :
		case "S12_BIS4" : unit = "BIAS#"; break;

		case "PTY_CSII" :
		case "RN1_CSI1" :
		case "RN1_CSI2" :
		case "RN1_CSI3" :
		case "RN1_CSI4" :
		case "RN1_CSI5" :
		case "RN1_CSI6" :
		case "RN1_CSI7" :
		case "RN1_CSI8" :
		case "SN3_CSI1" :
		case "SN3_CSI2" :
		case "SN3_CSI3" :
		case "SN3_CSI4" :
		case "SN3_CSI5" :
		case "SN3_CSI6" :
		case "SN3_CSI7" :
		case "RN3_CSI1" :
		case "RN3_CSI2" :
		case "RN3_CSI3" :
		case "RN3_CSI4" :
		case "RN6_CSI1" :
		case "RN6_CSI2" :
		case "RN6_CSI3" :
		case "RN6_CSI4" :
		case "R12_CSI1" :
		case "R12_CSI2" :
		case "R12_CSI3" :
		case "R12_CSI4" :
		case "SN1_CSI1" :
		case "SN1_CSI2" :
		case "SN1_CSI3" :
		case "SN1_CSI4" :
		case "SN1_CSI5" :
		case "SN1_CSI6" :
		case "SN1_CSI7" :
		// case "SN3_CSI1" :
		// case "SN3_CSI2" :
		// case "SN3_CSI3" :
		// case "SN3_CSI4" :
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

		case "RN1_ETS1" :
		case "RN1_ETS2" :
		case "RN1_ETS3" :
		case "RN1_ETS4" :
		case "RN1_ETS5" :
		case "RN1_ETS6" :
		case "RN1_ETS7" :
		case "RN1_ETS8" :
		case "SN3_ETS1" :
		case "SN3_ETS2" :
		case "SN3_ETS3" :
		case "SN3_ETS4" :
		case "SN3_ETS5" :
		case "SN3_ETS6" :
		case "SN3_ETS7" :
		case "RN3_ETS1" :
		case "RN3_ETS2" :
		case "RN3_ETS3" :
		case "RN3_ETS4" :
		case "RN6_ETS1" :
		case "RN6_ETS2" :
		case "RN6_ETS3" :
		case "RN6_ETS4" :
		case "R12_ETS1" :
		case "R12_ETS2" :
		case "R12_ETS3" :
		case "R12_ETS4" :
		// case "SN3_ETS1" :
		// case "SN3_ETS2" :
		// case "SN3_ETS3" :
		// case "SN3_ETS4" :
		case "SN1_ETS1" :
		case "SN1_ETS2" :
		case "SN1_ETS3" :
		case "SN1_ETS4" :
		case "SN1_ETS5" :
		case "SN1_ETS6" :
		case "SN1_ETS7" :
		case "SN6_ETS1" :
		case "SN6_ETS2" :
		case "SN6_ETS3" :
		case "SN6_ETS4" :
		case "S12_ETS1" :
		case "S12_ETS2" :
		case "S12_ETS3" :
		case "S12_ETS4" : unit = "ETS#%"; break;

		case "RN1_ACC1" :
		case "RN1_ACC2" :
		case "RN1_ACC3" :
		case "RN1_ACC4" :
		case "RN1_ACC5" :
		case "RN1_ACC6" :
		case "RN1_ACC7" :
		case "RN1_ACC8" :
		case "SN3_ACC1" :
		case "SN3_ACC2" :
		case "SN3_ACC3" :
		case "SN3_ACC4" :
		case "SN3_ACC5" :
		case "SN3_ACC6" :
		case "SN3_ACC7" : unit = "ACC#%"; break;

		case "RN1_FAR1" :
		case "RN1_FAR2" :
		case "RN1_FAR3" :
		case "RN1_FAR4" :
		case "RN1_FAR5" :
		case "RN1_FAR6" :
		case "RN1_FAR7" :
		case "RN1_FAR8" :
		case "SN3_FAR1" :
		case "SN3_FAR2" :
		case "SN3_FAR3" :
		case "SN3_FAR4" :
		case "SN3_FAR5" :
		case "SN3_FAR6" :
		case "SN3_FAR7" : unit = "FAR#%"; break;

		case "RN1_HSS1" :
		case "RN1_HSS2" :
		case "RN1_HSS3" :
		case "RN1_HSS4" :
		case "RN1_HSS5" :
		case "RN1_HSS6" :
		case "RN1_HSS7" :
		case "RN1_HSS8" :
		case "SN3_HSS1" :
		case "SN3_HSS2" :
		case "SN3_HSS3" :
		case "SN3_HSS4" :
		case "SN3_HSS5" :
		case "SN3_HSS6" :
		case "SN3_HSS7" : unit = "HSS#%"; break;

		case "PTY_PODD" :
		case "RN1_POD1" :
		case "RN1_POD2" :
		case "RN1_POD3" :
		case "RN1_POD4" :
		case "RN1_POD5" :
		case "RN1_POD6" :
		case "RN1_POD7" :
		case "RN1_POD8" :
		case "SN3_POD1" :
		case "SN3_POD2" :
		case "SN3_POD3" :
		case "SN3_POD4" :
		case "SN3_POD5" :
		case "SN3_POD6" :
		case "SN3_POD7" : unit = "POD#%"; break;
		
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
