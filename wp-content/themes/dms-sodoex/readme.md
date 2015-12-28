Skeleton was created to give DMS users an effective foundation for building DMS themes. Due to Skeletons simplicity, it allows for quick and efficient theming.

### Getting Started
[![Gitter](https://badges.gitter.im/Join Chat.svg)](https://gitter.im/Dannyholt/skeleton?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Skeleton contains two branches (versions), these are Basic and Advanced.

#### Basic

The Basic version is mainly aimed at users who wish to make basic style alterations and includes the following:

~~~
skeleton/
├── functions.php/
├── screenshot.png
├── style.css/
└── style.less/
~~~

<p><a class="btn" href="https://github.com/Dannyholt/skeleton/archive/basic.zip" role="button">Download Skeleton Basic</a></p>

#### Advanced

The Advanced version is mainly aimed for developers wishing to create child themes for the [PageLines Marketplace](http://pagelines.com/shop), their clients and includes the following:

~~~
skeleton/
├── less/
│   ├── variables.less/
├── sections/
│   ├── masthead/ /* Overrides the core Masthead */
├── functions.php/
├── screenshot.png
├── style.css/
└── style.less/
~~~

Which allows you to override the default styling of DMS and create childs of the core sections bundled with DMS.

<p><a class="btn" href="https://github.com/Dannyholt/skeleton/archive/advanced.zip" role="button">Download Skeleton Advanced</a></p>


### Rebranding

**Skeleton** can easily be rebranded, by following the simple instructions below:

#### Directory Name

To get started simply download your preferred Skeleton version and extract the **.zip**, which will create a directory called **skeleton-version-name** on your computer. Rename this directory to whatever you like, usually this is your themes name.

#### Edit style.css

Open **style.css** in your favourite code editor (I recommend [Sublime Text](http://www.sublimetext.com/)), where you will find the following:

~~~
/*
Theme Name: Skeleton
Theme URI: http://www.pagelines.com
Description: Skeleton, a basic child theme for PageLines DMS
Version: 1.0.0
Author: Danny Holt
Author URI: https://github.com/Dannyholt/
Template: dms
*/
~~~

You can edit all headers except for `Template: dms` which informs Wordpress that this is a child theme for DMS. For example:

~~~
/*
Theme Name: My Theme Name
Theme URI: http://www.my-website.com
Description: My Themes description goes here...
Version: 1.1.1
Author: My Name goes here
Author URI: http://www.my-website.com
Template: dms
*/
~~~


### Install

Once you're happy with your edits, save your changes and upload the theme to your WordPress installation. You can do this by creating a **.zip** of your child theme directory and uploading to your site via the WordPress theme installer.

If you're unsure how to create a **.zip** of your child theme directory, use the guides below for your preferred OS.

* [Mac OSX](http://www.macinstruct.com/node/159)
* [Windows](http://windows.microsoft.com/en-gb/windows/compress-uncompress-files-zip-files)

An alternative method of installation would be to use an FTP client and upload the **unzipped directory** to the following:

~~~
/wp-content/themes/
~~~

For a more indepth guide on how to install a WordPress theme, follow the instructions on this [guide](http://codex.wordpress.org/Using_Themes).

### Issues

Have some problems with Skeleton? [Open an issue.](https://github.com/Dannyholt/skeleton/issues)
