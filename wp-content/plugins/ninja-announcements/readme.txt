=== Ninja Announcements Lite ===
Contributors: kstover, jameslaws
Donate link: http://wpninjas.net
Tags: announcement, alert, notice
Requires at least: 3.1
Tested up to: 3.4
Stable tag: 2.3.2

This plugin lets you create announcements (text and/or media) that are displayed in various places of your WordPress installation.

== Description ==
*Notice* - Version 2.0 represents a huge leap forward for Ninja Announcements. If you have not already, please update to this version.
If you are upgrading from an earlier version of Ninja Announcements, we recommend that you deactivate the previous version and delete
it before installing this version.

As of Version 2.0, Ninja Announcements fully supports localization. If you are interesting in translating Ninja Announcements
into a non-English language, please visit wpninjas.net and let us know so that we can include it in future versions.

The Ninja Announcements plugin displays small portions of text and/or images/video on pages and posts. Generally, these
are used to let your visitors know about something special. They can be scheduled so that they are only displayed 
between specified dates/times and/or on certain days of the week.  For Example, if you wanted to wish everyone a Merry Christmas, but you didn't want
to display the message until the 20th of December, you could schedule an announcement to begin on December 20 and
end on December 26. A visitor coming to your site would see the announcement between those dates, but otherwise your 
site would look just the same.

As with all WP Ninjas plugins, we have tried to keep our code as simple and unobtrusive as possible. To this end, all 
annoucements are edited via the built-in WordPress Rich Text Editor. This means that Ninja Announcements doesn't have
to include its own version of TinyMCE. Moreover, you can also include images and videos from your WordPress media 
library or YouTube, so you don't have to create or maintain a separate media library for your announcements.

Each of your announcements has its own location and scheduling settings, allowing you to place the announcement 
exactly when and where you want it, even display it as a widget. All this without touching code, even shortcodes!

The administration section of Ninja Announcements makes it very easy to add and edit announcements. Older announcements
are not automatically deleted, but simply deactivated so that they can be edited later. Of course, these can just be deleted if you want.

Features of Ninja Announcements Lite:

	* Use multiple announcements, each with its own settings.
	* Consistent class and id tags make styling your announcements through CSS simple.
	* Choose from three different announcement placements: Header, Widget or Manual (Function) [No shortcodes required].
	* Schedule announcements by date, day and/or hour so that they only show for a certain time period.
	* Edit announcements using the same rich text editor as a WordPress post.
	* Insert images or videos into announcements from your WordPress Media Library, just like you would a post.
	* Since it uses the built-in WordPress rich text editor and media gallery, it has a small footprint.
	* Choose wether or not users are able to close the announcement.

Upgrading to Ninja Announcements Pro unlocks the following features:

	* Show your announcements on posts or pages, even attach them to specific pages, posts or categories.
	* Control the HTML output of Ninja Announcements by setting which wrapper elements it should use.
	* Restrict announcement display by user role. i.e. Show announcements only to people who are logged in or those who are not logged in. Display your announcement only to editors, subscribers, administrators etc.
	* Show random announcements by using the new "Announcement Group" functionality.
	* Set the length of time announcements stay closed after a user has clicked "closed".
	* Documentation regarding hooks and filters included in Ninja Announcements Pro.
	
== Screenshots ==
To see screenshots, please visit http://wpninjas.net.

	
== Installation ==

Installing Ninja Announcements is really simple. 
*Notice* - If you're installing Ninja Announcements on an 3.0 Multi-Site, you'll have to activate it on each blog you want to use
it on. Ninja Announcements does not currently support Network Activation.

1. Upload the plugin folder (i.e. ninja-announcements) to the /wp-content/plugins/ directory of your WordPress installation.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add or edit announcements by clicking on the "Ninja Announcements" link.
4. Have a snack. You're done.

== Use ==

The default position for all announcements is at the top of your blog, before any of your images or text. If you don't assign a location to
an announcement, this is where it will show up. If you don't want to put the announcement there, you have two other options: sidebar or manual.

If you select "Sidebar (Widget)" from the location list, the announcement will appear as a widget underneath your "Appearance->Widgets" 
admin section. You can then place the widget anywhere in your sidebar that you would like.

The third location option, "Manual (Function)", is for more advanced WordPress users. This option gives you a php function
to call within your template file. The function will show the desired announcement wherever you place the code within your template.
As each announcement has its own, slightly different, function, you'll have to set the location to "Manual (Function)" and
save your changes before you are given the php code.

(To see examples and screenshots of each of these uses, please visit http://wpninjas.net)


== Advanced Styling ==

As you can see from the screenshots in the section above, the default and manual locations come with a default style applied to them. 
These styles are located in the ninja_annc/css/ninja_annc_display.css file. If you would like to overwrite these default styles, you can do 
so by styling the id of the container div. This div will always have an id of: ninja_annc_3 where 3 is the id of the announcement you want to style. 
This id number can be found at the top of each announcement's edit page. We highly recommend that you change this in your own stylesheet, 
as future versions of this plugin will likely overwrite the display css file. 

For a more detailed explaination of styling your announcements and the close button, please visit: http://wpninjas.net

== Help / Bugs ==

*Notice* - This plugin has not been tested with any version of WordPress prior to 3.0. If you have trouble installing it on a
previous version, please keep this in mind. If you do have a working install of Ninja Announcements on an older version
of WordPress, we'd love to hear about it. Drop by the forums at http://plugins.wpninjas.net and let us know.

If you need help installing or getting things working with Ninja Announcements, visit our forums at http://plugins.wpninjas.net. The
forums are also where we take bug reports and feature requests.

== Requested Features ==

We are contemplating adding many features to future versions of the Ninja Announcements plugin. This is a non-exhaustive list:

	* Announcement previews
	* Multiple widget announcements
	* Multi-site network activation


If you have any requests, please drop by the forums at http://wpninjas.net and tell us about them.

== Changelog ==

= 2.3.2 =
* Fixed a long time bug with the default header location and Internet Explorer.

= 2.3.1 =
* fixed the automatic placement issue with IE browsers.
* This should also fix some meta tag issues that were occuring.

= 2.3 =
* fixed the the localization to the widget page
* Added Swedish Translationn -  Provided by Per Söderman of www.mesas.se

= 2.2 =
* Added some lines to the POT file that were missed initially.

= 2.1 =
* Removed Pro/Lite declaration in the Ninja Announcement menu so it stays on one line.
* Removed the explicit menu position to remove conflicts with other plugins.
* Add a check when location is a widget so that if inavtive the widget container won't be output.

= 2.0 =
* Completely reworked how Ninja Announcements works. 2.0 represents a much better version of the plugin overall.

= 1.4 =
*FIxed a major bug that prevented users from editing Ninja Announcements properly on WordPress 3.2.

= 1.3 =
*Fixed some major security holes within Ninja Announcements.

= 1.2.3 =
*Fixed a bug that prevented the link/unlick buttons from working on the editor.

= 1.2.2 =
* Fixed a major bug for some users that caused the announcement editor to appear as an all-white box.

= 1.2 =
* Fixed some minor bugs. One dealing with HTML validation and another with security issues.

= 1.1 =
* Added a "close" button to each announcement. This allows the user to close each for the rest of their browsing session
* Inclusion of a shortcode allows you to easily place an announcement on a page or post. [ninja_annc id=3]
* Added a new function for template designers that allows you to show all active announcements.

= 1.0 =
* First version of Ninja Announcements released.