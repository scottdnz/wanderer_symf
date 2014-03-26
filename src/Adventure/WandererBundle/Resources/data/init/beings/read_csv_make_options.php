<?php

$raceArr = array();
$handle = fopen("being_data_sorted.csv", "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
  $raceArr[] = $data[0];
}
fclose($handle);

$fp = fopen("being_data_options.txt", "w");
for ($i = 0; $i < count($raceArr); $i++) {
  $ln = sprintf("<option value=\"%s\">%s</option>\n", $raceArr[$i], $raceArr[$i]);
//  fwrite($fp, "<option value=\"" . "\">" . $raceArr[$i] . "</option>\n");
  fwrite($fp, $ln);
}
fclose($fp);

echo "File being_data_options.txt written" 

?>

