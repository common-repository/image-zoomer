=== Image Zoomer ===
Contributors: dynamicwp team
Donate link: http://dynamicwp.net
Tags: zoomer, magnify, ecommerce, image, showcase
Requires at least: 2.8
Tested up to: 2.9.2
Stable tag: 1.0

Image zoomer is jQuery zoom tool. The plugin gives any image on your page the ability to be magnified when the mouse rolls over it.

== Description ==

Image zoomer is jQuery zooming tool. The plugin gives any image on your page the ability to be magnified when the mouse rolls over it. The square effect uses a “magnifying glass” that appears over the image lets the user zoom in to show close-up detail of the image.

**Features**

* Easy – Just upload and activate the plugin.
* Customize – The default zoom level (ie: 2x) and The default size of the “magnifier” (ie: 75×75) can be changed.
* When the user scrolls the mouse wheel while over the image, the zoom level decreases or increases based on the scroll direction. The range of zoom can be changed (ie: 1x to 10x).

**Demo**



Please check [the plugin release page](http://www.dynamicwp.net/plugins/image-zoomer-plugin/) then hover an image demo then try to scroll up or down.

**Credit** 



This plugin based [Image Power Zoomer](http://www.dynamicdrive.com/dynamicindex4/powerzoomer.htm) from [Dynamic Drive](http://www.dynamicdrive.com/)

== Installation ==

1. Decompress the .zip archive and upload `zoomer.php` file into `/wp-content/plugins/` directory
1. Or the other way, with your wordpress admin page > Plugins > Add New then choose upload on the top. Next choose File (`zoomer.zip`) then Install Now (see the screenshots)
1. Activate the plugin through the `Plugins` menu in WordPress
1. Open Image Zoomer Settings Page to customize

== Frequently Asked Questions ==

= How to change the default zoom level? =

In `your wordpress admin page > Settings > Image Zoomer`, fill the form “Zoomer default power” with an integer greater or equal to 1.

= How to change the size of the “magnifier”? =

In `your wordpress admin page > Settings > Image Zoomer`, fill the form “Zoomer dimensions” with an integer, the default is 75 means 75×75.

= I want only certain images can be enlarged while others don’t, how? =

The default settings is your image will be zoomer all, but if you only want some of your image can be zoomer you can do it.
First go to image zoomer settings panel “Settings > Image Zoomer”, check with “no” in Option “Zoom all image”. Then open a post that have image you want to zoom.
In HTML editor find an image you want to zoom, than add “dwzoom” (without the quotes) to the class of image.

For practical example, please visit [the plugin release page](http://www.dynamicwp.net/plugins/image-zoomer-plugin/)

== Screenshots ==

1. An image with zooming effect
2. Plugin installation with Wordpress admin panel
3. Image Zoomer Options panel

== Changelog ==

= 1.0 =
* Initial release
