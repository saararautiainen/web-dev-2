<?php

$xmlArray = array('brislington.xml' => 'brislington_no2.xml', 'fishponds.xml' => 'fishponds_no2.xml', 'newfoundland_way.xml' => 'newfoundland_way_no2.xml', 'parson_st.xml' => 'parson_st_no2.xml', 'wells_rd.xml' => 'wells_rd_no2.xml', 'rupert_st.xml' => 'rupert_st_no2.xml');

foreach ($xmlArray as $input => $output) {
    //Open reader and XML file
$reader = new XMLReader();
$reader -> open($input);

if (!$reader) {
    die("Failed to open 'data.xml'");
} 

//Empty array to be able to push readings into it
$values = array();

while($reader->read()) {

   if($reader-> nodeType == XMLReader::ELEMENT) {

//Switch statement, each case is a different row from which I want to get the value of the attribute.
   switch ($reader->localName) {
    case 'time':
        $value['time'] = (string)$reader->getAttribute('val');
        break;
    case 'date':
        $value['date']= (string)$reader->getAttribute('val');
        break;
    case 'no2':
        $value['no2']= (string)$reader->getAttribute('val');
        break;
    case 'lat':
        $lat= (string)$reader->getAttribute('val');
        break;
    case 'long':
        $long= (string)$reader->getAttribute('val');
        //In the final case I push all of the values into the "values" array to be later put into a new XML file. Lat and long don't need to go in because they're used only once.
        $values[] = $value;
        break;   
  }
  }
}

$placeID = str_replace ("_", " ", $input);
$placeID = rtrim($placeID, '.xml');

$reader->close();
echo "All done!";

//New XML writer 
$writer = new XMLWriter();  
$writer->openURI($output);  
$writer->startDocument('1.0','UTF-8');  
$writer->setIndent(4);   
$writer->startElement('data');
  $writer->writeAttribute('type', 'Nitrogen Dioxide');   
   $writer->startElement('location');  
         $writer->writeAttribute('id', $placeID);  
         $writer->writeAttribute('lat', $lat ); 
         $writer->writeAttribute('long',$long ); 
  foreach ($values as $row) {
  $writer->startElement('reading');
  $writer->writeAttribute('date', $row['date']);
  $writer->writeAttribute('time', $row['time']);
  $writer->writeAttribute('val', $row['no2']);
  $writer->endElement();
}
      
$writer->endElement();  
   
$writer->endDocument();   
$writer->flush();
}


?>