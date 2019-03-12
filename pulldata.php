<?php

ini_set('display_errors', 'On');
echo "working .. wait";
//ob_flush();
flush();

$xmlArray = array("brislington.xml" => 3, "fishponds.xml" => 6, "newfoundland_way.xml" =>11, "parson_st.xml" => 8, "wells_rd.xml" => 10, "rupert_st.xml" => 9 );

foreach ($xmlArray as $xml => $id) {
	if (($handle = fopen("air_quality.csv", "r")) !== FALSE) {
	echo "Opened file";
	echo '<pre>'; var_dump($xml);
    
	# define the tags - last col value in csv file is derived so ignore
	$header = array('id', 'desc', 'date', 'time', 'nox', 'no', 'no2', 'lat', 'long');
	
	# throw away the first line - field names
	fgetcsv($handle, 200, ",");
	
	# count the number of items in the $header array so we can loop using it
	$cols = count($header);

	echo $cols + " headers";
	
	#set record count to 1

	$count = 1;

	# set row count to 2 - this is the row in the original csv file
	$row = 2;
		
	# start ##################################################
	$out = '<records>';
	
	while (($data = fgetcsv($handle, 200, ",")) !== FALSE) {
        
        if ($data[0] == $id) {
			$rec = '<row count="' . $count . '" id="' . $row . '">';
		
			for ($c=0; $c < $cols; $c++) {
				$rec .= '<' . trim($header[$c]) . ' val="' . trim($data[$c]) . '"/>';
			}
			$rec .= '</row>';
			$count++;
			$out .= $rec;

		}
		$row++;
	}
	
	$out .= '</records>';

	file_put_contents($xml, $out);
	
	fclose($handle);
}
echo "....all done!";
}
?>