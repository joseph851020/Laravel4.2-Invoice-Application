<?php if($preferences->business_model == 0) { $item_type = 'product'; $business_model = 0; }
     if($preferences->business_model == 1) { $item_type = 'service'; $business_model = 1; }
?>
<select name="item_selection" id="" class="item_sel" class="">
	<option value="">@if($preferences->business_model == 0) Select product @elseif($preferences->business_model == 1) Select service @endif</option>
	<option value="newitem">Add New</option>
	<?php 
	    foreach($items as $item): ?>                    
	    @if($item->item_type == $item_type)
	    <option <?php echo $item->id == $newest_item->id ? 'selected="selected"' : ""; ?> item_id="<?php echo $item->id; ?>" item_name="<?php echo $item->item_name; ?>" item_tax="<?php echo $item->tax_type; ?>" item_description="<?php echo $item->item_name; ?>" item_unit_price="<?php echo $item->unit_price; ?>"><?php echo $item->item_name; ?></option>
		@endif
	<?php endforeach; ?>
</select>