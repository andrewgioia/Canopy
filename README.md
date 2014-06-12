# Canopy

Working title for home energy data analsysis and visualization tools. I'm interested in how we use energy at home to help make better decisions, plus charts and data are fun :)

## Data Sources

PECO supports a version of the Green Button xml data format and we're only supporting this right now. More information on the format and specifications is available at [Energy OS's Developer SDK page](https://github.com/energyos/OpenESPI-GreenbuttonDataSDK/).

The relevant hourly energy data is stored in IntervalReading nodes:

* timePeriod->start: the starting time for the energy reading (UNIX timestamp)
* value: the kWh reading (in watts)
* cost: the price of that energy reading, in hundred-thousandths