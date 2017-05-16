<?php

// Harvest Sherborn mapping from ION

require_once(dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');

$basedir = 'html';
$files1 = scandir(dirname(__FILE__) . '/' . $basedir);

foreach ($files1 as $directory)
{
	if (preg_match('/^\d+$/', $directory))
	{	
		$files2 = scandir(dirname(__FILE__) . '/' . $basedir . '/' . $directory);
		foreach ($files2 as $filename)
		{
			if (preg_match('/^(?<id>\d+)\.html$/', $filename, $m))
			{	
				//echo $filename . "\n";
				$html = file_get_contents(dirname(__FILE__) . '/' . $basedir . '/' . $directory . '/' . $filename);

				$dom = str_get_html($html);

				$lis = $dom->find('ul li');
				foreach ($lis as $li)
				{
					if (preg_match('/(?<micro>.*)\s+\[Index Animalium Entry\]/', $li->plaintext, $m)) 
					{ 
						$obj = new stdclass;
						$obj->ion = str_replace('.html', '', $filename);
						$obj->citation = $m['micro'];
		
						foreach ($li->find('a') as $a)
						{
							if (preg_match('/biodiversitylibrary/', $a->href))
							{
								$obj->PageID = str_replace('http://biodiversitylibrary.org/page/', '', $a->href);
							}		
							else
							{
								$obj->IndexAnimalium = $a->href;
							}
						}
		
						print_r($obj);
					}
				}
			}
		}
	}
}

?>
