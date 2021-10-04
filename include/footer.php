<?php
function getVisIpAddr() {

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

?>


</div>
<!-- response Message -->
<div class="modal response-modal" id="success-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1>Success</h1>
            </div>
            <div class="modal-body">
              <p></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-modal btn-proceed btn-close pull-right" data-dismiss="modal">OK</button>
              </div>
        </div>
    </div>
</div>

<div class="modal response-modal" id="error-modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1>Error!</h1>
                        </div>
                        <div class="modal-body">
                            <p></p>

                            <ul>

                            </ul>

                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-modal try_again pull-right" data-dismiss="modal">Try Again</button>
                          </div>
                    </div>
                </div>
            </div>

             <div id="loader-footer"></div>
             <div class="container coming animate-bottom " id="my-cont">
                <div class="row">
                    <div class="col-xs-12">

                        <!-- <h1>COMING SOON...</h1> -->
                    </div>
                </div>
             </div>


	<!-- jQuery -->
	<script type="text/javascript" src="layout/js/jquery.min.js"></script>
	<script type="text/javascript" src="layout/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="layout/js/owl.carousel.min.js"></script>
	<script type="text/javascript" src="layout/js/jquery.magnific-popup.js"></script>

	<script type="text/javascript" src="layout/js/main.js"></script>

    <script type="text/javascript" src="layout/js/custom-file-input.js"></script>

<script type="text/javascript">
    $(document).ready(function(){

   //Links color
   var page = $("div.link_color").data("target");
   $("#nav li ."+page).css({
       "borderBottom":"3px solid #fff",
       "color":"#fff"
   });

              //$(".actions-list").insertAfter(siblings(".actions"));
             $(document).on("click",".actions",function(){
                 $(".actions-list").css("z-index","2");
                 $(this).siblings(".actions-list").toggleClass("hidden").css("z-index","3");

             });
           ///////////////Show list//////////////////
         $(document).on("click",".actions-list ul li", function (){
             var actions = $(this).data("class");
             $(this).parent("ul").parent(".actions-list").siblings("input.actions").val(actions);
             $(".actions-list").addClass("hidden");
         });



         //responses popup
         $(document).on("click", ".response-modal .btn-modal", function(){
             $(this).parent(".modal-footer").parent(".modal-content").parent(".modal-dialog").parent(".response-modal").addClass("hidden");
         });
             $(document).on("click",".button_delete",function(){
                 $("#success-modal .btn-proced").addClass("hidden");
             });


  });

</script>



</body>

</html>
