<?php
	/* Libchart - PHP chart library
	 * Copyright (C) 2005-2011 Jean-Marc Trémeaux (jm.tremeaux at gmail.com)
	 * 
	 * This program is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 * 
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
	 * 
	 */
	
	/*
	 * Vertical bar chart demonstration
	 *
	 */

	include "libchart/classes/libchart.php";

                 $db_host = "localhost"; //Host address (most likely localhost)
                 $db_name = "intraview"; //Name of Database
                 $db_user = "root"; //Name of database user
                 $db_pass = ""; //Password for database user

$qid=$_GET['qiID'];
$host="localhost";
$username="root";
$password="";
$dbname="yacrs";
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

	$chart = new VerticalBarChart();
	$dataSet = new XYDataSet();

    $sql="SELECT question_id, COUNT(value) AS val1, COUNT(value_cq) AS val2 FROM yacrs_response WHERE question_id=$qid GROUP BY value";
    $result = $conn->query($sql);

$data_points = array();
$i=1;
foreach($result as $r)
{
    $qid=$r['question_id'];
    $val1=$r['val1'];
    $val2=$r['val2'];
    $point = array($i, $val1, $val2);
    array_push($data_points, $point);
    $i++;
}
$data=$data_points;



require_once 'phplot/phplot.php';
$plot = new PHPlot(800, 600);
$plot->SetImageBorderType('plain');

$plot->SetPlotType('stackedbars');
$plot->SetDataType('text-data');
$plot->SetDataValues($data);

$plot->SetTitle('Answer Analysis');
$plot->SetYTitle('Count');

# No shading:
$plot->SetShading(5);

$plot->SetLegend(array('Main Questions', 'Confidence Building'));
# Make legend lines go bottom to top, like the bar segments (PHPlot > 5.4.0)
$plot->SetLegendReverse(True);

$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

# Turn on Y Data Labels: Both total and segment labels:
$plot->SetYDataLabelPos('plotstack');

$plot->DrawGraph();
?>
<body>
	<img alt="Vertical bars chart" src="cq_graph.png" style="border: 1px solid gray;"/>
</body>
</html>
