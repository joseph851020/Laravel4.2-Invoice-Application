<select name="client_number" id="client_number" class="client_option client_new">
	<option value="" selected="selected">Select Client</option>
	<option value="newclient">New Client</option>
	<?php foreach($clients as $client): ?>
	    <option <?php echo $client->id == $newest_client->id ? 'selected="selected"' : ""; ?> cl_id="<?php echo $client->id; ?>" cl_add1="<?php echo $client->add_1; ?>" cl_add2="<?php echo $client->add_2; ?>" cl_state="<?php echo $client->state; ?>" cl_pcode="<?php echo $client->postal; ?>" value="<?php echo $client->company; ?>"><?php echo $client->company; ?></option>
	<?php endforeach; ?>
</select>