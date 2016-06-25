# Revolution Slider Search Replace CLI
WP CLI Command to search replace the website URLs in the Revolution sliders

### Installation

- Download the plugin [zip](https://github.com/Nikschavan/revslider-search-replace/archive/master.zip) file from Github.
- Upload the zip file to WordPress dashboard in Plugins -> Add new and activate the plugin.

### Usage

##### OPTIONS

- `<id>`
  ID of the slider, also takes "all" as option where it will search accross all the sliders

- `<source-url>`
  Source URL

- `<destination-url>`
  destination URL

- [`--network`]
  Search Replace the strings in Revolution sliders throughout all the sites in multisite network

##### EXAMPLES

1. `wp rsr 2 <source-url> <destination-url>`
  - This will search replace the strings in the slider with is "2"
2. `wp rsr all <source-url> <destination-url>`
  - This will search replace the strings on all the sliders on the site
3. `wp rsr all <source-url> <destination-url> --network`
	- This command will will search replace the strings on all sliders accross all the sites in multisite network.
