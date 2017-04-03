      
        <footer class="fluid-section">
    			<p class=""><a href="//www.sighted.com" target="_blank"><img src="/integritylogo.png" alt="Sighted Invoice and Expense" style='width:16px;height:18px;'> <span class="appversion">Sighted v.2.0</span></a>   
        		<a class="helpfooter" href="/help"><i class="fa fa-life-ring"></i> Get Help</a> </p>
         
        </footer>
       
		</div><!-- END page-container -->
		
		<!-- Load JS here for greater good =============================--> 
	     
        <script src="/assets/js/jquery-1.8.3.min.js"></script>  
        <script src="/assets/js/select2.js"></script>     
        <script src="/assets/js/main.js"></script>     
        <input type="hidden" class="app_theme"value="{{ Session::get('theme_id') }}">
        
        
        @yield('footer')
        <script src="/assets/js/jquery.popupoverlay.js"></script>
        <script>
            $(window).load(function(){

                $('#mobile').css({'display':'block'});

                if($("#mmenu").length > 0){
                    $("#mmenu").hide();
                    $(".mtoggle").click(function() {
                        $("#mmenu").slideToggle(300);
                    });

                    $('#mmenu .sub-menu').hide(); //Hide children by default

                    $('#mmenu').children().click(function(){

                         $('#mmenu li').removeClass('navActive');
                         $('#mmenu li').addClass('navInActive');

                         $(this).addClass('navActive');
                         $(this).removeClass('navInActive');

                        //now find the `.child` elements that are direct children of the clicked `<li>` and toggle it into or out-of-view
                        $(this).children('.sub-menu').slideToggle(300);
                        $('.navInActive ul').slideUp('normal');
                    });

                    $(document).click(function(){
                        $('.sub-menu').slideUp('normal');
                    });

                    $("#mmenu").click(function(e) {
                        e.stopPropagation(); // This is the preferred method.
                        //return false;   // This should not be used unless you do not want
                        // any click events registering inside the div
                    });

                    var smallWindow = false;
                    $(window).on('resize', function () {
                        var windowsize = $(window).width();
                        if (windowsize > 800) {
                            $("#mmenu").slideUp('normal');
                        }
                    });

                }
        		
        		$('#go_invoice_form').click(function(){
        			 // Any Prep work
        		});
        	 
			 	$('input[name=business_model]').on('change', function() {
					
				   if($(this).val() == 1){
				   		
				   		$('#bill_option').fadeIn();
				   		
				   }else{
				   	
				   		$('#bill_option').fadeOut();
				   }
				 
				});

        	});
        </script>    
    </body>
</html>
