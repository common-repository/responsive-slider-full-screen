=== Responsive Slider Full Screen ===
Contributors: Module Express
Donate link: http://beautiful-module.com/
Tags: slider Full Screen,Responsive Slider Full,mobile Slider Full Screen,image slider,responsive header gallery slider,responsive banner slider,responsive header banner slider,header banner slider,responsive slideshow,header image slideshow
Requires at least: 3.5
Tested up to: 4.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A quick, easy way to add an header Responsive Slider Full Screen OR Responsive Slider Full Screen inside wordpress page OR Template. Also mobile touch Responsive Slider Full Screen

== Description ==

This plugin add a Responsive Slider Full Screen in your website. Also you can add Responsive Slider Full Screenpage and mobile touch slider in to your wordpress website.

View [DEMO](http://beautiful-module.com/demo/responsive-slider-full-screen/) for additional information.

= Installation help and support =

The plugin adds a "Responsive Slider Full Screen" tab to your admin menu, which allows you to enter Image Title, Content, Link and image items just as you would regular posts.

To use this plugin just copy and past this code in to your header.php file or template file 
<code><div class="headerslider">
 <?php echo do_shortcode('[sp_slider.fullscreen]'); ?>
 </div></code>

You can also use this Responsive Slider Full Screen inside your page with following shortcode 
<code>[sp_slider.fullscreen] </code>

Display Responsive Slider Full Screen catagroies wise :
<code>[sp_slider.fullscreen cat_id="cat_id"]</code>
You can find this under  "Responsive Slider Full Screen-> Gallery Category".

= Complete shortcode is =
<code>[sp_slider.fullscreen cat_id="9" autoplay="true" autoplay_interval="3000"]</code>
 
Parameters are :

* **limit** : [sp_slider.fullscreen limit="-1"] (Limit define the number of images to be display at a time. By default set to "-1" ie all images. eg. if you want to display only 5 images then set limit to limit="5")
* **cat_id** : [sp_slider.fullscreen cat_id="2"] (Display Image slider catagroies wise.) 
* **autoplay** : [sp_slider.fullscreen autoplay="true"] (Set autoplay or not. value is "true" OR "false")
* **autoplay_interval** : [sp_slider.fullscreen autoplay="true" autoplay_interval="3000"] (Set autoplay interval)

= Features include: =
* Mobile touch slide
* Responsive
* Shortcode <code>[sp_slider.fullscreen]</code>
* Php code for place image slider into your website header  <code><div class="headerslider"> <?php echo do_shortcode('[sp_slider.fullscreen]'); ?></div></code>
* Responsive Slider Full Screen inside your page with following shortcode <code>[sp_slider.fullscreen] </code>
* Easy to configure
* Smoothly integrates into any theme
* CSS and JS file for custmization

== Installation ==

1. Upload the 'responsive-slider-full-screen' folder to the '/wp-content/plugins/' directory.
2. Activate the 'Responsive Slider Full Screen' list plugin through the 'Plugins' menu in WordPress.
3. If you want to place Responsive Slider Full Screen into your website header, please copy and paste following code in to your header.php file  <code><div class="headerslider"> <?php echo do_shortcode('[sp_slider.fullscreen limit="-1"]'); ?></div></code>
4. You can also display this Images slider inside your page with following shortcode <code>[sp_slider.fullscreen limit="-1"] </code>


== Frequently Asked Questions ==

= Are there shortcodes for Responsive Slider Full Screen items? =

If you want to place Responsive Slider Full Screen into your website header, please copy and paste following code in to your header.php file  <code><div class="headerslider"> <?php echo do_shortcode('[sp_slider.fullscreen limit="-1"]'); ?></div>  </code>

You can also display this Responsive Slider Full Screen inside your page with following shortcode <code>[sp_slider.fullscreen limit="-1"] </code>



== Screenshots ==
1. Designs Views from admin side
2. Catagroies shortcode

== Changelog ==

= 1.0 =
Initial release