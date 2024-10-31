=== Phraseanet Wordpress Client ===
Contributors: alchemydev,nicolas,sam0hack
Tags: Phraseanet, images, gallery, assets, media, Gutenberg
Requires at least: 5.6.0
Tested up to: 6.0
Requires PHP: PHP 7.4.4
Stable tag: 1.3.11 
License: GNU General Public License (GPL) version 3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt

This plugin creates the possibility to get and add assets from Phraseanet server into your Wordpress website.

== Description ==
This plugin creates the possibility to get and add assets from Phraseanet server into your Wordpress website. 
This plugin allows you to create a Phraseanet Gutenberg block with various custom configurations that allows you to customize the block the way you want. 

**Customizations block settings**

1. Block title - You can customize the block title by adding a custom title in the block settings.
2. Collections - You can choose the collections you want to display in the block.
3. Query - You can add your custom query to the block.
4. Define displayed facets - You can define the facets you want to display in the block.
5. Preview details - Fields that will display on the preview Eg. title,keyword,city.
6. Sub defination maping - You can map the sub defination to the fields you want to display on the thumb and preview.

**Customizations block UI settings**

**Preview assets UI settings**

1. Overlay - This option allows you to preview the asset in a modal .
2. Sidebar - This option allows you to preview the asset in a sidebar.

**Image Grid layout settings**

1. Classic - This option allows you to display the assets in a classic rectangular grid.
2. Masonry - This option allows you to display the assets in a masonry grid (like instagram grid).

**Masonry style settings**

1. Auto  - Auto detect the image layout type (portrait and landscape) and render the image accordingly
2. Random - Randomly renders the image layout
 

== Installation ==
1. Install easily with the Wordpress plugin control panel or manually download the plugin and upload the folder `Phraseanet-Wordpress-Client` to the `/wp-content/plugins/` directory.  

2. Activate the plugin through the 'Plugins' menu in Wordpress

3. Look at your admin bar you will find Phraseanet client there also in the page or post type search Phraseanet to get and insert the Gutenberg block in the post. It's that easy now enjoy using the phraseanet plugin.


== Frequently Asked Questions ==

= What Phraseanet wordpress plugin do? =

The Phraseanet WordPress plugin integrates with the Phraseanet service and allows users to create blocks and populate them in the content areas. Blocks are customizable items that can be added to any WordPress page or post.

= How does Phraseanet Client plugin Work? =

Phraseanet Client plugin will be connected to your Phraseanet server endpoint and allows you to make queries and get assets from your server to your Wordpress Gutenberg block

= Can the Assets/images be saved to my server? =

No. The Assets are already stored on your Phraseanet server so there is no need to duplicated those assets on your Wordpress server.
All the assets are coming from your Phraseanet server in the form of API. 


= How is the Phraseanet Client plugin Performance? =

This plugin is build with efficiency in mind and built with PHP and ReactJs 
 

= Is  Phraseanet Client plugin Free? =

Yes. it is free but some premium features are paid.


== Screenshots ==
1. Admin - Block view with customizing options.
2. Front-End view of the block with facets sidebar.
3. Front-End view of the block without facets sidebar.
4. Auth settings.
5. UI and number of results per page settings.
6. Adding a Requester.
7. Configure the requester.
8. (New) Subdefination mapper to easily manage and map fields.
9. Color Picker for preview Details (key and value)
10. Mix and match colors of the phraseanet block with your theme. 

== Changelog ==

Author: sam0hack <sam.nyx@live.com>

Date:   Tue Jun 07 15:46:17 2022 +0200

    Added Color picker for preview details.
    Mix and match colors of the phraseanet block with your theme.
    Various errors fixes.
    Smoother UI.

Date:   Fri May 26 17:00:37 2022 +0200

    Tested with Wordpress Version 6.0 Upto 2022 theme



Author: sam0hack <sam.nyx@live.com>
Date:   Fri May 16 15:40:37 2022 +0200

    Add mapping for Embed 


Author: sam0hack <sam.nyx@live.com>
Date:   Fri May 13 17:44:37 2022 +0200

    typo fix

Author: sam0hack <sam.nyx@live.com>
Date:   Fri May 13 17:33:53 2022 +0200

    perf(PC-59): :zap: Fix styling of the subdef list, add download & permalink list and refactor the code to improve.
    
    Fix styling of the subdef list in subdef settings, add download & permalink list and refactor the code to improve the performace and readability of the code

Author: sam0hack <sam.nyx@live.com>
Date:   Fri May 13 12:19:12 2022 +0200

    perf: :zap: image loading eager or lazy based on the visible viewport

Author: sam0hack <sam.nyx@live.com>
Date:   Thu May 12 18:13:30 2022 +0200

    fix: PC-59 fix the subdef mapping with only one mapping and remove other mapping from post block

Author: sam0hack <sam.nyx@live.com>
Date:   Thu May 12 18:11:04 2022 +0200

    change style

Author: sam0hack <sam.nyx@live.com>
Date:   Wed May 11 19:25:48 2022 +0200

    fix: :bug: PC-59 fix bug when saving the selected the subdef with config
    
    PC-59 fix bug when saving the selected the subdef with config and changing subdef was showing previous saved list of different subdef config

Author: sam0hack <sam.nyx@live.com>
Date:   Wed May 11 13:27:07 2022 +0200

    style: :art: Add plugin footer

Author: sam0hack <sam.nyx@live.com>
Date:   TUE May 10 11:58:08 2022 +0100

    feat: Add subdef mapping in the admin settings. where you can map the subdef to the fields you want to display on the thumb and preview.


Author: sam0hack <sam.nyx@live.com>
Date:   TUE May 03 16:05:08 2022 +0100

    fix: Composer installed issue

Author: sam0hack <sam.nyx@live.com>
Date:   TUE May 03 15:30:08 2022 +0100

    fix: PC-61 fix: Permalink copy fallback method error and fix: error when add normal block wrap iframe in if condition


Author: sam0hack <sam.nyx@live.com>
Date:   TUE Apr 26 18:21:08 2022 +0100

    perf: Native Lazy Loading on images

Author: sam0hack <sam.nyx@live.com>
Date:   TUE Apr 26 18:21:08 2022 +0100
    
    ver - 1.3.0
    feat: add new subdef mapping along with audio assets selector
    fix:  style issue on the overlay view
    fix:  squash minor bugs


Author: sam0hack <sam.nyx@live.com>
Date:   TUE Apr 12 14:03:08 2022 +0100

    Fix: Updating issues

Author: sam0hack <sam.nyx@live.com>
Date:   FRI Apr 01 14:03:08 2022 +0100

    We squashed some bugs and improve the UI expirience.

Author: sam0hack <sam.nyx@live.com>
Date:   Wed Mar 30 21:10:08 2022 +0100

    fix: fix: invalid query when click on facets. 

Author: sam0hack <sam.nyx@live.com>
Date:   Wed Mar 30 19:10:08 2022 +0100

    fix: Fix the facets default view. Now facets sidebar will be visible until user disables it. 

commit 2e4e1c014ec6f9b64f1aef7d5368fa586435207f
Author: sam0hack <sam.nyx@live.com>
Date:   Thu Mar 3 15:29:08 2022 +0100

    PC-55 version upgrade for WP

commit d9d5a81e08d8d5f0fa1b03e7c81c4503d5fdf0d4
Author: sam0hack <sam.nyx@live.com>
Date:   Thu Mar 3 13:04:39 2022 +0100

    fix: PC-55 logo

commit e96f119974407f1b7e8119986645eb469a7b8d75
Author: sam0hack <sam.nyx@live.com>
Date:   Thu Mar 3 13:02:08 2022 +0100

    patch: PC-55 All of the issues are minor except for one that allows WP logged-in users to turn the SDK's debug mode on, which activates logging and can potentially expose some Freemius-related sensitive variables — like the opted-in user email address and API keys
    
    patch: PC-55 All of the issues are minor except for one that allows WP logged-in users to turn the SDK's debug mode on, which activates logging and can potentially expose some Freemius-related sensitive variables — like the opted-in user email address and API keys

commit 8b904b17b876143d05af5965a37eada53e019a7f
Author: sam0hack <sam.nyx@live.com>
Date:   Wed Mar 2 15:13:17 2022 +0100

    fix: PC-52 and PC-54 SVN commit rejected by Wordpress pre-commit hooks issues with Wordpress compatibility
    
    fix: PC-54 by adding the local version of Phraseanet-php-sdk with fixed version of doctrine/collection and doctrine/cache

commit ed03d62aabb2f0ac2fd8e345f6d4414840ff9e4a
Author: sam0hack <sam.nyx@live.com>
Date:   Fri Feb 25 15:06:33 2022 +0100

    fix: PC-48 improve performance

commit 2bf8af15f0dd52080fffa461d07f801b176755c8
Author: sam0hack <sam.nyx@live.com>
Date:   Fri Feb 25 15:05:50 2022 +0100

    fix: PC-52 indentation

commit 60882dc2ddebc1542c5b67cf483415b46f690a74
Author: sam0hack <sam.nyx@live.com>
Date:   Thu Feb 24 16:46:20 2022 +0100

    fix: PC-48 Fix wrong asset download
    
    fix: PC-48 after updating collection chooser with the allowed collections the download asset functionality broke.

commit 684774d5e8beb007427958463f5b55ad0567adee
Author: sam0hack <sam.nyx@live.com>
Date:   Thu Feb 24 16:14:42 2022 +0100

    fix: PC-52 Optimize the lodash imports
    
    Optimized the lodash imports and removed .map files from production to reduce the build size.


== Upgrade Notice ==
Please check the Plugin compatibility with the installed Wordpress version before upgrade.
