RocReport Twitter
=================

Twitter integration for RocReport

The code for the RocReport backend has beenmoved to https://github.com/rickylaishram/RocReport-Server

## Requirements
sixohsix's Python Twitter Tools https://github.com/sixohsix/twitter

Install with
	pip install twitter


## Configuration (data.py)

Create a file data.py, or rename data-sample.py to data.py.
In data.py, fill in all the relevant values

#### Location
In the class Geo, you have to add locations.
Suppose you want to search tweets in NYC; latitude 40.7056308, longitude -73.9780035 with a radius 5 miles, add the following to Geo class

	location['nyc'] = "40.7056308,-73.9780035,5mi"

Then, you will execute the search as

	python fetch.py nyc

You can add multiple locations.

License
================
GPL v3
