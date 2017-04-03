      
      <footer class="fluid-section">
        	<div class="appLeft">
        		<ul>
        			<li><a class="fa fa-flag darkbtn" href="#"></a></li>  
        			<li><a class="darkbtn" href="#">About</a></li> 
        			<li><a class="darkbtn" href="#">API</a></li>      			
        		</ul>
        	</div><!-- END appleft -->
        	
        	<div class="appRight">
        		 <p><a href="http://www.integrityinvoice.com" target="_blank"><img src="{{ URL::asset('integrityinvoice_logo_symbol_small.png') }}" alt="." ></a> v.2.0</p>
        	</div><!-- END appleft -->
        </footer>
		
		</div><!-- END page-container -->
		
		<!-- Load JS here for greater good =============================--> 
              
        <script src="{{ URL::asset('assets/js/jquery-1.8.3.min.js') }}"></script>         
        <script src="{{ URL::asset('assets/js/jquery.multilevelpushmenu.js') }}"></script> 
        <script src="{{ URL::asset('assets/js/block.js') }}"></script>  
        
        <?php if(isset($scripts)): ?>
 		<?php $script_array = explode(',', $scripts);
		  foreach($script_array as $key => $value): ?>
    	 <script src="{{ URL::asset('assets/js/'.$value.'.js') }}"></script>  
    	<?php  endforeach; endif; ?>
            
      
        <script src="{{ URL::asset('assets/js/admin.js') }}"></script>     
        
        @yield('footer')
 
        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <!--
        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
        
        -->
    </body>
</html>
