# Tech Bench - File Link Module

This module is an extension to the Tech Bench application.  It allows users to create file links that will allow guests to either upload files for the user to access, or download files that the user has loaded to the link.  Each link has a unique URL to access it, as well as a set expiration date.  Once that date has passed, the files in the link are no longer accessible by guest access.

By using this feature, users can securely pass files to customers that may be too large for emailing or other means.

## Installation

To install this module:

Unzip the file and drop the "FileLinkModule" folder into the "Modules" folder in the Root of your Tech Bench application.  From the command prompt, navigate to the root directory of your Tech Bench installation and enter the following command:

```
php artisan tb_module:activate FileLinkModule
```

The script will link the Module to the Tech Bench instance and create the necessary Database tables and other files needed.
