# Canopy

Working title for home energy data analysis and visualization tools. I'm interested in how we use energy at home to help make better decisions, plus charts and data are fun :)

## What Does Canopy Do?

Canopy is primarily a visualization and data management tool to better view your home energy consumption. Many electric utility companies provide export tools to download your hourly electricity usage but there are currently no good apps to view this data! With Canopy, once you download your usage files (often in xml format), you can use Canopy to store them, format them, and show you your data in a number of smart, interactive ways to help make better energy decisions.

In addition to electricity usage, Canopy can pull hourly weather data and add it as an overlay. You can also set vacations and time away as overlays, and a number of other settings to help determine what is working in your reducing your energy footprint.

## Getting Started

Once downloaded, copy /app/core/config.php to a new file /app/core/config.local.php and edit your local database and other environment settings. If you'd like to pull in weather data from Weather Underground, add your WU API key here as well (you can get a key on [Weather Uunderground's API website](http://www.wunderground.com/weather/api/))

Run all of the .sql files in /deploy to set up your database. The default name is canopy. The /deploy folder also contains (or will contain) a few sample xml files to check out Canopy before you get going or if you're in the process of getting access to your own energy data.

## Data Sources

### Electricity Usage Data

PECO supports a version of the Green Button xml data format and we're only supporting this right now. More information on the format and specifications is available at [Energy OS's Developer SDK page](https://github.com/energyos/OpenESPI-GreenbuttonDataSDK/).

The relevant hourly energy data is stored in IntervalReading nodes in the exported xml files:

* timePeriod->start: the starting time for the energy reading (UNIX timestamp)
* value: the kWh reading (in watts)
* cost: the price of that energy reading, in hundred-thousandths

There unfortunately isn't much more than this right now, as it would be great to get this usage on finer increments (PECO reports every 15 minutes!) to find out what appliances are causing spikes/overconsumption.

### Weather Data

Weather data is pulled from Weather Uunderground and requires an API key/account. This is optional and only required if you want to add the weather overlay to your charts (high/low temperatures and condition data). I find this useful for getting a high level picture and trend on usage. 