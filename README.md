# WordPress Starter Plugin
A convenient source to create a WordPress plugin.

`codeandbeauty-starter-plugin` is equip with classes and tools to start developing your desired plugin.

### Installation
Clone this repository inside /wp-content/plugins/ directory of your WordPress installation.

````
git clone https://github.com/codeandbeauty/starter-plugin.git
````

### Usage

##### Manual Customization:
* Change the class prefix `CodeAndBeauty` into your desired prefix. Class prefix usually the same with your plugin name (i.e. MySimplePlugin)
* Change all instances of `codeandbeauty` to your plugin slug (i.e. mysimpleplugin)
* Change all instances of `TEXTDOMAIN`, the domain slug, into your desired domain slug

Your all set and ready to start building your awesome WordPress plugin.

#### Auto Generation
You can use `Gulp` or `Grunt` to generate your new plugin.

Parameters:
* --folder = The name of folder/directory of your new plugin.
* --name = The name use as class prefix i.e. YourPluginName
* --slug = The slug use as prefix both action/filter hooks, function names, and other area that require prefixes.
* --domain = The domain slug of your plugin. If empty, it will use whatever string set in `--slug`

*Using Grunt*
````
$ grunt create-plugin --folder=your-plugin-name --name=YourPluginName --slug=yourpluginname --domain=cad
````

*Using Gulp*
````
$ gulp create-plugin --folder=your-plugin-name --name=YourPluginName --slug=yourpluginname --domain=cad
````

##### What this command do?
- It generates a new folder
- It changes the file, class, and slug names.

##### What's next?
Change the plugin name, description, version, author etc at the main plugin file. (i.e. codeandbeauty.php)

*Start developing your awesome WordPress plugin!*
