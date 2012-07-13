# NeatlineTime plugin for Omeka

The NeatlineTime plugin, by the [Scholars' Lab][scholarslab] at the University of Virginia Library, allows you to create timelines for the [Omeka][omeka] publishing platform. It uses the [SIMILE Timeline plugin][simile-timeline].

## Installation

1. Upload the 'NeatlineTime' plugin directory to your Omeka installation's 'plugins' directory. See [Installing a Plugin][installing-a-plugin].

2. Activate the plugin from the admin → Settings → Plugins page.

## Usage

Once installed, NeatlineTime will add a tab to the Omeka admin panel. From here, you can browse existing timelines, and add, edit, and delete timelines.

Uninstalling the plugin will only remove timelines added to your Omeka archive, not any items displayed on those timelines.

### Add a Timeline

Creating a timeline is a two-step process:

1. From the admin → NeatlineTime page, click the "Add New Timeline" button to begin creating a timeline.

2. Give your timeline a title and description, and choose whether you wish to make the timeline public and featured. Save your changes.

  ![Browse Timelines](http://23.21.98.97/wp-content/uploads/2011/05/timeline_1.png)

3. To choose which items appear on your timeline, click the "Edit Query" link beside your existing timeline.

  ![Edit Query Link](http://23.21.98.97/wp-content/uploads/2011/05/timeline_3.png)

4. This will take you to a form similar to Omeka's advanced search form. From here, you can perform a search for any items in your archive, and if those items contain a valid date in their Dublin Core:Date field, they will be displayed on the timeline.

  ![Edit Query](http://23.21.98.97/wp-content/uploads/2011/05/timeline_4.png)

5. With a query defined, the matching items will be rendered on the timeline:

  ![Timeline](http://23.21.98.97/wp-content/uploads/2011/05/timeline_51.png)

#### Dates for Items

NeatlineTime will attempt to convert the value for a date string into an ISO-8601 date format. Some example date values you can use:

  * January 1, 2012
  * 2012-01-01
  * 1 Jan 2012
  * 2012-12-15

To denote spans of time, separate the start and end date with a '/':

  * January 1, 2012/February 1, 2012

NeatlineTime doesn't accept just years (*1066*, for example) because it's not clear what that means. Should that translate to the range *January 1, 1066/December 31, 1066*? Should it be *January 1, 1066*? *June 31, 1066*? Instead of us picking an arbitrary point in the year or marking the entire year, we simply ask that you be more specific.

### Browsing timelines

You can browse existing timelines by clicking on the "Browse Timelines" from your public theme, or the "NeatlineTime" tab in the admin panel.

### Viewing specific timelines

You can always see your timeline by click the title of the timeline in the admin. The URL for your timelines will be 'neatline-time/timelines/show/[id]', where [id] is the ID number for your timeline.

  ![Public Show](http://23.21.98.97/wp-content/uploads/2011/05/timeline_6.png)

### Modifying theme templates for Neatline Time

Neatline Time contains theme templates that control how its various pages are displayed in your public theme. As with other Omeka plugins, you can override these using the instructions on the [Theming Plugin Pages][themeing-plugin-pages] codex page.

The template files available in NeatlineTime include:

* timelines/browse.php - The template for browsing existing timelines.
* timelines/show.php - The template for showing a specific timeline.

## Contributing to the Project

### Feedback We rely on the [Github issues tracker][issues] for feedback on issues and improvements.

### Patches/Pull Requests

* Fork the project.
* Make your feature addition or bug fix.
* Add tests for it, and make sure all the tests pass. This is important so we don't unknowingly break your changes in a future release. If you're fixing a bug, it helps us to verify that your bug does in fact exist. Both NeatlineTime and Omeka use [PHPUnit][phpunit] to ensure the quality of the software.
* Commit your changes to your own fork.
* Send us a pull request, with a clear explanation of the changes. Bonus
  points for topic branches.

## Copyright

Copyright (c) 2010–2012 The Board and Visitors of the University of Virginia. See [LICENSE][license] for more information.

[scholarslab]: http://scholarslab.org/
[omeka]: http://omeka.org
[simile-timeline]: http://www.simile-widgets.org/wiki/Timeline
[installing-a-plugin]: http://omeka.org/codex/Installing_a_Plugin
[license]: LICENSE
[issues]: http://github.com/scholarslab/NeatlineTime/issues/ "Issues for Neatline Time"
[phpunit]: http://www.phpunit.de/manual/current/en/ "PHP Unit"
[theming-plugin-pages]: http://omeka.org/codex/Theming_Plugin_Pages "Theming Plugin Pages"
