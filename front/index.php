<html>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
    <head>
    	<script type="text/javascript" src="js/jquery-2.2.4.min.js"></script>
    	<script type="text/javascript" src="js/jquery.mask.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
         <script type="text/javascript" src="js/agenda.js"></script>
        
        <link rel="stylesheet" href="css/bootstrap.css"> 
        <link rel="stylesheet" href="css/general.css">
        <link rel="stylesheet" href="css/agenda.css"> 
        <link rel="stylesheet" href="css/materialize.min.css">
    <body>
	<?php include "modal.html" ?>
	    <section>
	        <?php include "header.html"; ?>
	        	        
	        <div class="add-contact">
	        	<?php include "form.html"; ?>
	        </div>
	        	        
	        <div class="table-contacts">
	        	 <?php include "table-contacts.html" ?>
	        </div>


		</section>

     	 <?php include "footer.html"; ?>
	</body>
</html>