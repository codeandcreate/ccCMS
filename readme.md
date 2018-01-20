# ccCMS / ccPHP

This is my take to a simple Framework based on my daily doings at work. At the moment there isn't a CMS part. 

I use ccCMS/ccPHP as base for my pages like [Code And Create](http://codeandcreate.de).

## Usage

* Basic configuration
  * `/ccCMS/cms.conf.php` - Basic cms configuration; it's only relevant to /REST/custom at the moment
  * `/ccCMS/installation.conf.php` - Additional configuration for the installation
  * `/ccCMS/frontend_rest.conf.php` - Configuration for the restful server on /REST/
* Frontend
  * Static pages are located in `/site/static/`. The Framework looks for md5 encodet filenames in this folder. For example "/index.html" is located in /site/static/`d1546d731a9f30cc80127d57142a482b`.html. 
  * Dynamic pages are located in `/sites/dynamic/`. `ccCMS_dynamic.conf.php` is the main configuration for dynamic pages.
* Backend
  * The framework includes a simple restful server. The class is located here: `/ccCMS/customized/custom_restfulServer.lib.php`

## Documentation

The most documentation is inline and a example page is included at `/site/`. More documentation can be found on [my page](http://codeandcreate.de/projekte/projekt,privat_ccPHP).

## Licence

My own code uses the MIT licence. 3rd party libs may have others.