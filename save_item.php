<?php

/*
  Module developed for the Open Source Content Management System WebsiteBaker (http://websitebaker.org)
  Copyright (C) 2007 - 2013, Christoph Marti

  LICENCE TERMS:
  This module is free software. You can redistribute it and/or modify it 
  under the terms of the GNU General Public License  - version 2 or later, 
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  DISCLAIMER:
  This module is distributed in the hope that it will be useful, 
  but WITHOUT ANY WARRANTY; without even the implied warranty of 
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
  GNU General Public License for more details.
*/


// Get item id
if (!isset($_POST['item_id']) OR !is_numeric($_POST['item_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$id      = $_POST['item_id'];
	$item_id = $id;
}

// Includes
require('../../config.php');
require('resize_img.php');
// Get some default values
require_once('config.php');
// Include WB functions file
require(WB_PATH.'/framework/functions.php');
// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// Look for language file
if (LANGUAGE_LOADED) {
    include(WB_PATH.'/modules/bakery/languages/EN.php');
    if (file_exists(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php')) {
        include(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php');
    }
}

// Create new order object
require(WB_PATH.'/framework/class.order.php');
$item_order = new order(TABLE_PREFIX.'mod_bakery_items', 'position', 'item_id', 'section_id');



// Remove any tags and add slashes
$old_link          = strip_tags($admin->get_post('link'));
$old_section_id    = strip_tags($admin->get_post('section_id'));
$new_section_id    = strip_tags($admin->get_post('new_section_id'));
$action            = strip_tags($admin->get_post('action'));

$title             = $admin->add_slashes(strip_tags($admin->get_post('title')));
$sku               = $admin->add_slashes(strip_tags($admin->get_post('sku')));
$stock             = $admin->add_slashes(strip_tags($admin->get_post('stock')));
$price             = $admin->add_slashes(strip_tags($admin->get_post('price')));
$shipping          = $admin->add_slashes(strip_tags($admin->get_post('shipping')));
$tax_rate          = $admin->add_slashes(strip_tags($admin->get_post('tax_rate')));
$active            = strip_tags($admin->get_post('active'));
$definable_field_0 = $admin->add_slashes(strip_tags($admin->get_post('definable_field_0')));
$definable_field_1 = $admin->add_slashes(strip_tags($admin->get_post('definable_field_1')));
$definable_field_2 = $admin->add_slashes(strip_tags($admin->get_post('definable_field_2')));
$description       = $admin->add_slashes(strip_tags($admin->get_post('description')));
$full_desc         = $admin->add_slashes($admin->get_post('full_desc'));

$images = array();
if (!empty($_POST['images'])) {
	foreach ($_POST['images'] as $img_id => $image) {
		// Strip tags and add slashes
		$image = array_map('strip_tags', $image);
		$image = array_map('addslashes', $image);
		// Sanitize vars
		$image['active']       = empty($image['active'])        ? 0 : 1;
		$image['delete_image'] = empty($image['delete_image'])  ? FALSE : $image['delete_image'];
		// Rejoin images array
		$images[$img_id]       = $image;
	}
}

$imgresize    = strip_tags($admin->get_post('imgresize'));
$quality      = strip_tags($admin->get_post('quality'));
$maxheight    = strip_tags($admin->get_post('maxheight'));
$maxwidth     = strip_tags($admin->get_post('maxwidth'));

$attribute_id = $admin->add_slashes(strip_tags($admin->get_post('attribute_id')));
$ia_operator  = $admin->add_slashes(strip_tags($admin->get_post('ia_operator')));
$ia_price     = $admin->add_slashes(strip_tags($admin->get_post('ia_price')));
$assign_id    = $admin->add_slashes(strip_tags($admin->get_post('assign_id')));

// Validate the title field
if ($admin->get_post('title') == '') {
	// Put item data into the session var to prepopulate the text fields after the error message
	$_SESSION['bakery']['item']['title']             = $title;
	$_SESSION['bakery']['item']['sku']               = $sku;
	$_SESSION['bakery']['item']['stock']             = $stock;
	$_SESSION['bakery']['item']['price']             = $price;
	$_SESSION['bakery']['item']['shipping']          = $shipping;
	$_SESSION['bakery']['item']['tax_rate']          = $tax_rate;
	$_SESSION['bakery']['item']['active']            = $active;
	$_SESSION['bakery']['item']['definable_field_0'] = $definable_field_0;
	$_SESSION['bakery']['item']['definable_field_1'] = $definable_field_1;
	$_SESSION['bakery']['item']['definable_field_2'] = $definable_field_2;
	$_SESSION['bakery']['item']['description']       = $description;
	$_SESSION['bakery']['item']['full_desc']         = $full_desc;
	$_SESSION['bakery']['item']['images']            = $images;
	$_SESSION['bakery']['item']['imgresize']         = $imgresize;
	$_SESSION['bakery']['item']['quality']           = $quality;
	$_SESSION['bakery']['item']['maxheight']         = $maxheight;
	$_SESSION['bakery']['item']['maxwidth']          = $maxwidth;
	$_SESSION['bakery']['item']['new_section_id']    = $new_section_id;
	$_SESSION['bakery']['item']['action']            = $action;
	// Show error message and go back
	$admin->print_error($MESSAGE['GENERIC_FILL_IN_ALL'], WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id);
}

// For currency inputs convert decimal comma to decimal point
$price    = str_replace(',', '.', $price);
$shipping = str_replace(',', '.', $shipping);



// MOVE ITEM TO ANOTHER BAKERY SECTION/PAGE

$moved = false;
if ($old_section_id != $new_section_id && $action == 'move') {
	// Get new page and section ids
	$query_sections = $database->query("SELECT page_id FROM ".TABLE_PREFIX."sections WHERE section_id = '$new_section_id'");
	$sections   = $query_sections->fetchRow();
	$page_id    = $sections['page_id'];
	$section_id = $new_section_id;
	// Get new order position
	$position   = $item_order ->get_new($section_id);
	$moved      = true;
}



// GET ITEM LINK

// Get module pages directory from general settings table
$query_general_settings = $database->query("SELECT pages_directory FROM ".TABLE_PREFIX."mod_bakery_general_settings");
$general_settings       = $query_general_settings->fetchRow();
$module_pages_directory = '/'.$general_settings['pages_directory'].'/';

// Work-out what the link should be
$item_link = $module_pages_directory.page_filename($title).PAGE_SPACER.$item_id;
// Replace triple page spacer by one page spacer
$item_link = str_replace(PAGE_SPACER.PAGE_SPACER.PAGE_SPACER, PAGE_SPACER, $item_link);



// UPDATE DATABASE

// Either insert or update ITEM ATTRIBUT...
if (isset($_POST['save_attribute']) AND $_POST['save_attribute'] != '') {

	// Get option_id from the attributes table
	$query_attributes = $database->query("SELECT option_id FROM ".TABLE_PREFIX."mod_bakery_attributes WHERE attribute_id = '$attribute_id'");
	$attribute = $query_attributes->fetchRow();
	$option_id = stripslashes($attribute['option_id']);

	// Update item attribute
	if (isset($_POST['attribute_id'])) {
		if (isset($_POST['assign_id']) && is_numeric($_POST['assign_id'])) {
			$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_item_attributes SET `option_id` = '$option_id', `attribute_id` = '$attribute_id', `price` = '$ia_price', `operator` = '$ia_operator' WHERE `item_id` = '$item_id' AND `assign_id` = '$assign_id'");
		}
		// Insert new item attribute
		else {
			$database->query("INSERT INTO ".TABLE_PREFIX."mod_bakery_item_attributes (item_id, option_id, attribute_id, price, operator) VALUES ('$item_id', '$option_id', '$attribute_id', '$ia_price', '$ia_operator')");
		}
	}

	// Check if there is a db error, otherwise say successful
	if ($database->is_error()) {
		$admin->print_error($database->get_error(), WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id.'#options');
	} else {
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id.'#options');
	}
}


// ... or update ITEM DATA
else {
	// Only update if position is set and has been changed
	$query_position = isset($position) ? " `position` = '$position'," : '';

	// Item images
	foreach ($images as $img_id => $image) {

		// Set image alt-text if left blank
		if (empty($image['alt'])) {
			if (!empty($image['caption'])) { $image['alt'] = $image['caption']; }
			if (!empty($image['title']))   { $image['alt'] = $image['title']; }
		}

		// Update db
		$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_images SET item_attribute_id = '{$image['attribute']}', `active` = '{$image['active']}', `alt` = '{$image['alt']}', `title` = '{$image['title']}', `caption` = '{$image['caption']}' WHERE img_id = '$img_id'");
	}

	// Item data
	$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET section_id = '$section_id', page_id = '$page_id', title = '$title', link = '$item_link', `sku` = '$sku', `stock` = '$stock', `price` = '$price', `shipping` = '$shipping', `tax_rate` = '$tax_rate', `definable_field_0` = '$definable_field_0', `definable_field_1` = '$definable_field_1', `definable_field_2` = '$definable_field_2', `description` = '$description', `full_desc` = '$full_desc', active = '$active',$query_position modified_when = '".@mktime()."', modified_by = '".$admin->get_user_id()."' WHERE item_id = '$item_id'");

	// Check if there is a db error, otherwise say successful
	if ($database->is_error()) {
		$admin->print_error($database->get_error(), WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id);
	} elseif ($action != 'duplicate') {

		// Different targets depending on the save action
		if (isset($_POST['save_and_return']) AND $_POST['save_and_return'] != '') {
			$return_url = WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id;
		}
		elseif (isset($_POST['save_and_return_to_images']) AND $_POST['save_and_return_to_images'] != '') {
			$return_url = WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id.'#images';
		}
		else {
			$return_url = ADMIN_URL.'/pages/modify.php?page_id='.$page_id;
		}
		// Print success message and return
		$admin->print_success($TEXT['SUCCESS'], $return_url);
	}

	// Clean up item ordering of former section id
	$item_order ->clean($old_section_id); 
}



// ACCESS FILE

// Make sure the item link is set and exists
// Make new item access files dir
make_dir(WB_PATH.PAGES_DIRECTORY.$module_pages_directory);
if (!is_writable(WB_PATH.PAGES_DIRECTORY.$module_pages_directory)) {
	$admin->print_error($MESSAGE['PAGES']['CANNOT_CREATE_ACCESS_FILE']);
} elseif ($old_link != $item_link OR !file_exists(WB_PATH.PAGES_DIRECTORY.$item_link.PAGE_EXTENSION) OR $moved) {
	// We need to create a new file
	// First, delete old file if it exists
	if (file_exists(WB_PATH.PAGES_DIRECTORY.$old_link.PAGE_EXTENSION) && $action != 'duplicate') {
		unlink(WB_PATH.PAGES_DIRECTORY.$old_link.PAGE_EXTENSION);
	}
	// Specify the filename
	$filename = WB_PATH.PAGES_DIRECTORY.$item_link.PAGE_EXTENSION;
	// The depth of the page directory in the directory hierarchy
	// 'PAGES_DIRECTORY' is at depth 1
	$pages_dir_depth = count(explode('/',PAGES_DIRECTORY))-1;
	// Work-out how many ../'s we need to get to the index page
	$index_location = '../';
	for ($i = 0; $i < $pages_dir_depth; $i++) {
		$index_location .= '../';
	}
	// Write to the filename
	$content = ''.
'<?php
$page_id = '.$page_id.';
$section_id = '.$section_id.';
$item_id = '.$item_id.';
define("ITEM_ID", $item_id);
require("'.$index_location.'config.php");
require(WB_PATH."/index.php");
?>';
	$handle = fopen($filename, 'w');
	fwrite($handle, $content);
	fclose($handle);
	change_mode($filename);
}



// IMAGE AND THUMBNAIL

// Make sure the target directories exist
// Set array of all directories needed
$directories = array(
	'',
	'/images',
	'/thumbs',
	'/images/item'.$item_id,
	'/thumbs/item'.$item_id
);

// Try and make the directories
foreach ($directories as $directory) {
	$directory_path = WB_PATH.MEDIA_DIRECTORY.'/bakery'.$directory;
	make_dir($directory_path);

	// Add index.php files if not existing yet
	if (!is_file($directory_path.'/index.php')) {
		$content = ''.
"<?php

header('Location: ../');

?>";
		$handle = fopen($directory_path.'/index.php', 'w');
		fwrite($handle, $content);
		fclose($handle);
		change_mode($directory_path.'/index.php', 'file');
	}
}

// Delete image if requested
foreach ($images as $image) {
	if ($image['delete_image'] !== FALSE) {
		$img_file = $image['delete_image'];
		// Thumbs use .jpg extension only
		$thumb_file = str_replace (".png", ".jpg", $img_file);
		// Try unlinking image and thumb
		if (file_exists(WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id.'/'.$img_file)) {
			unlink(WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id.'/'.$img_file);
		}
		if (file_exists(WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.$item_id.'/'.$thumb_file)) {
			unlink(WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.$item_id.'/'.$thumb_file);
		}
	}
}

// Add uploaded images
$upload_error    = '';
$file_type_error = false;
$num_images      = count($_FILES['image']['name']);
// Loop through the uploaded image(s)
for ($i = 0; $i < $num_images; $i++) {
	if (isset($_FILES['image']['tmp_name'][$i]) AND $_FILES['image']['tmp_name'][$i] != '') {

		// Get real filename and set new filename
		$file       = $_FILES['image']['name'][$i];
		$path_parts = pathinfo($file);
		$filename   = $path_parts['basename'];
		$fileext    = $path_parts['extension'];
		$filename   = str_replace('.'.$fileext, '', $filename); // Filename without extension
		$filename   = str_replace(' ', '_', $filename);         // Replace spaces by underscores
		$fileext    = strtolower($fileext);
		
		// Path to the new file
		$new_file = WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id.'/'.$filename.'.'.$fileext;

		// Make sure the image is a jpg or png file
		if (!($fileext == 'jpg' || $fileext == 'jpeg' || $fileext == 'png')) {
			$file_type_error = true;
			continue;
		}
		// Check for invalide chars in filename
		if (!preg_match('#^[a-zA-Z0-9._-]*$#', $filename)) {
			$errors[] = $MOD_BAKERY['ERR_INVALID_FILE_NAME'].': '.htmlspecialchars($filename.'.'.$fileext);
			continue;
		}
		// Check lenght of filename
		if (strlen($filename) > $filename_max_length) {
			$errors[] = $MOD_BAKERY['ERR_FILE_NAME_TOO_LONG'].': '.htmlspecialchars($filename.'.'.$fileext);
			continue;
		}
		// Check if filename already exists
		if (file_exists($new_file)) {
			$errors[] = $MESSAGE['MEDIA']['FILE_EXISTS'].': '.htmlspecialchars($filename.'.'.$fileext);
			continue;
		}

		// Upload image
		move_uploaded_file($_FILES['image']['tmp_name'][$i], $new_file);
		change_mode($new_file);

		// Check if we need to create a thumb
		$query_settings = $database->query("SELECT resize FROM ".TABLE_PREFIX."mod_bakery_page_settings WHERE section_id = '$section_id'");
		$fetch_settings = $query_settings->fetchRow();
		$resize         = $fetch_settings['resize'];
		if ($resize != 0) {
		
			// Thumbnail destination
			$thumb_destination = WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.$item_id.'/'.$filename.'.jpg';
			
			// Check thumbnail type
			if (!($fileext == 'png')) {
				make_thumb($new_file, $thumb_destination, $resize);
			} else {
				resizePNG($new_file, $thumb_destination, $resize, $resize);
			}
			change_mode($thumb_destination);
		}
	
	
		// Check if we need to resize the image
		if ($imgresize == 'yes' && file_exists($new_file)) {
	
			// Image destination
			$img_destination = WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id.'/'.$filename.'.jpg';

			// Check image type
			if (!($fileext == 'png')) {
				resizeJPEG($new_file, $maxwidth, $maxheight, $quality);
			} else {
				if (resizePNG($new_file, $img_destination, $maxwidth, $maxheight)) {
					// Try unlinking png image not used any more
					if (file_exists(WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id.'/'.$filename.'.png')) {
						unlink(WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id.'/'.$filename.'.png');
					}
				}
			}
			change_mode($img_destination);
			
			// After resizing change file extension to .jpg
			$fileext = 'jpg';
		}
	}
}

// If needed display upload error messages and return
if ($file_type_error || (isset($errors) && count($errors) > 0)) {
	if ($file_type_error) {
		$upload_error = $MESSAGE['GENERIC_FILE_TYPES'].' .jpg / .jpeg / .png<br />';
	}
	if (isset($errors) && count($errors) > 0) {
		$upload_error .= implode("<br />", $errors);
	}
	$admin->print_error($upload_error, WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id);
}






// DUPLICATE ITEM
// **************

if ($action == 'duplicate') {


	// DUPLICATE ITEM
	
	// Get new page and section ids
	if ($old_section_id != $new_section_id) {
		$query_sections = $database->query("SELECT page_id FROM ".TABLE_PREFIX."sections WHERE section_id = '$new_section_id'");
		$sections = $query_sections->fetchRow();
		$page_id = $sections['page_id'];
		$section_id = $new_section_id;
	}	
	// Get new order position
	$position = $item_order ->get_new($section_id);
	// Insert new row into database
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_bakery_items (section_id, page_id, position, created_when, created_by) VALUES ('$section_id', '$page_id', '$position','".@mktime()."','".$admin->get_user_id()."')");

	// Get the id
	$orig_item_id = $item_id;
	$item_id      = $database->get_one("SELECT LAST_INSERT_ID()");



	// GET NEW ITEM LINK

	// Work-out what the link should be
	$item_link = $module_pages_directory.page_filename($title).PAGE_SPACER.$item_id;
	// Replace triple page spacer by one page spacer
	$item_link = str_replace(PAGE_SPACER.PAGE_SPACER.PAGE_SPACER, PAGE_SPACER, $item_link);



	// UPDATE DATABASE

	// First get item attributes of the original item id
	$query_item_attributes = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_bakery_item_attributes WHERE item_id = '$orig_item_id'");
	if ($query_item_attributes->numRows() > 0) {
		while ($ia = $query_item_attributes->fetchRow()) {  // ia = item_attributes
			// Insert duplicated item attributes
			$database->query("INSERT INTO ".TABLE_PREFIX."mod_bakery_item_attributes (item_id, option_id, attribute_id, price, operator) VALUES ('$item_id', '{$ia['option_id']}', '{$ia['attribute_id']}', '{$ia['price']}', '{$ia['operator']}')");
		}
	}

	// Update duplicated item data
	$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET section_id = '$section_id', page_id = '$page_id', title = '$title', link = '$item_link', `sku` = '$sku', `stock` = '$stock', `price` = '$price', `shipping` = '$shipping', `tax_rate` = '$tax_rate', `definable_field_0` = '$definable_field_0', `definable_field_1` = '$definable_field_1', `definable_field_2` = '$definable_field_2', `description` = '$description', `full_desc` = '$full_desc', active = '0', modified_when = '".@mktime()."', modified_by = '".$admin->get_user_id()."' WHERE item_id = '$item_id'");

	// Check if there is a db error, otherwise say successful
	if ($database->is_error()) {
		$admin->print_error($database->get_error(), WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id);
	} else {
		// Different targets depending on the save action
		if ((isset($_POST['save_and_return']) AND $_POST['save_and_return'] != '') OR (isset($_POST['save_and_return_to_images']) AND $_POST['save_and_return_to_images'] != '')) {
			$return_url = WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$item_id;
		}
		else {
			$return_url = ADMIN_URL.'/pages/modify.php?page_id='.$page_id;
		}
		// Print success message and return
		$admin->print_success($TEXT['SUCCESS'], $return_url);
	}



	// ACCESS FILE

	// Make sure the item link is set and exists
	// Make new item access files dir
	if (!is_writable(WB_PATH.PAGES_DIRECTORY.$module_pages_directory)) {
		$admin->print_error($MESSAGE['PAGES']['CANNOT_CREATE_ACCESS_FILE']);
	} else {
		// We need to create a new file
		// Specify the filename
		$filename = WB_PATH.PAGES_DIRECTORY.$item_link.PAGE_EXTENSION;
		// The depth of the page directory in the directory hierarchy
		// '/pages' is at depth 1
		$pages_dir_depth = count(explode('/',PAGES_DIRECTORY))-1;
		// Work-out how many ../'s we need to get to the index page
		$index_location = '../';
		for ($i = 0; $i < $pages_dir_depth; $i++) {
			$index_location .= '../';
		}
		// Write to the filename
		$content = ''.
'<?php
$page_id = '.$page_id.';
$section_id = '.$section_id.';
$item_id = '.$item_id.';
define("ITEM_ID", $item_id);
require("'.$index_location.'config.php");
require(WB_PATH."/index.php");
?>';
		$handle = fopen($filename, 'w');
		fwrite($handle, $content);
		fclose($handle);
		change_mode($filename);
	}



	// IMAGE AND THUMBNAIL

	// Dublicate image data in the db
	$query_images = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_bakery_images WHERE item_id = '$orig_item_id'");
	if ($query_images->numRows() > 0) {
		while ($image = $query_images->fetchRow()) {
			// Insert duplicated images
			$database->query("INSERT INTO ".TABLE_PREFIX."mod_bakery_images (item_id, item_attribute_id, filename, active, position, alt, title, caption) VALUES ('$item_id', '{$image['item_attribute_id']}', '{$image['filename']}', '{$image['active']}', '{$image['position']}', '{$image['alt']}', '{$image['title']}', '{$image['caption']}')");
		}
	}

	// Prepare pathes to the source image and thumb directories
	$img_source_dir = WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$orig_item_id;
	$thumb_source_dir = WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.$orig_item_id;

	// Make sure the target directories exist
	make_dir(WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id);
	make_dir(WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.$item_id);
		
	// Check if the image and thumb source directories exist
	if (is_dir($img_source_dir) && is_dir($thumb_source_dir)) {
		// Open the image directory then loop through its contents
		$dir = dir($img_source_dir);
		while (false !== $image_file = $dir->read()) {
			// Skip index file and pointers
			if (strpos($image_file, '.php') !== false || substr($image_file, 0, 1) == ".") {
				continue;
			}
			// Thumbs use .jpg extension only
			$thumb_file = str_replace (".png", ".jpg", $image_file);

			// Pathes to the image/thumb source and destination respectively
			$img_source        = $img_source_dir.'/'.$image_file;
			$img_destination   = WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id.'/'.$image_file;
			$thumb_source      = $thumb_source_dir.'/'.$thumb_file;
			$thumb_destination = WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.$item_id.'/'.$thumb_file;

			// Try duplicating image and thumb
			if (file_exists($img_source)) {
				if (copy($img_source, $img_destination)) {
					change_mode($img_destination);
				}
			}
			if (file_exists($thumb_source)) {
				copy($thumb_source, $thumb_destination);
				change_mode($thumb_destination);
			}
		}
	}
}


// Print admin footer
$admin->print_footer();
