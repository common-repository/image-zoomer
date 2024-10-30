<?php

/*
Plugin Name: Image Zoomer
Plugin URI: http://www.dynamicwp.net/plugins/image-zoomer-plugin/
Description: With this plugin, your images will be zoomed in when you hover your mouse over those image
Author: Reza Erauansyah
Version: 1.0
Author URI: http://dynamicwp.net
*/

if (!class_exists("DynamicwpZoomer")) {
	class DynamicwpZoomer {
		var $adminOptionsName = "DynamicwpZoomerAdminOptions";
		function DynamicwpZoomer() { //constructor
			
		}
		function init() {
			$this->getAdminOptions();
		}
		//Returns an array of admin options
		function getAdminOptions() {
			$zoomerAdminOptions = array(
				'zoomerdefaultpower' => '',
				'zoomersize' => '',
				'allimage' => '')
				;
			$zoomerOptions = get_option($this->adminOptionsName);
			if (!empty($zoomerOptions)) {
				foreach ($zoomerOptions as $key => $option)
					$zoomerAdminOptions[$key] = $option;
			}				
			update_option($this->adminOptionsName, $zoomerAdminOptions);
			return $zoomerAdminOptions;
		}
		
		//Add jquery
		function mypunczoom(){
			wp_enqueue_script('jquery');
			}

		//Add zoomer script
		function mypunczoomscript(){
			$zoomerOptions = $this->getAdminOptions();

			$zoomerdefaultpower = $zoomerOptions['zoomerdefaultpower'];
			if($zoomerdefaultpower == ''){
				$newzoomerdefaultpower = 2;
			}
			else{
			$newzoomerdefaultpower=$zoomerdefaultpower;
			}
			
			$zoomersize = $zoomerOptions['zoomersize'];
			if($zoomersize == ''){
				$newzoomersize = 75;
			}
			else{
			$newzoomersize=$zoomersize;
			}
			
			$allimage = $zoomerOptions['allimage'];
			if($allimage != 'no')
				$newallimage = "
				 $('img.aligncenter').addpowerzoom()
				 $('img.alignleft').addpowerzoom()
				 $('img.alignright').addpowerzoom()
				 $('img.alignnone').addpowerzoom()
				 ";
			else
				$newallimage ="$('img.dwzoom').addpowerzoom()";
				 
			
			echo "
	<script type=\"text/javascript\">
	jQuery.noConflict()

var ddpowerzoomer={
	dsetting: {defaultpower:$newzoomerdefaultpower, powerrange:[1,10], magnifiersize:[$newzoomersize, $newzoomersize]},
	mousewheelevt: (/Firefox/i.test(navigator.userAgent))? \"DOMMouseScroll\" : \"mousewheel\", //FF doesn't recognize mousewheel as of FF3.x
	magnifier: {outer:null, inner:null, image:null},
	activeimage: null,

	movemagnifier:function(e, moveBol, zoomdir){
		var activeimage=ddpowerzoomer.activeimage //get image mouse is currently over
		var activeimginfo=activeimage.info
		var coords=activeimginfo.coords //get offset coordinates of image relative to upper left corner of page
		var magnifier=ddpowerzoomer.magnifier
		var magdimensions=activeimginfo.magdimensions //get dimensions of magnifier
		var power=activeimginfo.power.current
		var powerrange=activeimginfo.power.range
		var x=e.pageX-coords.left //get x coords of mouse within image (where top corner of image is 0)
		var y=e.pageY-coords.top
		if (moveBol==true){
			if (e.pageX>=coords.left && e.pageX<=coords.right && e.pageY>=coords.top && e.pageY<=coords.bottom)  //if mouse is within currently within boundaries of active base image
				magnifier.outer.css({left:e.pageX-magdimensions[0]/2, top:e.pageY-magdimensions[1]/2})	//move magnifier so it follows the cursor
			else{ //if mouse is outside base image
				ddpowerzoomer.activeimage=null
				magnifier.outer.hide() //hide magnifier
			}
		}
		else if (zoomdir){ //if zoom in
			var od=activeimginfo.dimensions //get dimensions of image
			var newpower=(zoomdir==\"in\")? Math.min(power+1, powerrange[1]) : Math.max(power-1, powerrange[0]) //get new power from zooming in or out
			var nd=[od[0]*newpower, od[1]*newpower] //calculate dimensions of new enlarged image within magnifier
			magnifier.image.css({width:nd[0], height:nd[1]})
			activeimginfo.power.current=newpower //set current power to new power after magnification
		}
		power=activeimginfo.power.current //get current power
		var newx=-x*power+magdimensions[0]/2 //calculate x coord to move enlarged image
		var newy=-y*power+magdimensions[1]/2
		magnifier.inner.css({left:newx, top:newy}) //move image wrapper within magnifier so the correct image area is shown
	},

	setupimage:function($, imgref, options){
		var s=jQuery.extend({}, ddpowerzoomer.dsetting, options)
		var imgref=$(imgref)
		imgref.info={ //create object to remember various info regarding image 
			power: {current:s.defaultpower, range:s.powerrange},
			magdimensions: s.magnifiersize,
			dimensions: [imgref.width(), imgref.height()],
			coords: null
		}
		imgref.unbind('mouseenter').mouseenter(function(e){ //mouseenter event over base image
			var magnifier=ddpowerzoomer.magnifier
			magnifier.outer.css({width:s.magnifiersize[0], height:s.magnifiersize[1]}) //set magnifier's size
			var offset=imgref.offset() //get image offset from document
			var power=imgref.info.power.current
			magnifier.inner.html('<img src=\"'+imgref.attr('src')+'\"/>') //get base image's src and create new image inside magnifier based on it
			magnifier.image=magnifier.outer.find('img:first')
				.css({width:imgref.info.dimensions[0]*power, height:imgref.info.dimensions[1]*power}) //set size of enlarged image
			var coords={left:offset.left, top:offset.top, right:offset.left+imgref.info.dimensions[0], bottom:offset.top+imgref.info.dimensions[1]}
			imgref.info.coords=coords //remember left, right, and bottom right coordinates of image relative to doc
			magnifier.outer.show()
			ddpowerzoomer.activeimage=imgref
		})
	},

	
	init:function($){
		var magnifier=$('<div style=\"position:absolute;width:100px;height:100px;display:none;overflow:hidden;border:1px solid black;\" />')
			.append('<div style=\"position:relative;left:0;top:0;\" />')
			.appendTo(document.body) //create magnifier container and add to doc
		ddpowerzoomer.magnifier={outer:magnifier, inner:magnifier.find('div:eq(0)'), image:null} //reference and remember various parts of magnifier
		magnifier=ddpowerzoomer.magnifier
		$(document).unbind('mousemove.trackmagnifier').bind('mousemove.trackmagnifier', function(e){ //bind mousemove event to doc
			if (ddpowerzoomer.activeimage){ //if mouse is currently over a magnifying image
				ddpowerzoomer.movemagnifier(e, true) //move magnifier
			}
		}) //end document.mousemove

		magnifier.outer.bind(ddpowerzoomer.mousewheelevt, function(e){ //bind mousewheel event to magnifier
			if (ddpowerzoomer.activeimage){
				var delta=e.detail? e.detail*(-120) : e.wheelDelta //delta returns +120 when wheel is scrolled up, -120 when scrolled down
				if (delta<=-120){ //zoom out
					ddpowerzoomer.movemagnifier(e, false, \"out\")
				}
				else{ //zoom in
					ddpowerzoomer.movemagnifier(e, false, \"in\")
				}
				e.preventDefault()
			}
		})
	}
} //ddpowerzoomer

jQuery.fn.addpowerzoom=function(options){
	var $=jQuery
	return this.each(function(){ //return jQuery obj
		if (this.tagName!=\"IMG\")
			return true //skip to next matched element
		var imgref=$(this)
		if (this.offsetWidth>0 && this.offsetHeight>0) //if image has explicit CSS width/height defined
			ddpowerzoomer.setupimage($, this, options)
		else if (this.complete){ //account for IE not firing image.onload
			ddpowerzoomer.setupimage($, this, options)
		}
		else{
			imgref.bind('load', function(){
				ddpowerzoomer.setupimage($, this, options)
			})
		}
	})
}

jQuery(document).ready(function($){ //initialize power zoomer on DOM load
	ddpowerzoomer.init($)
})
	
	
		jQuery(document).ready(function($){ 
			$newallimage
		})

	</script>";
		}	

		//Prints out the admin page
		function printAdminPage() {
					$zoomerOptions = $this->getAdminOptions();
										
					if (isset($_POST['update_DynamicwpZoomerSettings'])) { 
						if (isset($_POST['zoomerdefaultpower'])) {
							$zoomerOptions['zoomerdefaultpower'] = $_POST['zoomerdefaultpower'];
						}
						if (isset($_POST['zoomersize'])) {
							$zoomerOptions['zoomersize'] = $_POST['zoomersize'];
						}
						if (isset($_POST['allimage'])) {
							$zoomerOptions['allimage'] = $_POST['allimage'];
						}						
						
						update_option($this->adminOptionsName, $zoomerOptions);
						
						?>
						<div class="updated"><p><strong><?php _e("Settings Updated.", "DynamicwpZoomer");?></strong></p></div>
											<?php
											} ?>
						<div class="wrap">
							<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
							<h2>Image Zoomer</h2>
					
							<b>Zoomer default power : </b><br />
							<input type="text" name="zoomerdefaultpower" value="<?php if ($zoomerOptions['zoomerdefaultpower']) {echo $zoomerOptions['zoomerdefaultpower'];} else { echo '2'; }?>" style="width: 10%;" /><br />
							<small>Sets the default magnification power when the mouse rolls over the image. Value should be an integer greater or equal to 1. The default power is 2</small>					
			
							<br /><br />
					
							<b> Zoomer dimensions: </b><br />
							<input type="text" name="zoomersize" value="<?php if ($zoomerOptions['zoomersize']) {echo $zoomerOptions['zoomersize'];} else { echo '75'; }?>" style="width: 10%;" /><br />
							<small>Sets the dimensions of the zoomer that appears over the target image. 75 means the zoomer dimensions will be 75 x 75. </small>

							<br /><br />
							
							<b> Zoom all image: </b>
							<input type="radio" name="allimage" value="yes" <?php if($zoomerOptions['allimage'] != 'no'){?>checked="checked"<?php }?> /> yes &nbsp;&nbsp;
							<input type="radio" name="allimage" value="no" <?php if($zoomerOptions['allimage'] == 'no'){?>checked="checked"<?php }?> /> no<br />
							<small>Sets which image you want to zoom. if 'yes', then all image in the post will be zoomed automatically. If 'no', then you should add 'dwzoom' to the class of image you want to zoom. You can perform this via post html editor</small>

							<br /><br />
							<div class="submit">
								<input type="submit" name="update_DynamicwpZoomerSettings" value="<?php _e('Update Settings', 'DynamicwpZoomer') ?>" />	
							</div>
							</form>
						</div>
					<?php
				}//End function printAdminPage()
	
	}

} //End Class DynamicwpZoomer

if (class_exists("DynamicwpZoomer")) {
	$zoomer_plugin = new DynamicwpZoomer();
}

//Initialize the admin panel
if (!function_exists("DynamicwpZoomer_ap")) {
	function DynamicwpZoomer_ap() {
		global $zoomer_plugin;
		if (!isset($zoomer_plugin)) {
			return;
		}
		if (function_exists('add_options_page')) {
	add_options_page('<b style="color: #C50606;">Image Zoomer</b>', '<b style="color: #C50606;">Image Zoomer</b>', 9, basename(__FILE__), array(&$zoomer_plugin, 'printAdminPage'));
		}
	}	
}

//Actions and Filters	
if (isset($zoomer_plugin)) {
	//Actions
	add_action('admin_menu', 'DynamicwpZoomer_ap');
	add_action('activate_zoomer/zoomer.php',  array(&$zoomer_plugin, 'init'));
	
	if(!is_admin()){
	add_action('init', array(&$zoomer_plugin, 'mypunczoom')); 
	add_action('wp_head', array(&$zoomer_plugin, 'mypunczoomscript'));
	}
}


?>
