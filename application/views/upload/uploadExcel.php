<?PHP
    $user_detail = $this->session->userdata('user_data_session');
    $username = $user_detail['name'];
    $userid = $user_detail['userid'];
?>


      <section class="wrapper">
        <div class="containter">
           <div class="col-lg-9">
            <h3>Upload Excel</h3>
           </div>

           <div class="col-lg-3">
              <br>
              <label>Quater :</label>
              <select id="quater" onchange="changeDate()">
              </select>
              <label>&nbsp; &nbsp; Year :</label>
              <select id="syear" onchange="changeDate()">
              </select>
              
            </div>

        </div>   
          <!-- /col-lg-3 -->

          <div class="table table-bordered table-striped table-condensed cf" id="expGrid" >

          </div>
        <!-- /row -->
      </section>
    
  <script type="text/javascript">

 	(function() {    
 	 	
 	}());

 	
   //function doStuff(){...}

 	    
  </script>


