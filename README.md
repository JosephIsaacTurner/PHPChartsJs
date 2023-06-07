# PHPHistogram
This contains a collection of functions that allow for autogenerating histograms for PHP pages. It uses PHP to generate Charts.Js histograms from arrays, usually from queries returned from a MySQL database.

Usage:
1) Somewhere in the page, make sure you include Chart.JS (<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>)
2) Include in your php page: include_once('PHPHistogram.php');
3) Create an array of keyed arrays for the data you want to visualize (If you are querying a MySQL database, you can autogenerate this array using the queryToArray($connection, $query) function). 
4) Call the function generateHistogram($data, $columnName), using the data array and the field you are interested in plotting. It returns an html string containing the histogram. 
5) If you are interesting in generating multiple parallel histograms from the same data array, you can use the function generateMultiHistogram($data, $columnNames), where the $columnNames argument is the array of fields you are interested in plotting.
6) If you want to autogenerate histograms based on all the possible fields in the data array, you can pass in an array from the getColumnNames($data) function, like so: generateMultiHistogram($data, getColumnNames($data));
