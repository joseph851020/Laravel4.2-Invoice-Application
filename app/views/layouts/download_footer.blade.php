		</div>  <!-- End panel -->   
		  
		</div> <!-- End pagebody -->
 		
		<script src="https://code.jquery.com/jquery-1.8.3.min.js"></script>
		
		@yield('footer')
		
		<script>
		
		$('.description').on( 'keyup', 'textarea', function (){
		    $(this).height( 0 );
		    $(this).height( this.scrollHeight );
		});
		$('.description').find( 'textarea' ).keyup();

		</script>
		
		</div><!-- END page-container -->
		
		<!-- Load JS here for greater good =============================--> 
      
    </body>
</html>
