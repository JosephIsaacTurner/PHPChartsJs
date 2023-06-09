<?php
    # Make sure you have the following script in your html file: 
    # <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>;
    <?php

function generateHistogram($data, $columnName) {
    static $count = 0;
    $count++;
	if ($count > 10) {
		$count = 0;
	}
    static $i = 77;
    $i--;
    if ($i == 0) {
        $i = 77;
    }
    // Extract the column values from the data array
    $columnValues = array_column($data, $columnName);

    // Count the occurrences of each unique value
    $counts = array_count_values($columnValues);

    // Prepare the labels and counts for the chart
    $labels = array_keys($counts);
    $countData = array_values($counts);
    // Sort the data and labels in descending order based on values
    // array_multisort($countData, SORT_DESC, $labels);

    // Convert the sorted data and labels to JSON
    $sortedCountData = json_encode($countData);
    $sortedLabels = json_encode($labels);
    
    $totalBars = count($labels);
    $gradientColors = generateGradientColors($totalBars); // Generate gradient colors based on the number of bars

    $chart = '<div style="width: 80%; margin: 0 auto;">
                    <canvas id="histogram-chart' . $count . $i. '" style="width: 500px !important; height: 200px !important;"></canvas>
                </div>
        <script>
            const ctx' . $count . $i .' = document.getElementById("histogram-chart' . $count .  $i.'").getContext("2d");
            new Chart(ctx' . $count . $i. ', {
                type: "bar",
                data: {
                    labels: ' . $sortedLabels . ',
                    datasets: [{
                        label: "Count by '. $columnName . '",
                        data: ' . $sortedCountData . ',
                        backgroundColor: ' . json_encode($gradientColors) . ',
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }
                }
            });
        </script>';
    return $chart;
}

function generateGradientColors($totalBars) {
    // Replace with your own color palette here if you want //
    $palette = ['#d6488380', '#bb3f7c80', '#9f377580', '#842e6d80', '#68266680', '#4d1d5f80', '#52296b80', '#57367780', '#5c428480', '#614f9080', '#665b9c80', '#5e5f9980', '#56649580', '#4e689280', '#466d8e80', '#3e718b80', '#537e9280', '#688b9980', '#7e99a180', '#93a6a880', '#a8b3af80', '#95adaa80', '#82a7a580', '#6ea0a180', '#5b9a9c80', '#48949780'];
    $colors = [];
    $paletteSize = count($palette);
    $step = 1 / ($totalBars + 1);

    for ($i = 0; $i < $totalBars; $i++) {
        $index = floor($i * $paletteSize / $totalBars) % $paletteSize;
        $colors[] = $palette[$index];
    }
    return $colors;
}

function generateMultiHistogram($data, $columnNames) {
    
    $charts = array();
    foreach($columnNames as $i=>$columnName){
        $charts[$i] = generateHistogram($data, $columnName);
    }
    
    $styleString = "<style>
    /* Style the tab */
    .tab {
      overflow: hidden;
      border: 1px solid #ccc;
      background-color: #f1f1f1;
    }    
    /* Style the buttons that are used to open the tab content */
    .tab button {
      width: auto !important;
      padding: 12px 10px !important;
      background-color: inherit;
      float: left;
      border: none !important;
      border-radius: 0 !important;
      outline: none;
      cursor: pointer;
      transition: 0.3s;
    }
    
    /* Change background color of buttons on hover */
    .tab button:hover {
      background-color: #ddd;
    }
    
    /* Create an active/current tablink class */
    .tab button.active {
      background-color: #ccc;
    }
    
    /* Style the tab content */
    .tabcontent {
      display: none;
      padding: 6px 12px;
      border: 1px solid #ccc;
      border-top: none;
    }
    </style>";
    
    // Create the HTML/JavaScript string for the histogram chart
    $chartHeader = '<div class="tab">';
    $chartContent = "";

    foreach($columnNames as $i=>$column){
        if ($i == 0){
            $active = "active";
            $display = 'style="display: block;"';
        }
        else{
            $active = "";
            $display = '';
        }
        $chartHeader .= "<button class='tablinks $active' onclick='openColumn(event, ". '"' . $column . '"' . ")'>$column</button>";
        $chartContent .= "<div id='$column' class='tabcontent' $display>
                            ". $charts[$i]. "
                        </div>";
    }
    $chartHeader .= '</div>';

    $chartString = "$styleString
                    <div class='chartParent' style='width: 80%; margin: 0 auto;'>
                    $chartHeader
                    $chartContent
                    </div>
                    ";
    $chartString .= '
    <script>
        function openColumn(evt, columnName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(columnName).style.display = "block";
        evt.currentTarget.className += " active";
        }
    </script>
    ';
    return $chartString;
}

function getColumnNames($data) {
    if (empty($data)) {
        return array(); // Return an empty array if there are no rows in the data
    }
    $firstRow = reset($data); // Get the first row of the data
    $columnNames = array_keys($firstRow); // Extract the column names
    return $columnNames;
}
function queryToArray($connection, $query){
    // This function accepts a database connection and SQL query as parameters.
    // The query is executed, and the results are returned as an array of keyed arrays.

    $result = mysqli_query($connection, $query);

    $data = array(); // Initialize an empty array to store the data

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row; // Append each row to the data array
    }

    return $data;
}

function generateStackedBarChart($data, $x_key, $y_key, $stack_key) {
    static $countStacked = 0;
    $countStacked++;
	if ($countStacked > 10) {
		$countStacked = 0;
	}
    static $iStacked = 77;
    $iStacked--;
    if ($iStacked == 0) {
        $iStacked = 77;
    }
    $stackValues = array_column($data, $stack_key);
    $uniqueStackValues = array_unique($stackValues);
    $colors = generateGradientColors(count($uniqueStackValues));

    $stackColorMapping = []; // To hold the color assigned to each company
    $colorIndex = 0;

    // Initialize the datasets array.
    $datasets = [];

    // Loop over each record in the SQL data.
    foreach ($data as $record) {
        $x_value = $record[$x_key];
        $stack_value = $record[$stack_key];
        $y_value = $record[$y_key];

        // If this stack value isn't in the datasets array yet, add it.
        if (!isset($datasets[$stack_value])) {
            // Check if the color for this stack_value has been assigned
            if (!isset($stackColorMapping[$stack_value])) {
                $stackColorMapping[$stack_value] = $colors[$colorIndex];
                $colorIndex++;
            }

            $datasets[$stack_value] = [
                'label' => $stack_value,
                'data' => [],
                'backgroundColor' => $stackColorMapping[$stack_value],
            ];
        }

        // Add the y value to this stack value's data.
        $datasets[$stack_value]['data'][$x_value] = $y_value;
    }

    // The x values in the chart.
    $x_values = array_unique(array_column($data, $x_key));
    sort($x_values);

    // The data array of each dataset should have an entry for each x value, even if
    // it's zero. This ensures that all bars are the same width.
   // After the foreach loop where you're filling $datasets array:

    // Loop over each record in the $datasets array.
    foreach ($datasets as $key => $dataset) {
        $newData = [];

        // Loop over each x value.
        foreach ($x_values as $x_value) {
            if (isset($dataset['data'][$x_value])) {
                // If there's data for this x value, add it to the new data array.
                $newData[] = $dataset['data'][$x_value];
            } else {
                // If there's no data for this x value, add a 0 to the new data array.
                $newData[] = 0;
            }
        }

        // Replace the old data array with the new one.
        $datasets[$key]['data'] = $newData;
    }


    // Generate the Chart.js code.
    $chart_js_code = '
        <div class="chartParent" style="width: 80%; margin: 0 auto;">
        <canvas id="stackedBar' . $countStacked . $iStacked . '"></canvas>
        </div>
        <script>
        var ctx = document.getElementById("stackedBar' . $countStacked . $iStacked . '").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ' . json_encode($x_values) . ',
                datasets: ' . json_encode(array_values($datasets)) . '
            },
            options: {
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        beginAtZero: true,
                        stacked: true
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.dataset.label || "";

                                if (label) {
                                    label += ": ";
                                }

                                if (context.parsed.y !== null) {
                                    label += context.parsed.y;
                                }

                                var total = 0;
                                for (var i = 0; i < context.dataset.data.length; i++) {
                                    total += Number(context.dataset.data[i]);
                                }

                                label += " (Total: " + total + ")";
                                return label;
                            },
                            footer: function(tooltipItems) {
                                let sum = 0;
                                
                                // Retrieve original chart data
                                var data = tooltipItems[0].chart.data;

                                // Calculate sum for all stacks of this bar
                                data.datasets.forEach((dataset) => {
                                    sum += Number(dataset.data[tooltipItems[0].dataIndex]) || 0;
                                });

                                return "Total: " + sum;
                            }
                        }
                    }
                }
            }
        });
        </script>
    ';
    return $chart_js_code;
}

    
?>