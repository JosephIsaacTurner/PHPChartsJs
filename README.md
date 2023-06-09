# PHPChartsJs
PHPHistogram is a comprehensive collection of functions that simplifies the process of generating histograms/charts for web pages served through PHP. It leverages PHP and Charts.js to create dynamic histograms from arrays, commonly obtained from queries returned by a MySQL database.
Demo Link: http://josephiturner.com/PHPChartsJs_Example.html

## Usage
To use PHPChartsJs, follow these steps:

1) Ensure that Chart.js is included somewhere in your page using the following script tag:
`<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>`
2) Include the PHPChartsJs library in your PHP page by adding the following line of code:
`include_once('PHPCharts.php');`
3) Prepare the data you wish to visualize by creating an array of keyed arrays (basically a JSON). If you are querying a MySQL database, you can conveniently generate this array using the `queryToArray($connection, $query)` function, or the native mysqli_fetch_all($result, MYSQLI_ASSOC) function.

4) Generate a histogram by calling the `generateHistogram($data, $columnName)` function, providing the data array and the desired field for plotting. This function will return an HTML string containing the generated histogram.

5) If you want to generate multiple parallel histograms from the same data array, you can use the `generateMultiHistogram($data, $columnNames)` function. The `$columnNames` argument should be an array of the fields you want to plot.

6) To automatically generate histograms for all possible fields in the data array, pass the result of the `getColumnNames($data)` function as the `$columnNames` argument to the `generateMultiHistogram($data, $columnNames)` function.

7) Make stacked bar charts using the `generateStackedBarChart($data, $x_key, $y_key, $stack_key)` function. `$x_key` is the array key for the fields to be plotted along the x axis. `$y_key` is the array key for the fields to be plotted along the y axis. `$stack_key` is the array key to separate values into stacks in the y dimension. Take a look at the Charts.Js documentation to see more: https://www.chartjs.org/docs/latest/samples/bar/stacked.html

## Example
Here's an example code snippet demonstrating the usage of PHPChartsJs:

```
// Include Chart.js
echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';

// Include PHPHistogram library
include_once('PHPCharts.php');

// Establish database connection
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);   

// Fetch data from MySQL database
$query = "SELECT * FROM your_table";
$data = queryToArray($connection, $query);

// Generate a histogram for a specific field
$histogram = generateHistogram($data, 'your_field_name');
echo $histogram;

// Generate multiple histograms separate by html tab groups based on all fields in the data array:
$multiHistogram = generateMultiHistogram($data, getColumnNames($data));
echo $multiHistogram;

$stackedBarChart = generateStackedBarChart($data, 'x_key', 'y_key', 'stack_key');
echo $stackedBarChart; 
```
By following these simple steps, you can effortlessly generate dynamic histograms for webpages using PHPHistogram.
